<?php

class IndexController extends GlobalController {

    public function init() {        
        parent::init();
    }

    public function indexAction() {
        $model = new Model_appModel();
    }

    public function signinAction() {         
        $ds = DIRECTORY_SEPARATOR;
        $config = new Zend_Config_Ini(APPLICATION_PATH . $ds . 'configs' . $ds . 'application.ini', 'production');                       
        $googleConfigCurrentDomain = $config->google->configCurrentDomain;                 
        $model = new Model_googleapiModel($googleConfigCurrentDomain);       
        
        $getState = $this->_getParam('state');   
        $getCode = $this->_getParam('code');   
        
        if (!$getCode || !$getState) {
            $this->_sess->uniqueId = uniqid();           
            $accountsUrl = $model->getAccountsUrl($this->_sess->uniqueId, 'email');                        
            $this->_helper->redirector->gotoUrl($accountsUrl);            
        }
            
        if($getState != $this->_sess->uniqueId) {
            //am_t
            //Obsługa błędu
        }
                
        $response = $model->retrieveData();        
        if(is_array($response) && empty($response)) {
            //Zrobić przekierowanie na główną z błedem, że nie przyszła odpowiedź
        }
        
        $response = json_decode($response, true);

        $accountEmail = isset($response['email']) ? $response['email'] : null;
        if (!$accountEmail) {
            // tutaj np. przekierowanie, jeżeli nie udało się pobrać danych konta Google
        }

        // Wyświetlamy adres e-mail użytkownika, który dokonał autoryzacji.
        exit(var_dump($response['email']));
    }
    
    

}
