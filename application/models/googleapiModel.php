<?php   

class Model_googleapiModel extends Zend_Db_Table_Abstract {

    protected $_config;
    protected $_redirectUri;
    protected $_clientId;
    protected $_secretKey;
    
    //am_t usunąć
    public function test() {
        $ds = DIRECTORY_SEPARATOR;
        $config = new Zend_Config_Ini(APPLICATION_PATH . $ds . 'configs' . $ds . 'application.ini', 'production');                
        $googleClientId = $config->google->clientId;
        $googleSecretKey = $config->google->secretKey;
        $googleconfigCurrentDomain = $config->google->configCurrentDomain;
        exit(var_dump($googleClientId));        
        
    }

    public function __construct($redirectUri = null) {
        $ds = DIRECTORY_SEPARATOR;
        $this->_config = new Zend_Config_Ini(APPLICATION_PATH . $ds . 'configs' . $ds . 'application.ini', 'production');        
        $this->_redirectUri = urlencode($redirectUri);
        $this->_clientId = $this->_config->google->clientId;
        $this->_secretKey = $this->_config->google->secretKey;
        parent::__construct();
    }

    public function Dialog($scope = null) {
        $uniqueId = uniqid();
        if (isset($_COOKIE['google_access_state'])) {
            setcookie('google_access_state', null, time() - 3600, '/', '.' . $this->_config->google->configCurrentDomain);
        }
        setcookie('google_access_state', $uniqueId, time() + 3600, '/', '.' . $this->_config->google->configCurrentDomain);

        return sprintf(
                "https://accounts.google.com/o/oauth2/auth?client_id=%s&scope=%s&redirect_uri=%s&state=%s&response_type=code", 
                $this->_clientId, 
                $scope, 
                $this->_redirectUri, 
                $uniqueId
        );
    }

    public function GetAccessToken($apiUserInfo) {
//        if ($this->IsAccessToken($apiUserInfo) === true) {
//            return $_COOKIE['GOOGLE_ACCESS_TOKEN_' . md5($apiUserInfo)];
//        }

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
            return 0;
        }

        setcookie('GOOGLE_ACCESS_TOKEN_' . md5($apiUserInfo), $response['access_token'], time() + $response['expires_in'], '/', $_SERVER['SERVER_NAME']);
        return $response['access_token'];
    }

    public function Get($apiUserInfo = null) {
        $access_token = $this->GetAccessToken($apiUserInfo);
        if (empty($access_token)) {
            return array();
        }

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $apiUserInfo);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Authorization: OAuth ' . $access_token));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($curl);
        curl_close($curl);

        return $response;
    }

//    public function IsAccessToken($scope = null) {
//        if (isset($_COOKIE['GOOGLE_ACCESS_TOKEN_' . md5($scope)])) {
//            return true;
//        }
//
//        return false;
//    }

}
