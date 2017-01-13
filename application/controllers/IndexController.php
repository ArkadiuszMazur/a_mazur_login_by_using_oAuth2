<?php

class IndexController extends GlobalController {

    public function init() {
        /* Initialize action controller here */
    }

    public function indexAction() {         
        $model = new Model_appModel();        
        $model->test();
    }

}
