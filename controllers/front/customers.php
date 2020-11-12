<?php
include(dirname(__FILE__).'..\..\classes\queryBuilder.php');

class DmixapiCustomersModuleFrontController extends ModuleFrontController
{
    private $resource = "customers";

    public function display()
    {

        return false;
    }

    public function init()
    {
        header('Content-Type: application/json');
        parent::init();
    }

    public function initContent()
    {
        $conf = include(dirname(__FILE__).'\..\..\config\api.php');
        $query = new QueryBuilder($conf['resources'][$this->resource]);
        $modifiers = Tools::getValue('modifiers');
        $id = Tools::getValue('id');

        if ($id != null){
          $query->addWhere('c.id_customer', $id);
        }

        if ($modifiers != null){
          $modifiers = unserialize($modifiers);
          $query->addModifiers($modifiers);
        }

        $sql = $query->toString();
        if ($id != null){
          echo (json_encode(Db::getInstance()->getRow($sql)));
        } else {
          echo (json_encode(Db::getInstance()->executeS($sql)));
        }
    }
}
