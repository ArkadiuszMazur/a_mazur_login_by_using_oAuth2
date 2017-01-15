<?php

class Model_appModel extends Zend_Db_Table_Abstract {

    public function __construct() {
        parent::__construct();
    }

    /**
     * Retrieve all rows from items table.
     * @return array
     */
    public function getList() {
        $query = $this->getAdapter()->select()->from('items', array('id', 'item'));
        $result = $query->query()->fetchAll(PDO::FETCH_KEY_PAIR);
        return $result;
    }

}
