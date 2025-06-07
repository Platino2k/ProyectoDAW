<?php

//Cerrar Sesion
if (isset($_POST['logout']) || isset($_GET['logout'])) {
    session_destroy();
    header("Location: index.php");
    exit;
}

$db = DBCON();

if (empty($_GET["world"])){
    $WORLD = $_POST["world"];
} else {

$WORLD = $_GET["world"];
}

$check = CHECKUSER($db);
if ($check == true){
    ADDPLAYER($db,$WORLD);
    LOADRESOURCES($db,$WORLD,$building);
    
    if(!empty($_POST['UNITPROD'])){
        UNITPROD($db,$WORLD,$armyCOST);
    }

    if(!empty($_GET['building'])){
        
        UPGRADEBUILDING($db,$WORLD,$buildingCOST);
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
    
   

    // Miramos el tamaño del mapa
     $sql = "USE USERS_DB;";
    $db->query($sql);
    $sql = "SELECT WORLD_SIZE FROM WORLDSTATUS WHERE WORLD_ID ='".$world."';";
    $result=$db->query($sql);
    $result=$result->fetch(PDO::FETCH_ASSOC);

    // CREA UNA CIUDAD
     $sql = "USE ".$world.";";
    $db->query($sql);

    $sql = "INSERT INTO TOWN (PLAYER_ID, WOOD, FOOD, STONE, IRON) VALUES (".$playerid.",400,400,300,300);";
    $db->query($sql);


    $sql = "SELECT TOWN_ID FROM TOWN WHERE PLAYER_ID = '".$playerid."';";
    $result2=$db->query($sql);
    $result2=$result2->fetch(PDO::FETCH_ASSOC);

    $square = rand(0,($result['WORLD_SIZE']*$result['WORLD_SIZE']));
    $sql = "SELECT TOWN_ID FROM MAP WHERE TOWN_ID = '".$square."';";
    $result3=$db->query($sql);
    $result3=$result3->fetch(PDO::FETCH_ASSOC);

    if (!$result3['TOWN_ID']){

    //Le asignamos cuadro en el mapa
        $sql = "UPDATE MAP SET PLAYER_ID = '".$playerid."', TOWN_ID = '".$result2['TOWN_ID']."' WHERE SQUARE_ID = ".$square.";";
        $result3=$db->query($sql);
    }


    
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
                <img src='../assets/icon/world/wood_icon.png'>
                <p>";echo $result2['FOOD'];echo"</p>
            </li>
            <li>
                <img src='../assets/icon/world/food_icon.png'>
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

function UPGRADEBUILDING($db,$world,$cost){

    $sql = "USE ".$world.";";
    $db->query($sql);


    $sql = "SELECT TOWN.TOWN_ID FROM TOWN JOIN PLAYERS ON TOWN.PLAYER_ID = PLAYERS.PLAYER_ID WHERE PLAYERS.PLAYER_NAME = '".$_SESSION['USER']."' LIMIT 1;";
    $result=$db->query($sql);
    $result=$result->fetch(PDO::FETCH_ASSOC);
    $townid = $result['TOWN_ID'];

  

    //COGE NOMBRE DEL EDIFICIO Y LE TESTA 1
        $txt = $_GET['building'];
        $split = explode('_',$txt);
        $NEWbuilding = $split[0]."_".($split[1]);
        $PREbuilding = $split[0]."_".($split[1]-1);


         // selecciona los recursos de la ciudad
    $sql = "SELECT FOOD, WOOD, STONE, IRON FROM TOWN WHERE TOWN_ID = '".$townid."'";
    $result3=$db->query($sql);
    $result3=$result3->fetch(PDO::FETCH_ASSOC);
    //Resta Recursos
    $sql = "SELECT BUILDING FROM TOWN_BUILDINGS WHERE TOWN_ID = '".$townid."' AND BUILDING = '".$NEWbuilding."';";
    $result4=$db->query($sql);
    $result4=$result4->fetch(PDO::FETCH_ASSOC);

        if (!$result4){

            if ($result3["FOOD"] >= $cost[$NEWbuilding][1] &&
                $result3["WOOD"] >= $cost[$NEWbuilding][0] &&
                $result3["STONE"] >= $cost[$NEWbuilding][3] &&
                $result3["IRON"] >= $cost[$NEWbuilding][4] ){

                    $sql = "UPDATE TOWN SET 
                    FOOD =  FOOD -".$cost[$NEWbuilding][1].",
                    WOOD =  WOOD -".$cost[$NEWbuilding][0].",
                    STONE =  STONE -".$cost[$NEWbuilding][2].",
                    IRON =  IRON -".$cost[$NEWbuilding][3]."
                    WHERE TOWN_ID = '".$townid."';";
                    $db->query($sql);

                    // AUMENTA NIVEL
                    $sql = "UPDATE TOWN_BUILDINGS
                    SET BUILDING = '".$_GET['building']."'
                    WHERE TOWN_ID = '".$townid."' AND BUILDING = '".$PREbuilding."';
                    ";
                    
                    $db->query($sql);
                }
        } 
    
}


function LOADRESOURCES($db,$world,$building){

    $sql = "USE ".$world.";";
    $db->query($sql);

    // Selecciona el id de la ciudad
    $sql = "SELECT TOWN.TOWN_ID FROM TOWN JOIN PLAYERS ON TOWN.PLAYER_ID = PLAYERS.PLAYER_ID WHERE PLAYERS.PLAYER_NAME = '".$_SESSION['USER']."' LIMIT 1;";
    $result=$db->query($sql);
    $result=$result->fetch(PDO::FETCH_ASSOC);
    $townid = $result['TOWN_ID'];

    $sql = "SELECT LASTCON FROM TOWN_CON WHERE TOWN_ID = '".$townid."';";
    $result2=$db->query($sql);
    $result2=$result2->fetch(PDO::FETCH_ASSOC);
    $date = new DateTimeImmutable();
    
       
    // $date es un objeto y hay que pasarlo a string
    $dateString = $date->format('Y-m-d H:i:s');

    if (empty($result2)){
        // Pongo valores base
        $sql = "INSERT INTO TOWN_CON (TOWN_ID,LASTCON) VALUES ('".$townid."','".$dateString."');";
        $db->query($sql);
    } else {
        
        $lastcon = new DateTimeImmutable($result2['LASTCON']);
        $mindiff = $lastcon->diff($date);

        $min = ($mindiff->days * 24 * 60) + ($mindiff->h * 60) + $mindiff->i;

        //Guardo nueva ultima conexion

        $sql = "UPDATE TOWN_CON SET LASTCON = '".$dateString."' WHERE TOWN_ID = '".$townid."';";
        $db->query($sql);

        //Se calculan recursos generados

        $sql = "select BUILDING from TOWN_BUILDINGS WHERE TOWN_ID = '".$townid."'";
        $result3=$db->query($sql);
        $result3=$result3->fetchALL(PDO::FETCH_ASSOC);


        $buildingKEY = array_keys($building); 
        $buildingVALUE = array_values($building);
        $counter = 0;
        for($i=0;$i<count($building);$i++){
            for($n=0;$n<count($result3);$n++){
                if($buildingKEY[$i] == $result3[$n]['BUILDING'] && $counter < 4){
                    $ammount = $buildingVALUE[$i][1] * $min;

                    //COGE NOMBRE DEL EDIFICIO Y LE TESTA 1
                    $txt = $buildingKEY[$i];
                    $resource = explode('_',$txt);

                    $sql = "UPDATE TOWN SET ".$resource[0]." = ".$resource[0]." + ".$ammount." WHERE TOWN_ID = '".$townid."';";
                    $db->query($sql);

                    $counter++;
                    
                }
            }
        }

   
    }



}

function UNITPROD($db,$world,$armyCOST){
    
    $sql = "USE ".$world.";";
    $db->query($sql);

    $unit = $_POST['unit'];
    $armyKEY = array_keys($armyCOST); 
    $armyVALUE = $armyCOST[$unit];

    $sql = "SELECT TOWN.TOWN_ID, TOWN.PLAYER_ID FROM TOWN JOIN PLAYERS ON TOWN.PLAYER_ID = PLAYERS.PLAYER_ID WHERE PLAYERS.PLAYER_NAME = '".$_SESSION['USER']."' LIMIT 1;";
    $result=$db->query($sql);
    $result=$result->fetch(PDO::FETCH_ASSOC);
    $townid = $result['TOWN_ID'];
    
    $playerid = $result['PLAYER_ID'];

    
    $sql = "SELECT WOOD,FOOD,STONE,IRON FROM TOWN WHERE TOWN_ID = '".$townid."';";
    $result2=$db->query($sql);
    $result2=$result2->fetch(PDO::FETCH_ASSOC);


    for($i=0;$i<count($armyCOST);$i++){
        if ($armyKEY[$i] == $unit && $unit > 0){
            $cuantity = $_POST['cuantity'];

            $woodCOST = $armyVALUE[0] * $cuantity;
            $foodCOST = $armyVALUE[1] * $cuantity;
            $stoneCOST = $armyVALUE[2] * $cuantity;
            $ironCOST = $armyVALUE[3] * $cuantity;

            if ($result2['WOOD'] > $woodCOST && $result2['FOOD'] > $foodCOST && $result2['STONE'] > $stoneCOST && $result2['IRON'] > $ironCOST){

                $sql = "UPDATE TOWN SET 
                    FOOD =  FOOD -".$woodCOST.",
                    WOOD =  WOOD -".$foodCOST.",
                    STONE =  STONE -".$stoneCOST.",
                    IRON =  IRON -".$ironCOST."
                    WHERE TOWN_ID = '".$townid."';";
                $db->query($sql);

                $sql = "SELECT * FROM ARMY WHERE POSITION = '".$townid."';";
                $result3=$db->query($sql);
                $result3=$result3->fetch(PDO::FETCH_ASSOC);
                if(!$result3){
                    
                    $sql = "INSERT INTO ARMY (PLAYER_ID, POSITION,SWORDMAN,PIKEMAN,ARCHER,L_CAVALRY,H_CAVALRY)
                    VALUES (".$playerid.",".$townid.",0,0,0,0,0)"; 
                    $db->query($sql);
                }
                $sql = "UPDATE ARMY SET ".$unit." = ".$unit." + ".$cuantity." WHERE POSITION = ".$townid.";"; 
                    $db->query($sql);

            } else {
                echo "NO HAY RECURSOS!";
            }

        }
    }



}

?>