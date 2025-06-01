<?php

//Cerrar Sesion
if (isset($_POST['logout']) || isset($_GET['logout'])) {
    session_destroy();
    header("Location: index.php");
    exit;
}

$db = DBCON();

 if(!empty($_POST['formdata'])){


    //Iniciar Sesion
    if ($_POST['formdata'] == "LOGIN"){        
            LOGIN($db);
    } else if ($_POST['formdata'] == "REGISTER"){        
            REGISTER($db);
    }

}

function DBCON(){

    // Establece conexion con la base de datos

    $user = "root";
    $server = "mariadb";
    $password = "rootpassword";

    $db = NEW PDO ("mysql:host=$server",$user,$password);
    return $db;

}

function LOGIN($db){

    $name = $_POST["username"];
    $pass = $_POST["password"];

    $sql = "USE USERS_DB;";
    $db->query($sql);

    $sql = "SELECT USER_NAME,USER_PASSWORD FROM USERS WHERE USER_NAME = '".$name."' AND USER_PASSWORD = '".$pass."';";
    $result=$db->query($sql);

    $result=$result->fetch(PDO::FETCH_ASSOC);

    if (!empty($result["USER_NAME"]) && $result["USER_NAME"] == $name && $result["USER_PASSWORD"] == $pass) {

        $_SESSION["USER"] = $_POST["username"];
        $_SESSION["PASS"] = $_POST["password"];

        header("Location: main.php");
    }

}

function REGISTER($db){
    
    $name = $_POST["username"];
    $pass = $_POST["password"];
    $birthdate = $_POST["birthdate"];
    $email = $_POST["email"];

    $sql = "USE USERS_DB;";
    $db->query($sql);

    
    $sql = "INSERT INTO USERS (USER_NAME, USER_PASSWORD, EMAIL, BIRTH_DATE, MODERATOR) VALUES ('".$name."', '".$pass."', '".$email."', '".$birthdate."', 0)";
    $db->query($sql);


}

function CHECKUSER($db){

    // Esta funcion es para incrementar la seguridad
    // Comprueblo que lo que hay en las variables $SESSION USER y PASS sea correcto
    if (!empty($_SESSION["USER"]) && !empty($_SESSION["PASS"])){
        $sql = "USE USERS_DB;";
        $db->query($sql);

        $sql = "SELECT USER_NAME,USER_PASSWORD FROM USERS WHERE USER_NAME = '".$_SESSION["USER"]."' AND USER_PASSWORD = '".$_SESSION["PASS"]."';";
        $result=$db->query($sql);

        $result=$result->fetch(PDO::FETCH_ASSOC);

        if (!empty($result["USER_NAME"]) && $result["USER_NAME"] == $_SESSION["USER"] && $result["USER_PASSWORD"] == $_SESSION["PASS"]){
            return true;
        } else {
            return false;
        }

    } else {
        return false;
    }

}

?>