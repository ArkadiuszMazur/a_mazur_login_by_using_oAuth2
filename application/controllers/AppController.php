<?php

/**
 * Actions management items in database called by ajax.
 */
class AppController extends GlobalController {

    protected $_model;

    public function init() {
        parent::init();
        //No access for not logged
        if (!$this->_sess->logged) {
            $this->_helper->redirector->gotoUrl('/signin');
        }        
        $this->_model = new Model_itemsModel();
        $this->_helper->layout()->disableLayout(); //No layout
        $this->_helper->viewRenderer->setNoRender(true); //No view
    }

    /**
     * Return item list.
     * @return json
     */
    public function getitemsAction() {
        $items = $this->_model->getList();
        if (empty($items)) {
            return;
        }

        $items = json_encode($items);
        exit($items);
    }

    /**
     * Remove item by id
     * return bool
     */
    public function removeAction() {
        $id = (int) $this->_getParam('id');
        //$check = $this->_model->remove($id);
        $check = (bool)$this->_model->delete(array('id = ?' => $id));
        exit(json_encode($check));
    }

    public function insertAction() {
        $item = $this->_getParam('item');
        $this->_model = new Model_itemsModel();
        $lastId = $this->_model->insert(array('item' => $item));
        exit($lastId);
    }
}
