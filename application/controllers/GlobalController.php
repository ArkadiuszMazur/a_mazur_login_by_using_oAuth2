<?php

/**
 * This is global overriding controller for all programmers controllers.
 * Here are located methods responsible for authorization and authentication.
 */
class GlobalController extends Zend_Controller_Action {

    protected $_sess;
    protected $_flashMessenger;
    
    public function init() {
        $this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');        
        /**
         * Verification whether the user is logged
         */               
        $this->_sess = new Zend_Session_Namespace('authenticate');                                                                 
        if(!$this->_sess->logged && !$this->_sess->loginProcessing) {
            $this->_sess->loginProcessing = true;
            $this->_helper->redirector->gotoUrl('signin');              
        }
    }

    public function indexAction() {        
        // action body
    }

}
