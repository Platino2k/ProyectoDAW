<?php

//Cerrar Sesion
if (isset($_POST['logout']) || isset($_GET['logout'])) {
    session_destroy();
    header("Location: index.php");
    exit;
}

if (isset($_POST['changePass'])) {
    CHANGEPASS($db);
}

$db = DBCON();

function DBCON()
{

    // Establece conexion con la base de datos

    $user = "root";
    $server = "mariadb";
    $password = "rootpassword";

    $db = new PDO("mysql:host=$server", $user, $password);
    return $db;

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
            header("Location: index.php");
        }

    } else {
        header("Location: index.php");
    }

}

function showWorlds($db, $lang)
{
    include "config.php";
    // Muestra la lista de mundos en main.php

    $sql = "USE USERS_DB;";
    $db->query($sql);

    $sql = "select * from WORLDSTATUS WHERE WORLD_STATUS = 'RUNNING';";
    $result = $db->query($sql);

    $WORLD = $result->fetchALL();

    // Indica en que pagina esta y enseña segun la pagina
    if (isset($_GET["WL"])) {

        $page = $_GET["WL"] * 6;
    } else {
        $page = 1 * 6;
    }

    $counter = 0;

    foreach ($WORLD as $DATA) {
        $sql = "USE " . $DATA['WORLD_ID'] . ";";
        $db->query($sql);

        $sql = "SELECT COUNT(*) AS PLAYERS FROM PLAYERS";
        $result2 = $db->query($sql);
        $result2 = $result2->fetch(PDO::FETCH_ASSOC);
        if ($counter < $page && $counter >= ($page - 6)) {
            echo "<div class='worldBox'>
                    <div class='separator'>
                        <h3>$DATA[WORLD_NAME]</h3>
                        <h4>" . $lang['worldselector_2'] . ": " . $result2['PLAYERS'] . "</h4>
                    </div>
                    <a href='world-pages/world.php?world=$DATA[WORLD_ID]'>" . $lang['worldselector_3'] . "</a>
                    
                </div>";


        }
        $counter++;
    }

    // Muestra la cantidad de paginas
    $pages = ceil($counter / 6);

    echo "</div>";
    echo "<div class='pageList'>";

    for ($i = 1; $i < $pages + 1; $i++) {

        echo "<a class='page' href='main.php?WL=" . $i . "'>" . ($i) . "</a>";

    }


}



?>