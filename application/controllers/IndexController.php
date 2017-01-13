<?php

class IndexController extends GlobalController {

    public function init() {
        /* Initialize action controller here */
    }

    public function indexAction() {               
        $model = new Model_appModel();        
        //$model->test();
        
        /**
         * Identyfikator projektu będzie mieć wartość amazur-155511
         * 
         * Identyfikator klienta:
         * 689383590563-fr0gsklufm984eom1451g33ou7fdn448.apps.googleusercontent.com
         * 
         * Tajny klucz klienta:
         * Zux_BXaN1AbZBGMnCbI8bykH
         */
    }

    
    public function signinAction() {
        
    }
}
