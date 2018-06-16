<?php
namespace app\controllers\util;

class ValidateUtil {
	
	public static function validateUserName($userName){
		
		$userRegxPattern = '/^[a-zA-Z0-9_]{1,30}$/i';
		
		if(preg_match($userRegxPattern,$userName)){
			return true;
		}
		
		return false;
	}

    public static function validatePwdForm($pwd){
        if(preg_match("/^.*(?=.{8,})(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).*$/", $pwd)) {
            return true;
        }else {
            return false;
        }
    }

	public static function validateIfWeakPwd($candidate){

        $r1='/[A-Z]/';  //uppercase
        $r2='/[a-z]/';  //lowercase
        $r3='/[0-9]/';  //numbers
        $r4='/[~!@#$%^&*()\-_=+{};:<,.>?]/';  // special char

        if(preg_match_all($r1,$candidate, $o)<1) {
            return false;
        }
        if(preg_match_all($r2,$candidate, $o)<1) {
            return false;
        }
        if(preg_match_all($r3,$candidate, $o)<1) {
            return false;
        }
        if(preg_match_all($r4,$candidate, $o)<1) {
            return false;
        }
        if(strlen($candidate)<8) {
            return false;
        }

        return true;
	}
	
}
