<?php
include(dirname(__FILE__).'..\..\classes\queryBuilder.php');

class DmixapiImagesModuleFrontController extends ModuleFrontController
{
    private $resource = "images";

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
          $query->addWhere('p.id_product', $id);
        }

        if ($modifiers != null){
          $modifiers = unserialize($modifiers);
          $query->addModifiers($modifiers);
        }

        $sql = $query->toString();
        // if ($id != null){
        //   print_r (Db::getInstance()->getRow($sql));
        // } else {
        // }
        print_r (Db::getInstance()->executeS($sql));
    }
}
