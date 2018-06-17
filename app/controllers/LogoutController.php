<?php
/**
 * Created by PhpStorm.
 * User: James
 * Date: 2018/6/10
 * Time: 17:31
 */

namespace app\controllers;

use comphp\base\Controller;

class LogoutController extends Controller
{

    public function init(){
        $this->logoutFuntion();
    }

    private function logoutFuntion(){

        session_start();

        $lang = $_SESSION['lang'];

        $_SESSION = array();

        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }

        session_destroy();

        header( 'Location:login.php' ) ;
    }

}