<?php

class IndexController extends GlobalController {

    public function init() {        
        parent::init();
    }

    public function indexAction() {
        $model = new Model_appModel();                    
    }

    public function signinAction() {           
        if($this->_sess->logged) {
            $this->_helper->redirector->gotoUrl('/');  
        }                
        $ds = DIRECTORY_SEPARATOR;
        $config = new Zend_Config_Ini(APPLICATION_PATH . $ds . 'configs' . $ds . 'application.ini', 'production');                               
        $googleConfigCurrentDomain = $config->google->configCurrentDomain;                 
        $model = new Model_googleapiModel($googleConfigCurrentDomain);       
        
        $getState = $this->_getParam('state');   
        $getCode = $this->_getParam('code');   
        
        /**
         * First request - we redirect to accounts.google.com
         */
        if (!$getCode || !$getState) {
            $this->_sess->uniqueId = uniqid();           
            $accountsUrl = $model->getAccountsUrl($this->_sess->uniqueId, 'email');                        
            $this->_helper->redirector->gotoUrl($accountsUrl);            
        }
        
        /**
         * If communication with API problem occured
         */
        if($getState != $this->_sess->uniqueId) {                        
            $this->_helper->redirector->gotoUrl('/communication_error');
        }
                
        $response = $model->retrieveData();    
        /**
         * No response case 
         */
        if(is_array($response) && empty($response)) { //am_t przywrócić i usunąć poniższe                
            $this->_helper->redirector->gotoUrl('/response_error');
        }
        
        $response = json_decode($response, true);

        $accountEmail = isset($response['email']) ? trim($response['email']) : null;
        /**
         * No email in response
         */
        if (!$accountEmail) {
            // tutaj np. przekierowanie, jeżeli nie udało się pobrać danych konta Google
        }
        
        if(trim($config->authorizedUser) == $accountEmail) {
            $this->_sess->logged = true;
            $this->_sess->loginProcessing = false;
        }
        
        exit(var_dump($config->authorizedUser));
        // Wyświetlamy adres e-mail użytkownika, który dokonał autoryzacji.
        exit(var_dump($accountEmail));        
                
    }
        
    /**
     * After the action, before view is rendering.
     */
    public function postDispatch() {
        $this->view->messages = $this->_flashMessenger->getMessages();
    }

}
