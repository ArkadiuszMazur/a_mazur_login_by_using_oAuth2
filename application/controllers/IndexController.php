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
        $googleconfigCurrentDomain = $config->google->configCurrentDomain;
        // Tutaj ustawiamy przekierowanie powrotne, na które Google zwróci dane
        $oGoogle_API = new Model_googleapiModel($googleconfigCurrentDomain);

        if (!isset($_GET['code']) OR ! isset($_GET['state'])) {
            $dialog = $oGoogle_API->Dialog('email');
            Header('Location: ' . $dialog);
            exit;
        }

        $google_access_state = empty($_COOKIE['google_access_state']) ? 0 : $_COOKIE['google_access_state'];
        if ($_GET['state'] !== $google_access_state) {
            // tutaj np. przekierowanie, jeżeli dane są błędne
        }

        $graph = $oGoogle_API->Get('https://www.googleapis.com/oauth2/v3/userinfo');
        $graph = json_decode($graph, true);

        if (!isset($graph['email'])) {
            // tutaj np. przekierowanie, jeżeli nie udało się pobrać danych konta Google
        }

        // Wyświetlamy adres e-mail użytkownika, który dokonał autoryzacji.
        exit(var_dump($graph['email']));
    }

}
