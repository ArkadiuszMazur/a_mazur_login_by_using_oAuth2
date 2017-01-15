<?php

class AppController extends GlobalController {

    protected $_model;
    
    public function init() {
        $this->_model = new Model_appModel();
        $this->_helper->layout()->disableLayout();
        parent::init();
    }

    public function indexAction() {        
//        if (!$this->_sess->logged) {
//            $this->_helper->redirector->gotoUrl('/signin');
//        }
        
        $items = $this->_model->getList();        
        if(empty($items)) {
            return;
        }
        
        $items = json_encode($items);
        exit($items);                        
    }

}
