<?php
/**
 * Created by PhpStorm.
 * User: yac0105
 * Date: 15/06/2018
 * Time: 12:08 PM
 */
//session_save_path( './' );
//session_start();

function msg($s){
    $locale = $_SESSION["lang"];

    //var_dump($_SESSION);

    if (isset(_LANG[$locale][$s])) {
        return _LANG[$locale][$s];
    } else {
        error_log("l10n error: locale: "."$locale, message:'$s'");
    }
}
?>