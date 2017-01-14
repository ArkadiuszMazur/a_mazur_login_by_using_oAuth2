<?php   

class Model_googleapiModel extends Zend_Db_Table_Abstract {

    protected $_config;
    protected $_redirectUri;
    protected $_clientId;
    protected $_secretKey;    
    protected $_apiUserData;    

    public function __construct($redirectUri = null) {
        $ds = DIRECTORY_SEPARATOR;
        $this->_config = new Zend_Config_Ini(APPLICATION_PATH . $ds . 'configs' . $ds . 'application.ini', 'production');        
        $this->_redirectUri = urlencode($redirectUri);
        $this->_clientId = $this->_config->google->clientId;
        $this->_secretKey = $this->_config->google->secretKey;
        $this->_apiUserData = $this->_config->google->apiUserData;       
        parent::__construct();
    }

    public function retrieveData() {        
        
        $token = $this->_getToken($this->_apiUserData);
        if (!$token) {
            return;
        }

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $this->_apiUserData);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Authorization: OAuth ' . $token));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($curl);
        curl_close($curl);

        return $response;
    }    
    
    /**
     * 
     * @param type $uniqueId - unique Id used to verification of proper communication
     * @param string $scope - service type
     * @return string
     */
    public function getAccountsUrl($uniqueId, $scope = null) {        
        return sprintf(
                "https://accounts.google.com/o/oauth2/auth?client_id=%s&scope=%s&redirect_uri=%s&state=%s&response_type=code", 
                $this->_clientId, 
                $scope, 
                $this->_redirectUri, 
                $uniqueId
        );
    }

    protected function _getToken($apiUserInfo) {

        $params = sprintf(
                "code=%s&client_id=%s&client_secret=%s&redirect_uri=%s&grant_type=authorization_code", 
                $_GET['code'], 
                $this->_clientId, 
                $this->_secretKey, 
                $this->_redirectUri
        );

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, 'https://accounts.google.com/o/oauth2/token');
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $params);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $response = json_decode(curl_exec($curl), true);
        curl_close($curl);

        if (!isset($response['access_token'])) {
            return;
        }

        //setcookie('GOOGLE_ACCESS_TOKEN_' . md5($apiUserInfo), $response['access_token'], time() + $response['expires_in'], '/', $_SERVER['SERVER_NAME']);
        return $response['access_token'];
    }



}
