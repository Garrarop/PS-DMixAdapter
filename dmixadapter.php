<?php

class DMixAdapter extends Module
{
    public function __construct(){
        $this->name = 'dmixadapter';
        $this->version = '1.0.0';
        $this->author = 'Garraro';
        parent::__construct();
        $this->displayName = $this->l('DMixAdapter');
        $this->description = $this->l('Adaptador para conectar Prestashop con el sistema de DigitalMix');
    }

    public function install()
    {
        if (!parent::install()) {
            return false;
        }
        return true;
    }

    public function uninstall()
    {
        if (!parent::uninstall()) {
            return false;
        }
        return true;
    }
    
    public function search($table, $id)
    {
		$table = str_replace('"','', $table);
		$table = str_replace("'",'', $table);
		$table = str_replace('*', '%', $table);
		$id = str_replace('"','', $id);
		$id = str_replace("'",'', $id);
		
		$busqueda = 'SELECT *
            FROM '._DB_PREFIX_.'product_lang
            WHERE id_lang = '.(int)$this->context->language->id.'
            AND `name` LIKE "%'.$table.'%"';

		if ($id != null) {
			$busqueda .= ' AND `id_product` = '.$id;
		}
		
        return Db::getInstance()->executeS($busqueda);
    }
}