<?php
/**
 * Created by PhpStorm.
 * User: yac0105
 * Date: 1/06/2018
 * Time: 7:41 PM
 */

namespace app\controllers;

use app\controllers\formbeans\LoginFormBeanFactory;
use app\models\modelInterface\UsrModelService;
use app\models\modelInterface\UsrModelServiceImpl;
use comphp\base\Controller;
use app\controllers\util\ValidateUtil;
use comphp\base\Handler;

include(APP_PATH. 'app/controllers/util/' .'ValidateUtil.php');
include(APP_PATH . 'app/controllers/formbeans/'.'LoginFormBean.php');


define('LOGIN_ACTION_LOGIN', "login" );
define('LOGIN_ACTION_FORWARD', "forward" );

/**
 * WorkFlow Controller
 * Class LoginController
 * @package app\controllers
 */
class LoginController extends Controller
{

    private $formBean;

    /**
     * init()
     */
    public function init(){

        //var_dump($_SESSION['lang']);

        parent::init();

        $this->formBean = LoginFormBeanFactory::create();

        if($this->_actionName == LOGIN_ACTION_LOGIN){

            $this->loginFunction();

        }elseif ($this->_actionName == LOGIN_ACTION_FORWARD){
            $_SESSION = array();
            $this->formBean->setWarning("Please login first!");
        }

        $this->assign('warning', $this->formBean->getWarning());
        $this->render();
    }

    /**
     *
     */
    public function loginFunction(){

        if( $_SERVER[ 'REQUEST_METHOD' ] == 'POST' ) {
            //valid
            if($this->isValidLogin()){

                $rst = (new Handler())->callUsrModelService(new UsrModelServiceImpl())->searchUsrInfoByLoginName($this->formBean->getFormUsrName());

                $userInfo = $rst->getResult();

				if ( !$userInfo )
                {
                    $this->formBean->setWarning("Not a valid surname, password combination");
                }else{
					if($userInfo->size()>0){

						$userLoginPwd = '';
						$userID = -1;

						while ( $aRow =  $userInfo->fetch() ){

                            $userLoginPwd = $aRow['loginPwd'];
                            $userID = $aRow['userID'];

						}

						if(!$this->checkPwd($this->formBean->getFormUsrPwd(), $userLoginPwd )){

                            $this->formBean->setWarning("You password is wrong!");

                        }else{

                            if(ValidateUtil::validateIfWeakPwd($this->formBean->getFormUsrPwd())){
                                echo "<script type='text/javascript'>alert('Login successfully! Your passwords is weak. We suggest you to modify the password!')</script>";
                            }

                            $_SESSION[ 'theUsrName' ] = $this->formBean->getFormUsrName();
                            $_SESSION[ 'userID' ] = $userID;
                            $_SESSION[ 'thePageName' ] = 'Post';

                            header( 'Location:post.php' ) ;
                        }

			        }else{

                        $this->formBean->setWarning("No User was found!");

			        }
				} 

            }

        }



    }

    /**
     * @param $userInputPwd
     * @param $userLoginPwd
     * @return bool
     */
    private function checkPwd($userInputPwd, $userLoginPwd){
        if (password_verify($userInputPwd, $userLoginPwd)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @return bool
     */
    private function isValidLogin(){

        $hasIssue = false;

        if(isset($_POST['user_name']) && !empty($_POST['user_name'])){

            $this->formBean->setFormUsrName($_POST['user_name']);

            if(!ValidateUtil::validateUserName($this->formBean->getFormUsrName())){
                $this->formBean->setWarning("Please input the right user name");

                $hasIssue = true;
            }
        }

        if(isset($_POST['user_pwd'])&& !empty($_POST['user_pwd'])){
            $this->formBean->setFormUsrPwd($_POST['user_pwd']);

            if(ValidateUtil::validateIfPwdUnleagal($this->formBean->getFormUsrPwd())){
                $this->formBean->setWarning("Please input the right style password");
                $hasIssue = true;
            }
        }

        if($hasIssue){
            return false;
        }

        return true;
    }

}