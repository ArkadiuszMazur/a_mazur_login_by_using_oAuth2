<?php

class Model_itemsModel extends Zend_Db_Table_Abstract {
    
    protected $_name = 'items';

    public function __construct() {
        parent::__construct();
    }
    
    /**
     * Retrieve all rows from items table.
     * @return array
     */
    public function getList() {
        //$query = $this->getAdapter()->select()->from('items', array('id', 'item'));
        $query = $this->getAdapter()->select()->from('items', array('id', 'item'))->order(array('id DESC'));
        $result = $query->query()->fetchAll(PDO::FETCH_KEY_PAIR);
        return $result;
    }    
}
