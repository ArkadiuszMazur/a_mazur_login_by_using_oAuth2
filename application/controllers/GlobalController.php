<?php

/**
 * This is global overriding controller for all programmers controllers.
 * Here are located methods responsible for authorization and authentication.
 */
class GlobalController extends Zend_Controller_Action {

    protected $_sess;
    
    public function init() {
        $this->_sess = new Zend_Session_Namespace('authenticate');        
    }

    public function indexAction() {        
        // action body
    }

}
