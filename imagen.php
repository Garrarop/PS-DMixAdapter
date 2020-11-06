<?php

include(dirname(__FILE__).'/../../config/config.inc.php');
include(dirname(__FILE__).'/../../init.php');

$module_name = 'dmixadapter';

$token = pSQL(Tools::encrypt($module_name.'/ajax.php'));

$module = Module::getInstanceByName($module_name);


if ($module->active){
	if ($_SERVER['REQUEST_METHOD'] === 'GET') {
		$table = pSQL(Tools::getValue('table'));
		$id = pSQL(Tools::getValue('id'));
		echo ("<pre>");
		print_r ($module->search($table, $id));
		echo ("</pre>");
	} else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
		$table = pSQL(Tools::getValue('table'));
		$id = pSQL(Tools::getValue('id'));
		echo ("<pre>");
		print_r ($module->imgUrl());
		echo ("</pre>");
	}
}


 
 
 
 
 