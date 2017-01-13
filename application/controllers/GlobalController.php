<?php

/**
 * This is global overriding controller for all programmers controllers.
 * Here are located methods responsible for authorization and authentication.
 */
class GlobalController extends Zend_Controller_Action {

    public function init() {
        /* Initialize action controller here */
        
        exit(var_dump($this->getOptions()));
      $config = new Zend_Config($this->getOptions(), true);
//    Zend_Registry::set('config', $config);
//    return $config;        
    }

    public function indexAction() {        
        // action body
    }

}
