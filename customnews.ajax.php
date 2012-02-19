<?php
/* ====================
[BEGIN_COT_EXT]
Hooks=ajax
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

cot_sendheaders();
echo $CNS_AJAX;
ob_end_flush();

?>