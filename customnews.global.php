<?php

/* ====================
  [BEGIN_COT_EXT]
  Hooks=global
  Order=1000
  [END_COT_EXT]
  ==================== */

/**
 * customnews for Cotonti CMF
 *
 * @version 3.00
 * @author esclkm littledev.ru
 * @copyright (с) 2011 esclkm littledev.ru
 */
defined('COT_CODE') or die('Wrong URL');

require_once $cfg['system_dir'].'/cotemplate.php';
require_once cot_incfile('page', 'module');
require_once cot_incfile('users', 'module');

//customnews Tabs List
if (!$customnewstabs)
{
	$cfg['plugin']['customnews']['tabs'] = (!empty($cfg['plugin']['customnews']['tabs'])) ? $cfg['plugin']['customnews']['tabs'] : 'first|5|all';
	$set = explode("\n", str_replace("\r\n", "\n", $cfg['plugin']['customnews']['tabs']));

	foreach ($set as $val)
	{
		$val = explode('|', $val);
		$val[1] = (float)(trim($val[1]));
		$val[0] = trim($val[0]);
		$val[2] = trim($val[2]);

		$customnewstabs[$val[0]] = array(
			'code' => $val[0],
			'count' => ($val[1] > 0) ? $val[1] : $cfg['plugin']['customnews']['count'],
			'part' => empty($val[2]) ? 'all' : $val[2],
			'where' => empty($val[3]) ? '' : ' AND '.$val[3],
			'order' => empty($val[4]) ? 'page_date DESC' : $val[4],
			'cat' => empty($val[5]) ? '' : $val[5],
		);
	}
	$cache && $cache->db->store('customnewstabs', $customnewstabs, 'system', 1200);
}

//end customnews Tabs List
if ($env['ext'] != 'admin')
{
	
	foreach ($customnewstabs as $tab => $tabinfo)
	{
		$bbenable = false;
		if ($tabinfo['part'] == 'all')
		{
			$bbenable = true;
		}
		elseif ($tabinfo['part'] == 'ajax' && defined('COT_AJAX'))
		{
			$bbenable = true;
		}
		elseif ($tabinfo['part'] == 'index' && $env['ext'] == 'index')
		{
			$bbenable = true;
		}		
		elseif ($tabinfo['part'] == $_GET['e'])
		{
			$bbenable = true;
		}

		if ($bbenable && (!COT_AJAX || cot_import('bbtab', 'G', 'TXT') == $tab))
		{
			$t1 = new XTemplate(cot_tplfile(array('customnews', $tab), 'plug'));
			if (!empty($tabinfo['cat']) && $tabinfo['cat'] != 'incat' && isset($structure['page'][$tabinfo['cat']]))
			{
				$customnewscats = cot_structure_children('page', $tabinfo['cat']);
				$customnewscats = (count($customnewscats)) ? " AND page_cat IN ('".implode("', '", $customnewscats)."')" : '';
				$bbcatwhere = $customnewscats;
			}
			elseif ($tabinfo['cat'] == 'incat' && $_GET['e'] == 'page' && (isset($structure['page'][$_GET['c']]) || $structure['page'][$pag['page_cat']]))
			{
				$bbcat = (isset($structure['page'][$_GET['c']])) ? $_GET['c'] : $structure['page'][$pag['page_cat']];
				$customnewscats = cot_structure_children($bbcat);
				$bbcatwhere = (count($customnewscats)) ? " AND page_cat IN ('".implode("', '", $customnewscats)."')" : '';
			}
			
			/* === Hook === */
			foreach (cot_getextplugins('customnews.query') as $pl)
			{
				include $pl;
			}
			/* ===== */
			
			$bbsql = $db->query("SELECT p.* $cns_join_columns
			FROM $db_pages AS p $cns_join_tables
			WHERE page_state='0' $bbcatwhere {$tabinfo['where']} 
			ORDER BY {$tabinfo['order']} LIMIT {$tabinfo['count']}");

			$jj = 0;
			while ($bbrow = $bbsql->fetch())
			{

				$jj++;
				$t1->assign(cot_generate_pagetags($bbrow, "PAGE_ROW_"));

				$t1->assign(array(
					"PAGE_ROW_NUM" => $jj,
					"PAGE_ROW_ODDEVEN" => cot_build_oddeven($jj),
					"PAGE_ROW_TITLE_CUT" => cot_cutstring($bbrow['page_title'], 21),
				));

				$t1->parse("CUSTOMNEWS.PAGE_ROW");
			}
			$t1->parse("CUSTOMNEWS");
			$CNS[strtoupper($tab)] = $t1->text("CUSTOMNEWS");
			$CNS_AJAX = &$CNS[strtoupper($tab)];
		}
	}
}
?>