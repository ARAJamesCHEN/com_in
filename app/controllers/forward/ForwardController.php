<?php
/**
 * Created by PhpStorm.
 * User: yac0105
 * Date: 15/06/2018
 * Time: 10:05 AM
 */

namespace app\controllers\forward;

abstract class ForwardController extends \comphp\base\Controller
{

    public function forwardToLogin(){
        if(isset($_SESSION[ 'userID' ]) && !empty($_SESSION[ 'userID' ])){

            $this->_usrId = $_SESSION[ 'userID' ];

            return false;

        }else{

            if(!strrpos(strtolower($this->_viewName), 'login')){
                header( 'Location:Login.php?forward' ) ;
            }

            return true;
        }
    }

}