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
		switch ($table) {
			case 'products':
				return $this->traerArticulos($id);
				break;
			case 'categories':
				return $this->traerCategorias($id);
				break;
			case 'combos':
				return $this->traerCombos($id);
				break;
		}
	}
	
	public function traerArticulos($id)
	{
		$busqueda = "select p.id_product as id, pl.name, pl.description_short, pl.description,p.price,i.id_image,cl.name as category, (
					 SELECT GROUP_CONCAT(agl.name, '_',al.name ORDER BY agl.id_attribute_group SEPARATOR '-') as attribute_designation 
					 FROM "._DB_PREFIX_."product_attribute_combination pac 
					 LEFT JOIN "._DB_PREFIX_."attribute a ON a.id_attribute = pac.id_attribute 
					 LEFT JOIN "._DB_PREFIX_."attribute_group ag ON ag.id_attribute_group = a.id_attribute_group 
					 LEFT JOIN "._DB_PREFIX_."attribute_lang al ON (a.id_attribute = al.id_attribute) 
					 LEFT JOIN "._DB_PREFIX_."attribute_group_lang agl ON (ag.id_attribute_group = agl.id_attribute_group AND agl.id_lang = al.id_lang) 
					 WHERE pac.id_product_attribute IN (SELECT pa.id_product_attribute FROM "._DB_PREFIX_."product_attribute pa WHERE pa.id_product = p.id_product 
					 and al.id_lang = pl.id_lang and default_on = 1 GROUP BY pa.id_product_attribute) 
					 GROUP BY pac.id_product_attribute) as combo_default
					 from "._DB_PREFIX_."product p
					 inner join "._DB_PREFIX_."product_lang pl on pl.id_product = p.id_product 
					 inner join "._DB_PREFIX_."category_lang cl on cl.id_category = p.id_category_default and cl.id_lang = pl.id_lang
					 left join "._DB_PREFIX_."image i on i.id_product = p.id_product and i.cover = 1 
					 where p.active = 1 and pl.id_lang = ".(int)$this->context->language->id;

		if ($id != null) {
			$busqueda .= " AND p.id_product = ".$id;
			return JSON_encode(Db::getInstance()->getRow($busqueda));
		}
		
        return JSON_encode(Db::getInstance()->executeS($busqueda));
    }
	
	public function traerCombos($id)
	{	
		if ($id == null) {
			die('Por favor selecciona un id');
		}
		$busqueda = "SELECT pac.id_product_attribute, pa.id_product, sum(pa.price) as price, pai.id_image,
					 (SELECT SUM(quantity) from "._DB_PREFIX_."stock_available where id_product_attribute = pac.id_product_attribute) as stock, 
					 GROUP_CONCAT(agl.name, '_',al.name ORDER BY agl.id_attribute_group SEPARATOR '-') as combo,
					 group_concat(al.id_attribute) as id_attributes_combo
					 FROM "._DB_PREFIX_."product_attribute_combination pac 
					 LEFT JOIN "._DB_PREFIX_."attribute a ON a.id_attribute = pac.id_attribute 
					 LEFT JOIN "._DB_PREFIX_."attribute_group ag ON ag.id_attribute_group = a.id_attribute_group 
					 LEFT JOIN "._DB_PREFIX_."attribute_lang al ON a.id_attribute = al.id_attribute 
					 LEFT JOIN "._DB_PREFIX_."attribute_group_lang agl ON ag.id_attribute_group = agl.id_attribute_group AND agl.id_lang = al.id_lang
					 left join "._DB_PREFIX_."product_attribute_image pai on pai.id_product_attribute = pac.id_product_attribute
					 inner join "._DB_PREFIX_."product_attribute pa on pa.id_product_attribute = pac.id_product_attribute
					 WHERE al.id_lang = ".(int)$this->context->language->id." and pa.id_product = ".$id."
					 GROUP BY pac.id_product_attribute, pai.id_image";
		
        return JSON_encode(Db::getInstance()->getRow($busqueda));
    }
	
	public function traerCategorias($id)
	{
		$select = "select c.id_category, c.id_parent, cl.name, group_concat(cp.id_product) as id_products, cl.description
					 from "._DB_PREFIX_."category c
					 inner join "._DB_PREFIX_."category_lang cl on cl.id_category = c.id_category 
					 inner join "._DB_PREFIX_."category_product cp on cp.id_category = c.id_category ";
					 
		$where  = "where cl.id_lang = ".(int)$this->context->language->id; 
		if ($id != null) {
			$where .= " AND c.id_category = ".$id;
		}
		$group  = " group by c.id_category";
		
		$busqueda = $select.$where.$group;
		if ($id != null) {
			return JSON_encode(Db::getInstance()->getRow($busqueda));
		}
        return JSON_encode(Db::getInstance()->executeS($busqueda));
    }
	
	public function imgUrl(){
		
		$busqueda = 'SELECT "._DB_PREFIX_."product.id_product, "._DB_PREFIX_."image.id_image 
					 FROM "._DB_PREFIX_."product, "._DB_PREFIX_."image
					 WHERE "._DB_PREFIX_."image.id_product = "._DB_PREFIX_."product.id_product and "._DB_PREFIX_."product.id_product = 11';
		
		$rows = Db::getInstance()->executeS($busqueda);
		
		$resultado = "";
		foreach($rows as $row) {
			$url = 'http://localhost/Presta/img/p/'.implode('/', str_split((string) $row["id_image"])).'/'.$row["id_image"].'.jpg';
			$resultado.= $url."<br>";
		}

		return $resultado;
	}
}

