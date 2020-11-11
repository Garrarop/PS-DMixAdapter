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
        $this->bootstrap = true;
      	$this->controllers = ['task'];
    }

    public function install()
    {
        return parent::install() &&
            $this->registerHook('actionDispatcherBefore');
    }

    public function hookActionDispatcherBefore($params)
    {
        if (isset($_GET['dmix'])) {
            die;
        }
    }
}
