<?php

class AppController extends GlobalController {

    protected $_model;

    public function init() {
        parent::init();
        //No access for not logged
        if (!$this->_sess->logged) {
            $this->_helper->redirector->gotoUrl('/signin');
        }
        $this->_model = new Model_appModel();
        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
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
        $check = $this->_model->remove($id);
        exit(json_encode($check));
    }

}
