<?php

$db = DBCON();

 if(!empty($_POST['formdata'])){


    //Iniciar Sesion
    if ($_POST['formdata'] == "LOGIN"){        
            LOGIN($db);
            $check=CHECKUSER($db);
    } else if ($_POST['formdata'] == "REGISTER"){        
            REGISTER();
    }

} else {
    //Comprueba cada vez que cargas una pagina el usuario y contraseña por seguridad
    $check=CHECKUSER($db);
}

//Cerrar Sesion
if (isset($_POST['logout'])) {
    session_destroy();
    header("Location: admin.php");
    exit;
}

function DBCON(){

    // Establece conexion con la base de datos

    $user = "root";
    $server = "mariadb";
    $password = "rootpassword";

    $db = NEW PDO ("mysql:host=$server",$user,$password);
    return $db;

}
function REGISTER(){
    
}
function LOGIN($db){

    $name = $_POST["username"];
    $pass = $_POST["password"];

    $sql = "USE USERS_DB;";
    $db->query($sql);

    $sql = "SELECT USER_NAME FROM USERS WHERE USER_NAME = '".$name."' AND USER_PASSWORD = '".$pass."';";
    $result=$db->query($sql);

    $result=$result->fetch(PDO::FETCH_ASSOC);

    if (!empty($result["USER_NAME"]) == $name){

        $_SESSION["USER"] = $_POST["username"];
        $_SESSION["PASS"] = $_POST["password"];

    }


}

function CHECKUSER($db){

    if (!empty($_SESSION["USER"]) && !empty($_SESSION["PASS"])){
        $sql = "USE USERS_DB;";
        $db->query($sql);

        $sql = "SELECT USER_NAME FROM USERS WHERE USER_NAME = '".$_SESSION["USER"]."' AND USER_PASSWORD = '".$_SESSION["PASS"]."';";
        $result=$db->query($sql);

        $result=$result->fetch(PDO::FETCH_ASSOC);

        if (!empty($result["USER_NAME"])){
            return true;
        } else {
            return false;
        }

    } else {
        return false;
    }

}


function CREATEWORLD($db){

    // Genera los mundos
    // En caso de no existir ningun mundo genera "mundo1"
    // En caso de que existan mundos les suma 1 ej: "mundo2", "mundo3",...


    // Selecciono todos las DB que se llamen mundo% y recoge el ultimo y lo guarda en $name
    $sql = "SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME LIKE 'mundo%' ORDER BY SCHEMA_NAME DESC LIMIT 1";

    $result = $db->query($sql);
    $name = $result->fetchALL();

    if ($name[0][0]) { // Si existe algun mundo
        $num = substr($name[0][0], 5);
        $num++;
        $txt = "MUNDO";
        $txt .= $num;
        $db->query("CREATE DATABASE `".$txt."`;");
    } else { // Genera el primer mundo
        
        $db->query("CREATE DATABASE MUNDO1");
    }

    $db = null; // Cierro la conexion con la base de datos

}


?>