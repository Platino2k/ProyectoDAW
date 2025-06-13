<?php


$db = DBCON();

//Cerrar Sesion
if (isset($_POST['logout']) || isset($_GET['logout'])) {
    session_destroy();
    header("Location: admin.php");
    exit;
}

if (!empty($_POST['formdata'])) {


    //Iniciar Sesion
    if ($_POST['formdata'] == "LOGIN") {
        LOGIN($db, $lang);
        $check = CHECKUSER($db);
    }

} else {
    //Comprueba cada vez que cargas una pagina el usuario y contraseña por seguridad
    $check = CHECKUSER($db);
}

if ($check == true) {

    if (isset($_GET['delWorld'])) {
        delWorld($db);
        header("Location: admin.php?showList=true");
        exit;
    }

    if (isset($_POST['createWorld_form'])) {
        CREATEWORLD($db);
    }

    if (isset($_POST['changePass'])) {
        CHANGEPASS($db);
    }

    if (isset($_GET['userBAN'])) {
        BANUSER($db);
        header("Location: admin.php?showPlayers=true");
        exit;
    }

    if (isset($_GET['changeStatus'])) {
        CHANGESTATUS($db, $lang);
    }



}

function BANUSER($db)
{

    $sql = "USE USERS_DB;";
    $db->query($sql);

    $user = $_GET['userBAN'];

    $sql = "SELECT BANNED FROM USERS WHERE USER_NAME = '" . $user . "';";
    $result = $db->query($sql);
    $result = $result->fetch(PDO::FETCH_ASSOC);

    if ($result["BANNED"] == 0) {
        $sql = "UPDATE USERS SET BANNED = 1 WHERE USER_NAME = '" . $user . "';";
        $db->query($sql);
    } else {
        $sql = "UPDATE USERS SET BANNED = 0 WHERE USER_NAME = '" . $user . "';";
        $db->query($sql);
    }



}

function CHANGESTATUS($db, $lang)
{
    $sql = "USE USERS_DB;";
    $db->query($sql);

    $world = $_GET['changeStatus'];

    $sql = "SELECT WORLD_STATUS FROM WORLDSTATUS WHERE WORLD_ID = '" . $world . "';";
    $result = $db->query($sql);
    $result = $result->fetch(PDO::FETCH_ASSOC);

    if ($result['WORLD_STATUS'] == 'RUNNING') {
        $sql = "UPDATE WORLDSTATUS SET WORLD_STATUS = 'NOTRUNNING' WHERE WORLD_ID = '" . $world . "';";
        $db->query($sql);
    } else {
        $sql = "UPDATE WORLDSTATUS SET WORLD_STATUS = 'RUNNING' WHERE WORLD_ID = '" . $world . "';";
        $db->query($sql);
    }

}


function CHANGEPASS($db)
{

    $sql = "USE USERS_DB;";
    $db->query($sql);

    $newPass = $_POST['newPass'];

    $oldPass = $_POST['oldPass'];

    if ($oldPass == $_SESSION['PASS']) {
        $user = $_SESSION['USER'];

        $sql = "UPDATE USERS SET USER_PASSWORD = '" . $newPass . "' WHERE USER_NAME = '" . $user . "';";
        $db->query($sql);

        $_SESSION['PASS'] = $newPass;
    } else {
        echo "<script>
            alert('LAS CONTRASEÑA ANTIGUA ES INCORRECTA');
        </script>";
    }
}

function DBCON()
{

    // Establece conexion con la base de datos

    $user = "root";
    $server = "mariadb";
    $password = "rootpassword";

    $db = new PDO("mysql:host=$server", $user, $password);
    return $db;

}

function LOGIN($db, $lang)
{

    $name = $_POST["username"];
    $pass = $_POST["password"];

    $sql = "USE USERS_DB;";
    $db->query($sql);

    $sql = "SELECT USER_NAME,USER_PASSWORD FROM USERS WHERE USER_NAME = '" . $name . "' AND USER_PASSWORD = '" . $pass . "' AND MODERATOR = 1;";
    $result = $db->query($sql);

    $result = $result->fetch(PDO::FETCH_ASSOC);

    //Da mensaje de error por usuario inexistente o contraseña
    if (empty($result)) {
        $sql = "SELECT USER_NAME,USER_PASSWORD FROM USERS WHERE USER_NAME = '" . $name . "' AND MODERATOR = 1;";
        $result2 = $db->query($sql);
        $result2 = $result2->fetch(PDO::FETCH_ASSOC);

        if (empty($result2)) {
            echo "<div class='error'>" . $lang["error_1"] . "</div>";
        } else {
            echo "<div class='error'>" . $lang["error_2"] . "</div>";
        }

    }

    if (!empty($result["USER_NAME"]) && $result["USER_NAME"] == $name && $result["USER_PASSWORD"] == $pass) {

        $_SESSION["USER"] = $_POST["username"];
        $_SESSION["PASS"] = $_POST["password"];

    }


}

function CHECKUSER($db)
{

    // Esta funcion es para incrementar la seguridad
    // Comprueblo que lo que hay en las variables $SESSION USER y PASS sea correcto
    if (!empty($_SESSION["USER"]) && !empty($_SESSION["PASS"])) {
        $sql = "USE USERS_DB;";
        $db->query($sql);

        $sql = "SELECT USER_NAME,USER_PASSWORD FROM USERS WHERE USER_NAME = '" . $_SESSION["USER"] . "' AND USER_PASSWORD = '" . $_SESSION["PASS"] . "';";
        $result = $db->query($sql);

        $result = $result->fetch(PDO::FETCH_ASSOC);

        if (!empty($result["USER_NAME"]) && $result["USER_NAME"] == $_SESSION["USER"] && $result["USER_PASSWORD"] == $_SESSION["PASS"]) {
            return true;
        } else {
            return false;
        }

    } else {
        return false;
    }

}


function CREATEWORLD($db)
{

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
        $db->query("CREATE DATABASE `" . $txt . "`;");



    } else { // Genera el primer mundo

        $txt = "MUNDO1";
        $db->query("CREATE DATABASE `" . $txt . "`;");
    }

    $db->query("USE " . $txt . ";");

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
            WOOD INT NOT NULL,
            FOOD INT NOT NULL,
            STONE INT NOT NULL,
            IRON INT NOT NULL,
            TOWN_NAME VARCHAR (12),
            CONSTRAINT TOWN_PK PRIMARY KEY (TOWN_ID),
            FOREIGN KEY (PLAYER_ID) REFERENCES PLAYERS(PLAYER_ID)
        );

        CREATE TABLE IF NOT EXISTS TOWN_CON(
            TOWN_ID INT NOT NULL,
            LASTCON DATETIME NOT NULL,
            FOREIGN KEY (TOWN_ID) REFERENCES TOWN(TOWN_ID)
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
            LETTER_ID INT AUTO_INCREMENT,
            SENDER_ID INT NOT NULL,
            RECEIVER_ID INT NOT NULL,
            CONTENT VARCHAR (1000),
            TITTLE VARCHAR (30),
            CONSTRAINT LETTER_ID PRIMARY KEY (LETTER_ID),
            FOREIGN KEY (SENDER_ID) REFERENCES PLAYERS(PLAYER_ID),
            FOREIGN KEY (RECEIVER_ID) REFERENCES PLAYERS(PLAYER_ID)
        );

        CREATE TABLE IF NOT EXISTS BATTLE_LOG(
            BATTLE_ID INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
            ATT_ID INT NOT NULL,
            DEF_ID INT NOT NULL,

            ASWORDMAN INT NOT NULL,
            APIKEMAN INT NOT NULL,
            AARCHER INT NOT NULL,
            AL_CAVALRY INT NOT NULL,
            AH_CAVALRY INT NOT NULL,
            
            DSWORDMAN INT NOT NULL,
            DPIKEMAN INT NOT NULL,
            DARCHER INT NOT NULL,
            DL_CAVALRY INT NOT NULL,
            DH_CAVALRY INT NOT NULL,

            ASWORDMAN_LOOSES INT NOT NULL,
            APIKEMAN_LOOSES INT NOT NULL,
            AARCHER_LOOSES INT NOT NULL,
            AL_CAVALRY_LOOSES INT NOT NULL,
            AH_CAVALRY_LOOSES INT NOT NULL,

            DSWORDMAN_LOOSES INT NOT NULL,
            DPIKEMAN_LOOSES INT NOT NULL,
            DARCHER_LOOSES INT NOT NULL,
            DL_CAVALRY_LOOSES INT NOT NULL,
            DH_CAVALRY_LOOSES INT NOT NULL,

            WOOD INT NOT NULL,
            FOOD INT NOT NULL,
            STONE INT NOT NULL,
            IRON INT NOT NULL,

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

    //CREO EL MAPA
    $counter = 0;
    for ($i = 0; $i < $mapsize; $i++) {

        for ($n = 0; $n < $mapsize; $n++) {
            $db->query("use " . $txt . ";");
            $db->query("INSERT INTO MAP (SQUARE_ID,POS_X,POS_Y) VALUES (" . $counter . "," . $i . "," . $n . ");");
            $counter++;
        }

    }


    $db = null; // Cierro la conexion con la base de datos
}

function WORLDLIST($db)
{

    // Entro en USERS_DB
    $sql = "USE USERS_DB;";
    $db->query($sql);

    if (isset($_POST['order'])) {
        $order = $_POST['order'];
    } else {
        $order = "ASC";
    }


    if ($_POST['filterName']) {
        if (isset($_POST['filter']) && $_POST['filter'] == 'ALL') {
            $sql = "select WORLD_NAME, WORLD_ID, WORLD_STATUS from WORLDSTATUS WHERE WORLD_NAME = '" . $_POST['filterName'] . "' ORDER BY WORLD_ID " . $order . ";";
        } else if (isset($_POST['filter'])) {
            $sql = "select WORLD_NAME, WORLD_ID, WORLD_STATUS from WORLDSTATUS WHERE WORLD_STATUS = '" . $_POST['filter'] . "' AND WORLD_NAME = '" . $_POST['filterName'] . "' ORDER BY WORLD_ID " . $order . ";";
        } else {
            $sql = "select WORLD_NAME, WORLD_ID, WORLD_STATUS from WORLDSTATUS;";
        }

    } else {

        if (isset($_POST['filter']) && $_POST['filter'] == 'ALL') {

            $sql = "select WORLD_NAME, WORLD_ID, WORLD_STATUS from WORLDSTATUS ORDER BY WORLD_ID " . $order . ";";
        } else if (isset($_POST['filter'])) {
            $sql = "select WORLD_NAME, WORLD_ID, WORLD_STATUS from WORLDSTATUS WHERE WORLD_STATUS = '" . $_POST['filter'] . "' ORDER BY WORLD_ID " . $order . ";";
        } else {
            $sql = "select WORLD_NAME, WORLD_ID, WORLD_STATUS from WORLDSTATUS;";
        }
    }
    $result = $db->query($sql);
    $WORLD = $result->fetchALL();


    foreach ($WORLD as $DATA) {
        echo "<tr>
            <td>$DATA[WORLD_NAME]</td>
            <td>$DATA[WORLD_ID]</td>
            <td>$DATA[WORLD_STATUS]</td>
            <td style='border: none; text-align: left; padding-left: 10px; width: 20px;'>
                <a href='admin.php?changeStatus=$DATA[WORLD_ID]&showList=true&filter1=1'><img src='assets/icon/serverStatus.webp' /></a>
            </td>
            <td style='border: none; text-align: left; padding-left: 10px; width: 20px;'>
                <a href='admin.php?delWorld=$DATA[WORLD_ID]&showList=true&filter1=1'><img src='assets/icon/trashcan.png' /></a>
            </td>
            </tr>";
    }
    return true;
}

function delWorld($db)
{
    // Borra el mundo seleccionado

    $sql = "drop database " . $_GET['delWorld'] . ";";
    $db->query($sql);

    $sql = "use USERS_DB;";
    $db->query($sql);

    $sql = "delete from WORLDSTATUS where WORLD_ID = '" . $_GET['delWorld'] . "';";
    $db->query($sql);

}

function PLAYERLIST($db)
{

    // Entro en USERS_DB
    $sql = "USE USERS_DB;";
    $db->query($sql);

    if (isset($_POST['order'])) {
        $order = $_POST['order'];
    } else {
        $order = "ASC";
    }


    if ($_POST['filterName']) {

        if ($_POST['filter'] == "MODERATOR") {
            $sql = "select * from USERS WHERE USER_NAME = '" . $_POST['filterName'] . "' AND MOD = '1' ORDER BY USER_ID " . $order . ";";

        } else if ($_POST['filter'] == "BANNED") {
            $sql = "select * from USERS WHERE USER_NAME = '" . $_POST['filterName'] . "' AND BANNED = '1' ORDER BY USER_ID " . $order . ";";

        } else {
            $sql = "select * from USERS WHERE USER_NAME = '" . $_POST['filterName'] . "' ORDER BY USER_ID " . $order . ";";
        }

    } else {


        if ($_POST['filter'] == "MODERATOR") {
            $sql = "select * from USERS WHERE MODERATOR = '1' ORDER BY USER_ID " . $order . ";";

        } else if ($_POST['filter'] == "BANNED") {
            $sql = "select * from USERS WHERE BANNED = '1' ORDER BY USER_ID " . $order . ";";

        } else {
            $sql = "select * from USERS ORDER BY USER_ID " . $order . ";;";
        }

    }
    $result = $db->query($sql);
    $WORLD = $result->fetchALL();

    foreach ($WORLD as $DATA) {
        echo "<tr>
            <td>$DATA[USER_ID]</td>
            <td>$DATA[USER_NAME]</td>
            <td>$DATA[EMAIL]</td>
            <td>$DATA[BIRTH_DATE]</td>
            <td>$DATA[MODERATOR]</td>
            <td>$DATA[BANNED]</td>
            <td style='width:50px;border:none'><a href='admin.php?filter2=ban&userBAN=" . $DATA['USER_NAME'] . "'><img id='" . $DATA['USER_NAME'] . "' src='../assets/icon/ban.png' width='30' height='30'></a></td>
            </tr>";



    }
    return true;
}

?>