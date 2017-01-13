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
         * 628300487987-i3l234emnhsml1e15ueqmm2349me6oad.apps.googleusercontent.com
         * 
         * Tajny klucz klienta:
         * zM80nyB_AxahePWtPyYcYj2X
         */
    }

    
    public function signinAction() {
        $model = new Model_googleapiModel();
        exit(var_dump($model));
        
        
    }
}
