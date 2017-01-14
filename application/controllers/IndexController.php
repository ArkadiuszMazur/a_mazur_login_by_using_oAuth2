<?php

class IndexController extends GlobalController {

    public function init() {
        /* Initialize action controller here */
    }

    public function indexAction() {
        $model = new Model_appModel();
        //$model->test();

        /**
         * Identyfikator projektu będzie mieć wartość amazur-155511
         * 
         * Identyfikator klienta:
         * 628300487987-i3l234emnhsml1e15ueqmm2349me6oad.apps.googleusercontent.com
         * 
         * Tajny klucz klienta:
         * zM80nyB_AxahePWtPyYcYj2X
         */
    }

    public function signinAction() {
        $model = new Model_googleapiModel();

        // Tutaj ustawiamy przekierowanie powrotne, na które Google zwróci dane
        $oGoogle_API = new Google_API('http://oauth2.nx.media.pl/signin.php');

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
        echo $graph['email'];
    }

}
