<?php

/**
 * Russian Language File for the customnews Plugin
 *
 * @version 3.00
 * @author esclkm littledev.ru
 * @copyright (c) 2011-2011 esclkm littledev.ru
 */

defined('COT_CODE') or die('Wrong URL.');

/**
 * Plugin Config
 */
$L['cfg_tabs'] = array('Конфигуратор шаблонов','1|2|3|4|5|6<br />
	1. Code<br />
	2. Count<br />
	3. Location (all / ajax / page / index / $_GET[\'e\'])<br />
	4. SQL query (page_shop_instock = \'1\') -- обязательно в формате MySQL!<br />
	5. Sorting (page_date ASC)<br />
	6. Cat parent (страниц страниц только из указанной категории и ее детей)  или incat для поиска в текущем родителе<br />
	Для применения вставьте тег {PHP.CNS.CODE}
	Для Использования произвольных шаблонов, необходимо создать файл формата customnews.code.tpl');
$L['cfg_count'] = array('Количество элементов по умолчанию');

?>