<?php
/**
 * Created by PhpStorm.
 * Run me after you run the php_com.sql script
 * User: James
 * Date: 2018/6/10
 * Time: 10:38
 */

define('APP_PATH', __DIR__ . '/');

use app\models\modelbusiness\modelEntity\UserModel;
include(APP_PATH. 'comphp/db/' .'db.php');
include(APP_PATH. 'comphp/db/' .'sql.php');
include(APP_PATH. 'comphp/base/' .'Model.php');
include(APP_PATH . 'app/models/modelbusiness/modelEntity/' . 'UserModel.php');
require(APP_PATH . 'comphp/Comphp.php');
$config = require(APP_PATH . 'config/config.php');

(new comphp\Comphp($config))->setDbConfig();

function update(){

    /*"James001",SHA2("James0101", 256),"M", "1990-01-01","james@hotmail.com","0103007536","Christchurch","G");
      "Amit001",SHA2("Amit0101", 256),"M", "1970-01-01","amit@ac.nz","0118527895","Christchurch","G");
      Mate001",SHA2("Mate0101", 256),"F", "2001-01-01","mate@ac.nz","0152365895","Auckland","G");
      "James002",SHA2("James0102", 256),"M", "1990-01-01","james@hotmail.com","0103007536","Christchurch","H");
      "Amit002",SHA2("Amit0102", 256),"M", "1970-01-01","amit@ac.nz","0118527895","Christchurch","H");
      Mate002",SHA2("Mate0102", 256),"F", "2001-01-01","mate@ac.nz","0152365895","Auckland","H");*/

    $passWordArray = array(
        'James001' => 'James0101',
        'newOne001' => 'newOne0101',
        'newTwo002' => 'newTwo0102',
        'James002'=> 'James0102',
        'Amit002' => 'Amit0102',
        'Mate002' => 'Mate0102'
    );

    $count = 0;

    foreach($passWordArray as $usrName => $usrPwd){
        $hash = password_hash($usrPwd, PASSWORD_BCRYPT);

        $data = array('loginPwd' => $hash);

        $count = (new UserModel)->where(['loginName = ?', "or sys='G'"], [$usrName])->update($data);

        echo "$count rows has been updated!<br>";
    }

    if($count){
        header( 'Location:login.php?forward' ) ;
    }

}

update();