<?php

class DmixadapterTaskModuleFrontController extends ModuleFrontController
{
    public function __construct()
    {
        echo 'dmix';
        die;
        parent::__construct();
    }

    public function display()
    {
        return true;
    }

    public function init()
    {
        echo 'dmix';
        die;
        parent::init();
    }

    public function initContent()
    {
        echo 'dmix';
        die;
        parent::initContent();
    }
}
