<?php
include(dirname(__FILE__).'..\..\classes\queryBuilder.php');

class DmixapiCombinationsModuleFrontController extends ModuleFrontController
{
    private $resource = "combinations";

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
          $query->addWhere('pac.id_product_attribute', $id);
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
