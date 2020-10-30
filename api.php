<?php

include(dirname(__FILE__).'/../../config/config.inc.php');
include(dirname(__FILE__).'/../../init.php');

$module_name = 'dmixadapter';

$token = pSQL(Tools::encrypt($module_name.'/ajax.php'));
$token_url = pSQL(Tools::getValue('token'));


if ($token != $token_url || !Module::isInstalled($module_name) || null == pSQL(Tools::getValue('table'))) {
    die('Error al ejecutar el ajax');
}

$module = Module::getInstanceByName($module_name);
if ($module->active && $_SERVER['REQUEST_METHOD'] === 'GET') {
	$table = pSQL(Tools::getValue('table'));
	$id = pSQL(Tools::getValue('id'));
    echo json_encode($module->search($table, $id));
}
