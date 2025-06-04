<?php


$db = DBCON();

//Cerrar Sesion
if (isset($_POST['logout']) || isset($_GET['logout'])) {
    session_destroy();
    header("Location: admin.php");
    exit;
}

 if(!empty($_POST['formdata'])){


    //Iniciar Sesion
    if ($_POST['formdata'] == "LOGIN"){        
            LOGIN($db,$lang);
            $check=CHECKUSER($db);
    }

} else {
    //Comprueba cada vez que cargas una pagina el usuario y contraseña por seguridad
    $check=CHECKUSER($db);
}

if ($check == true){

     if(isset($_GET['delWorld'])){
        delWorld($db);
    }

    if(isset($_POST['createWorld_form'])){
        CREATEWORLD($db);
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

function LOGIN($db,$lang){

    $name = $_POST["username"];
    $pass = $_POST["password"];

    $sql = "USE USERS_DB;";
    $db->query($sql);

    $sql = "SELECT USER_NAME,USER_PASSWORD FROM USERS WHERE USER_NAME = '".$name."' AND USER_PASSWORD = '".$pass."' AND MODERATOR = 1;";
    $result=$db->query($sql);

    $result=$result->fetch(PDO::FETCH_ASSOC);

    //Da mensaje de error por usuario inexistente o contraseña
    if(empty($result)){
        $sql = "SELECT USER_NAME,USER_PASSWORD FROM USERS WHERE USER_NAME = '".$name."' AND MODERATOR = 1;";
        $result2=$db->query($sql);
        $result2=$result2->fetch(PDO::FETCH_ASSOC);

        if(empty($result2)){
            echo "<div class='error'>".$lang["error_1"]."</div>";
        } else {
            echo "<div class='error'>".$lang["error_2"]."</div>";
        }

    }

    if (!empty($result["USER_NAME"]) && $result["USER_NAME"] == $name && $result["USER_PASSWORD"] == $pass) {

        $_SESSION["USER"] = $_POST["username"];
        $_SESSION["PASS"] = $_POST["password"];

    }


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


function CREATEWORLD($db){

    // Genera los mundos
    // En caso de no existir ningun mundo genera "mundo1"
    // En caso de que existan mundos les suma 1 ej: "mundo2", "mundo3",...

    $_POST['delWorld'] = "0";
    $_GET['delWorld'] = "0";

    // Selecciono todos las DB que se llamen mundo% y recoge el ultimo y lo guarda en $name
    $sql = "SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME LIKE 'mundo%' ORDER BY CAST(SUBSTRING(SCHEMA_NAME, 6) AS UNSIGNED) DESC LIMIT 1;";

    $result = $db->query($sql);
    $name = $result->fetchALL();

    if ($name[0][0]) { // Si existe algun mundo
        $num = substr($name[0][0], 5);
        $num++;
        $txt = "MUNDO";
        $txt .= $num;
        $db->query("CREATE DATABASE `".$txt."`;");

        
    
    } else { // Genera el primer mundo
        
        $txt = "MUNDO1";
        $db->query("CREATE DATABASE `".$txt."`;");
    }

    $db->query("USE ".$txt.";");

    // Se crean las tablas de la Base de datos
    $db->query("
        CREATE TABLE IF NOT EXISTS PLAYERS(
            PLAYER_ID INT NOT NULL AUTO_INCREMENT,
            PLAYER_NAME VARCHAR(14) NOT NULL UNIQUE,
            USER_ID INT NOT NULL,
            CONSTRAINT PLAYERS_PK PRIMARY KEY (PLAYER_ID,USER_ID)
        );

        CREATE TABLE IF NOT EXISTS TOWN(
            TOWN_ID INT NOT NULL AUTO_INCREMENT,
            PLAYER_ID INT NOT NULL,
            WOOD INT NOT NULL CHECK (WOOD <= 500),
            FOOD INT NOT NULL CHECK (FOOD <= 500),
            STONE INT NOT NULL CHECK (STONE <= 500),
            IRON INT NOT NULL CHECK (IRON <= 500),
            CONSTRAINT TOWN_PK PRIMARY KEY (TOWN_ID),
            FOREIGN KEY (PLAYER_ID) REFERENCES PLAYERS(PLAYER_ID)
        );

        CREATE TABLE IF NOT EXISTS TOWN_BUILDINGS(
            TOWN_ID INT NOT NULL,
            BUILDING VARCHAR(50) NOT NULL,
            FOREIGN KEY (TOWN_ID) REFERENCES TOWN(TOWN_ID)
        );

        CREATE TABLE IF NOT EXISTS MAP(
            SQUARE_ID INT NOT NULL,
            POS_X INT NOT NULL,
            POS_Y INT NOT NULL,
            PLAYER_ID INT,
            TOWN_ID INT,
            CONSTRAINT MAP_PK PRIMARY KEY (SQUARE_ID),
            FOREIGN KEY (TOWN_ID) REFERENCES TOWN(TOWN_ID),
            FOREIGN KEY (PLAYER_ID) REFERENCES PLAYERS(PLAYER_ID)
        );

        CREATE TABLE IF NOT EXISTS ARMY(
            PLAYER_ID INT NOT NULL,
            ARMY_ID INT AUTO_INCREMENT,
            POSITION INT NOT NULL,
            SWORDMAN INT NOT NULL,
            PIKEMAN INT NOT NULL,
            ARCHER INT NOT NULL,
            L_CAVALRY INT NOT NULL,
            H_CAVALRY INT NOT NULL,
            CONSTRAINT ARMY_ID PRIMARY KEY (ARMY_ID),
            FOREIGN KEY (PLAYER_ID) REFERENCES PLAYERS(PLAYER_ID)
        );

        CREATE TABLE IF NOT EXISTS LETTER(
            SENDER_ID INT NOT NULL,
            RECEIVER_ID INT NOT NULL,
            CONTENT VARCHAR (1000),
            FOREIGN KEY (SENDER_ID) REFERENCES PLAYERS(PLAYER_ID),
            FOREIGN KEY (RECEIVER_ID) REFERENCES PLAYERS(PLAYER_ID)
        );

        CREATE TABLE IF NOT EXISTS BATTLE_LOG(
            ATT_ID INT NOT NULL,
            DEF_ID INT NOT NULL,
            FOREIGN KEY (ATT_ID) REFERENCES PLAYERS(PLAYER_ID),
            FOREIGN KEY (DEF_ID) REFERENCES PLAYERS(PLAYER_ID)
        );
    ");


    //Guardo las variables del formulario
    $worldname = $_POST["world_name"];
    $mapsize = $_POST["map_size"];
    $playerqty = $_POST["player_qty"];

    $db->query("use USERS_DB;");
    $db->query("INSERT INTO WORLDSTATUS VALUES ('$worldname', '$txt', 'RUNNING', '$mapsize', '$playerqty');");


    $db = null; // Cierro la conexion con la base de datos
}

function WORLDLIST($db){

    // Entro en USERS_DB
    $sql = "USE USERS_DB;";
    $db->query($sql);

    $sql = "select WORLD_NAME, WORLD_ID, WORLD_STATUS from WORLDSTATUS;";
    $result=$db->query($sql);
    $WORLD = $result->fetchALL();
   
    foreach ($WORLD as $DATA){
        echo "<tr>
            <td>$DATA[WORLD_NAME]</td>
            <td>$DATA[WORLD_ID]</td>
            <td>$DATA[WORLD_STATUS]</td>
            <td style='border: none; text-align: left; padding-left: 10px; width: 20px;'>
                <a href='admin.php?delWorld=$DATA[WORLD_ID]'><img src='assets/icon/trashcan.png' /></a>
            </td>
            </tr>";
    }
    return true;
}

function delWorld($db){
    // Borra el mundo seleccionado

    $sql = "drop database ".$_GET['delWorld'].";";
    $db->query($sql);

    $sql = "use USERS_DB;";
    $db->query($sql);

    $sql = "delete from WORLDSTATUS where WORLD_ID = '".$_GET['delWorld']."';";
    $db->query($sql);

}

function PLAYERLIST($db){

    // Entro en USERS_DB
    $sql = "USE USERS_DB;";
    $db->query($sql);

    $sql = "select * from USERS;";
    $result=$db->query($sql);
    $WORLD = $result->fetchALL();
   
    foreach ($WORLD as $DATA){
        echo "<tr>
            <td>$DATA[USER_ID]</td>
            <td>$DATA[USER_NAME]</td>
            <td>$DATA[EMAIL]</td>
            <td>$DATA[BIRTH_DATE]</td>
            <td>$DATA[MODERATOR]</td>
            </tr>";
    }
    return true;
}

?>