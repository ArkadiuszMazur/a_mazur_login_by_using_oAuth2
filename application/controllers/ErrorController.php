<?php

class ErrorController extends GlobalController {

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

}
