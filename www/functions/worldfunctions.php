<?php

//Cerrar Sesion
if (isset($_POST['logout']) || isset($_GET['logout'])) {
    session_destroy();
    header("Location: index.php");
    exit;
}

$db = DBCON();
$WORLD = $_GET["world"];

$check = CHECKUSER($db);
if ($check == true){
    ADDPLAYER($db,$WORLD);

}

function DBCON(){

    // Establece conexion con la base de datos

    $user = "root";
    $server = "mariadb";
    $password = "rootpassword";

    $db = NEW PDO ("mysql:host=$server",$user,$password);
    return $db;

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
            header("Location: index.php");
        }

    } else {
        header("Location: index.php");
    }

}

function ADDPLAYER($db,$world){
    // Añade el usuario al mundo
    $sql = "USE USERS_DB;";
    $db->query($sql);

    $sql = ('SELECT USER_ID, USER_NAME from USERS WHERE USER_NAME = "'.$_SESSION['USER'].'" AND USER_PASSWORD = "'.$_SESSION['PASS'].'";');
    $result=$db->query($sql);
    $result=$result->fetch(PDO::FETCH_ASSOC);

    $id = $result['USER_ID'];
    $username = $result['USER_NAME'];

    $sql = "USE ".$world.";";
    $db->query($sql);

    $sql = ('SELECT * FROM PLAYERS WHERE PLAYER_NAME = "'.$username.'" AND USER_ID = "'.$id.'";');
    
    $resultCheck=$db->query($sql);
    $resultCheck=$resultCheck->fetch(PDO::FETCH_ASSOC);

    // Sino existe el usuario lo añade y crea su ciudad
    if(empty($resultCheck["PLAYER_NAME"])){
        $sql = ('INSERT INTO PLAYERS (PLAYER_NAME,USER_ID) VALUES ("'.$username.'","'.$id.'");');
        $db->query($sql);

        $sql = ('SELECT * FROM PLAYERS WHERE PLAYER_NAME = "'.$username.'" AND USER_ID = "'.$id.'";');
    
        $resultCheck=$db->query($sql);
        $resultCheck=$resultCheck->fetch(PDO::FETCH_ASSOC);
        

        CREATETOWN($db,$world,$resultCheck["PLAYER_ID"]);
        TOWNBUILDING($db,$world,$resultCheck["PLAYER_ID"],$id);
    }

}

function CREATETOWN($db,$world,$playerid){
    // CREA UNA CIUDAD
    $sql = "USE ".$world.";";
    $db->query($sql);

    $sql = "INSERT INTO TOWN (PLAYER_ID, WOOD, FOOD, STONE, IRON) VALUES (".$playerid.",400,400,300,300);";
    $db->query($sql);

    
}

function TOWNBUILDING($db,$world,$playerid,$userid){
    // CREA UNA CIUDAD
    $sql = "USE ".$world.";";
    $db->query($sql);

    $sql = "SELECT TOWN.TOWN_ID FROM TOWN JOIN PLAYERS ON TOWN.PLAYER_ID = PLAYERS.PLAYER_ID WHERE PLAYERS.PLAYER_ID = ".$playerid." AND PLAYERS.USER_ID = ".$userid.";";

    $result=$db->query($sql);
    $result=$result->fetch(PDO::FETCH_ASSOC);
    $townid = $result['TOWN_ID'];

    // Se asignan los edificios base a la ciudad
    $sql = "
    INSERT INTO TOWN_BUILDINGS (TOWN_ID, BUILDING) VALUES (".$townid.",'food_1');
    INSERT INTO TOWN_BUILDINGS (TOWN_ID, BUILDING) VALUES (".$townid.",'wood_1');
    INSERT INTO TOWN_BUILDINGS (TOWN_ID, BUILDING) VALUES (".$townid.",'stone_1');
    INSERT INTO TOWN_BUILDINGS (TOWN_ID, BUILDING) VALUES (".$townid.",'iron_1');

    INSERT INTO TOWN_BUILDINGS (TOWN_ID, BUILDING) VALUES (".$townid.",'townhall_1');
    INSERT INTO TOWN_BUILDINGS (TOWN_ID, BUILDING) VALUES (".$townid.",'storehouse_1');
    INSERT INTO TOWN_BUILDINGS (TOWN_ID, BUILDING) VALUES (".$townid.",'barracks_0');
    INSERT INTO TOWN_BUILDINGS (TOWN_ID, BUILDING) VALUES (".$townid.",'stable_0');
    ";

    $db->query($sql);

}

function showResources($db,$world){

    // Muestra recursos en world.php

    $sql = "USE ".$world.";";
    $db->query($sql);


    $sql = "SELECT TOWN.TOWN_ID FROM TOWN JOIN PLAYERS ON TOWN.PLAYER_ID = PLAYERS.PLAYER_ID WHERE PLAYERS.PLAYER_NAME = '".$_SESSION['USER']."' LIMIT 1;";
    $result=$db->query($sql);
    $result=$result->fetch(PDO::FETCH_ASSOC);
    $townid = $result['TOWN_ID'];

    $sql = "SELECT FOOD, WOOD, STONE, IRON FROM TOWN WHERE TOWN_ID = '".$townid."'";
    $result2=$db->query($sql);
    $result2=$result2->fetch(PDO::FETCH_ASSOC);
    
    echo "<ul>
            <li>
                <img src='../assets/icon/world/food_icon.png'>
                <p>";echo $result2['FOOD'];echo"</p>
            </li>
            <li>
                <img src='../assets/icon/world/wood_icon.png'>
                <p>";echo $result2['WOOD'];echo"</p>
            </li>
            <li>
                <img src='../assets/icon/world/stone_icon.png'>
                <p>";echo $result2['STONE'];echo"</p>
            </li>
            <li>
                <img src='../assets/icon/world/iron_icon.png'>
                <p>";echo $result2['IRON'];echo"</p>
            </li>
        </ul>";
        
    return $townid;
}



?>