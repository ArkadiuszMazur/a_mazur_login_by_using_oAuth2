<?php

    define('GOOGLE_CLIENT_ID', '998109724266-las7l0jib3t6juftu3usn1ohevp2e2bm.apps.googleusercontent.com');
    define('GOOGLE_CLIENT_SECRET', 'M53DvCMofwt97xESzNcO9OX3');
    define('CONFIG_CURRENT_DOMAIN', 'http://oauth2.nx.media.pl/signin.php');

class Model_googleapiModel extends Zend_Db_Table_Abstract {

    public function test() {
        exit(var_dump('Test in appModel passed :)'));
    }

    public function __construct($redirect_uri = null) {
        $this->redirect_uri = urlencode($redirect_uri);
        $this->client_id = GOOGLE_CLIENT_ID;
        $this->client_secret = GOOGLE_CLIENT_SECRET;
        parent::__construct();
    }

    public function Dialog($scope = null) {
        $state = uniqid();
        if (isset($_COOKIE['google_access_state'])) {
            setcookie('google_access_state', null, time() - 3600, '/', '.' . CONFIG_CURRENT_DOMAIN);
        }
        setcookie('google_access_state', $state, time() + 3600, '/', '.' . CONFIG_CURRENT_DOMAIN);

        return sprintf("https://accounts.google.com/o/oauth2/auth?client_id=%s&scope=%s&redirect_uri=%s&state=%s&response_type=code", $this->client_id, $scope, $this->redirect_uri, $state
        );
    }

    public function GetAccessToken($scope) {
        if ($this->IsAccessToken($scope) === true) {
            return $_COOKIE['GOOGLE_ACCESS_TOKEN_' . md5($scope)];
        }

        $params = sprintf("code=%s&client_id=%s&client_secret=%s&redirect_uri=%s&grant_type=authorization_code", $_GET['code'], $this->client_id, $this->client_secret, $this->redirect_uri
        );

        $oCURL = curl_init();
        curl_setopt($oCURL, CURLOPT_URL, 'https://accounts.google.com/o/oauth2/token');
        curl_setopt($oCURL, CURLOPT_POST, 1);
        curl_setopt($oCURL, CURLOPT_POSTFIELDS, $params);
        curl_setopt($oCURL, CURLOPT_RETURNTRANSFER, 1);
        $response = json_decode(curl_exec($oCURL), true);
        curl_close($oCURL);

        if (!isset($response['access_token'])) {
            return 0;
        }

        setcookie('GOOGLE_ACCESS_TOKEN_' . md5($scope), $response['access_token'], time() + $response['expires_in'], '/', $_SERVER['SERVER_NAME']);
        return $response['access_token'];
    }

    public function Get($scope = null) {
        $access_token = $this->GetAccessToken($scope);
        if (empty($access_token)) {
            return array();
        }

        $oCURL = curl_init();
        curl_setopt($oCURL, CURLOPT_URL, $scope);
        curl_setopt($oCURL, CURLOPT_HTTPHEADER, array('Authorization: OAuth ' . $access_token));
        curl_setopt($oCURL, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($oCURL);
        curl_close($oCURL);

        return $response;
    }

    public function IsAccessToken($scope = null) {
        if (isset($_COOKIE['GOOGLE_ACCESS_TOKEN_' . md5($scope)])) {
            return true;
        }

        return false;
    }

}
