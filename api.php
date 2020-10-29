<?php

include(dirname(__FILE__).'/../../config/config.inc.php');
include(dirname(__FILE__).'/../../init.php');

$module_name = 'dmixadapter';

$token = pSQL(Tools::encrypt($module_name.'/ajax.php'));
$token_url = pSQL(Tools::getValue('token'));

if ($token != $token_url || !Module::isInstalled($module_name)) {
    die('Error al ejecutar el ajax');
}

$module = Module::getInstanceByName($module_name);
if ($module->active) {
	$search = pSQL(Tools::getValue('search'));
	$search = str_replace('"','', $search);
	$search = str_replace("'",'', $search);
    if ($search != '') {
        echo json_encode($module->searchProducts($search));
    }
}
