<?php
class Model_appModel extends Zend_Db_Table_Abstract {


    public function __construct() {
        parent::__construct();
    }
    
    public function test() {
        exit(var_dump('Test in appModel passed :)'));
    }
        
}