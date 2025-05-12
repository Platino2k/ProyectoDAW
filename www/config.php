<?php


    // Aqui se comprueba el idioma de la sesión y cambia en función del valor que contenga "lang"
    if (!isset($_SESSION['lang'])){
        $_SESSION['lang'] = "es";
    } else if (isset($_GET['lang']) && $_SESSION['lang'] != $_GET['lang'] && !empty($_GET['lang'])){

        if ($_GET['lang'] == "es"){
            $_SESSION['lang'] = "es";
        } else if ($_GET['lang'] == "en"){
            $_SESSION['lang'] = "en";
        } else if($_GET['lang'] == "de"){
            $_SESSION['lang'] = "de";
        }

    }

    // Se llama al archivo con dicho idioma
    require_once "lang/".$_SESSION['lang'].".php";

?>