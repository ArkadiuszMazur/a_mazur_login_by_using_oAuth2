<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap {

    protected function _initDoctype() {
        $this->bootstrap('view');
        $view = $this->getResource('view');
        $view->doctype('XHTML1_STRICT');
    }

    /**
     * Database connection initiate
     */
    protected function _initDbConnection() {
        $this->bootstrap('db');
        $db = $this->getResource('db');
    }

    /**
     * Autoloading models and globalController classes
     * @return \Zend_Application_Module_Autoloader
     */
    protected function _initAutoload() {

        //Include globalController
        $path = APPLICATION_PATH . DIRECTORY_SEPARATOR . 'controllers' . DIRECTORY_SEPARATOR . 'GlobalController.php';
        if (file_exists($path)) {
            include_once($path);
        }

        //Autoloading models
        $autoloader = new Zend_Application_Module_Autoloader(
                array(
            'namespace' => '',
            'basePath' => dirname(__FILE__)
                )
        );
        return $autoloader;
    }

}
