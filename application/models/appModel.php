<?php

class Model_appModel extends Zend_Db_Table_Abstract {

    protected $_db;
    protected $_name = 'items';

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

    /**
     * Remove row from items.
     * @param int $id
     * @return bool
     */
    public function remove($id) {
        $id = (int)$id;
        return (bool)$this->delete(array('id = ?' => $id));
    }

}
