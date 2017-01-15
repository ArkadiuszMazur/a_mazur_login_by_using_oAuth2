<?php

class ErrorController extends GlobalController {

    protected $_config;
    
    public function init() {
        $ds = DIRECTORY_SEPARATOR;
        $this->_config = new Zend_Config_Ini(APPLICATION_PATH . $ds . 'configs' . $ds . 'application.ini', 'production');
        parent::init();
    }

    /**
     * Default Zend error actin
     */
    public function errorAction() {
        $errors = $this->_getParam('error_handler');

        switch ($errors->type) {
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_CONTROLLER:
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ACTION:

                // 404 error -- controller or action not found
                $this->getResponse()->setHttpResponseCode(404);
                $this->view->message = 'Page not found';
                break;
            default:
                // application error 
                $this->getResponse()->setHttpResponseCode(500);
                $this->view->message = 'Application error';
                break;
        }

        $this->view->exception = $errors->exception;
        $this->view->request = $errors->request;
    }

    /**
     * Communication error via googleApi
     */
    public function comerrorAction() {
        $this->_helper->layout->setLayout('error');
        $this->view->errorText = 'I`m sorry, an error occured during communication with google API.';
    }

    /*
     * Response error
     */

    public function reserrorAction() {
        $this->_helper->layout->setLayout('error');
        $this->view->errorText = 'I`m sorry, response from API was not retrieved..';
    }

    public function logerrorAction() {
        if ($this->_sess->account == $this->_config->authorizedUser) {
            $this->_flashMessenger->addMessage('You have been logged properly!');
            $this->_helper->redirector->gotoUrl('/');
        }

        $this->_helper->layout->setLayout('error');
        $this->view->errorText = 'Your currently account is: ' . $this->_sess->account . '. You need to be logged by account amazur.edukey@gmail.com. Please use password: nx.media.pl';
    }

}
