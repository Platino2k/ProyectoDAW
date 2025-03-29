<?php
 if(!empty($_POST['formdata'])){
    if ($_POST['formdata'] == "Crear"){        
        CREATEUSER();
    } else if ($_POST['formdata'] == "Entrar"){
        LOGIN();
    } else if ($_POST['formdata'] == "CrearMundo"){
        $db = DBCON();
        CREATEWORLD($db);
    }
}
function DBCON(){

    // Establece conexion con la base de datos

    $user = "root";
    $server = "localhost";

    $db = NEW PDO ("mysql:host=$server",$user);
    return $db;

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

function CREATEUSER(){
    
}

?>