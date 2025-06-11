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

     if(isset($_POST['changeName'])){
        CHANGETOWNNAME($db,$WORLD);
    }

     if(isset($_POST['changePass'])){
        CHANGEPASS($db);
    }

     if(isset($_POST['battle'])){
        BATTLE($db,$WORLD,$lang,$armyPOWER);
    }
    if(!empty($_POST['UNITPROD'])){
        UNITPROD($db,$WORLD,$armyCOST,$lang);
    }

    if(!empty($_GET['building'])){
        
        UPGRADEBUILDING($db,$WORLD,$buildingCOST);
    }
    
    LOADRESOURCES($db,$WORLD,$building);

}


function CHANGETOWNNAME($db,$world){
    $sql = "USE ".$world.";";
    $db->query($sql);

    $townID = $_POST['town_id'];
    $newName = $_POST['town_name'];


    $sql = "UPDATE TOWN SET TOWN_NAME = '".$newName."' WHERE TOWN_ID = ".$townID.";";
    $db->query($sql);




}

function CHANGEPASS($db){

    $sql = "USE USERS_DB;";
    $db->query($sql);

    $newPass = $_POST['newPass'];
    
    $oldPass = $_POST['oldPass'];

    if($oldPass == $_SESSION['PASS']){
        $user = $_SESSION['USER'];

        $sql = "UPDATE USERS SET USER_PASSWORD = '".$newPass."' WHERE USER_NAME = '".$user."';";
        $db->query($sql);

        $_SESSION['PASS'] = $newPass;
    } else {
        echo "<script>
            alert('LAS CONTRASEÑA ANTIGUA ES INCORRECTA');
        </script>";
    }
}

function checkWorldStatus($db,$world){

    $sql = "USE USERS_DB;";
    $db->query($sql);

    $sql = "SELECT WORLD_STATUS FROM WORLDSTATUS WHERE WORLD_ID = '".$world."';";
    $result=$db->query($sql);
    $result=$result->fetch(PDO::FETCH_ASSOC);

    if($result['WORLD_STATUS'] == 'NOTRUNNING'){
        echo "<script>
            window.location.href = '../main.php';
        </script>";
    } else {
        return true;
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


function BATTLE($db,$world,$lang,$armyPOWER){

    $sql = "USE ".$world.";";
    $db->query($sql);

    // Ejercito atacante
    $quantities1 = [
    "SWORDMAN" => $_POST['swordman'],
    "PIKEMAN" => $_POST['pikeman'],
    "ARCHER" => $_POST['archer'],
    "L_CAVALRY" => $_POST['l_cavalry'],
    "H_CAVALRY" => $_POST['h_cavalry'],
    ];

    //Defensor
    $objective = $_POST['objective'];

   // Ejercito 1
   $army1 = []; 
    foreach ($quantities1 as $unit => $count) {
        $power = $armyPOWER[$unit][0];
            for ($i = 0; $i < intval($count); $i++) {
                $army1[] = $power;
            }
        
    }

    
    $quantities1PRE = $quantities1;

    

    shuffle($army1);

    // Ejercito 2
    $army2=[]; 
    $sql = "SELECT SWORDMAN,PIKEMAN,ARCHER,L_CAVALRY,H_CAVALRY FROM ARMY WHERE POSITION = '".$objective."';";
    $result=$db->query($sql);
    $result=$result->fetch(PDO::FETCH_ASSOC);

    if(!empty($result)){

    // Ejercito defensor
        $quantities2 = [
        "SWORDMAN" => $result['SWORDMAN'],
        "PIKEMAN" => $result['PIKEMAN'],
        "ARCHER" => $result['ARCHER'],
        "L_CAVALRY" => $result['L_CAVALRY'],
        "H_CAVALRY" => $result['H_CAVALRY'],
        ];

    $quantities2PRE = $quantities2;


        

        foreach ($quantities2 as $unit => $count) {
        $power = $armyPOWER[$unit][0]; 
            for ($i = 0; $i < $count; $i++) {
                $army2[] = $power;
            }
        }
        shuffle($army2);

    


        if(count($army1) > count($army2)){

            $MAXquantity = count($army1); 
        } else {
            $MAXquantity = count($army2); 
        }

        $ATTPOINT = 0;
        $DEFPOINT = 0;

        for($i=0;$i<=$MAXquantity;$i++){
            $unit1 = $army1[$i] ?? 0;
            $unit2 = $army2[$i] ?? 0;

            if($unit1 < $unit2){
                $DEFPOINT++;
                if($unit1 == 2){$quantities1['SWORDMAN']--;}
                if($unit1 == 4){$quantities1['PIKEMAN']--;}
                if($unit1 == 3){$quantities1['ARCHER']--;}
                if($unit1 == 5){$quantities1['L_CAVALRY']--;}
                if($unit1 == 8){$quantities1['H_CAVALRY']--;}

            } else if ($unit1 > $unit2){

                $ATTPOINT++;
                if($unit2 == 2){$quantities2['SWORDMAN']--;}
                if($unit2 == 4){$quantities2['PIKEMAN']--;}
                if($unit2 == 3){$quantities2['ARCHER']--;}
                if($unit2 == 5){$quantities2['L_CAVALRY']--;}
                if($unit2 == 8){$quantities2['H_CAVALRY']--;}
            }

        }

        // Selecciona el id de la ciudad
        $sql = "SELECT TOWN.TOWN_ID FROM TOWN JOIN PLAYERS ON TOWN.PLAYER_ID = PLAYERS.PLAYER_ID WHERE PLAYERS.PLAYER_NAME = '".$_SESSION['USER']."' LIMIT 1;";
        $result2=$db->query($sql);
        $result2=$result2->fetch(PDO::FETCH_ASSOC);
        $townid = $result2['TOWN_ID'];
        
        // Actualizamos el ejercito atacante
        $sql = "UPDATE ARMY SET
        SWORDMAN = '".$quantities1['SWORDMAN']."',
        PIKEMAN = '".$quantities1['PIKEMAN']."',
        ARCHER = '".$quantities1['ARCHER']."',
        L_CAVALRY = '".$quantities1['L_CAVALRY']."',
        H_CAVALRY = '".$quantities1['H_CAVALRY']."'
        WHERE POSITION = '".$townid."';";
        $db->query($sql);

        // Actualizamos el ejercito defensor
        $sql = "UPDATE ARMY SET
        SWORDMAN = '".$quantities2['SWORDMAN']."',
        PIKEMAN = '".$quantities2['PIKEMAN']."',
        ARCHER = '".$quantities2['ARCHER']."',
        L_CAVALRY = '".$quantities2['L_CAVALRY']."',
        H_CAVALRY = '".$quantities2['H_CAVALRY']."'
        WHERE POSITION = '".$objective."';";
        $db->query($sql);

    } else {
        $ATTPOINT = 1;
        $DEFPOINT = 0;

        // Selecciona el id de la ciudad
        $sql = "SELECT TOWN.TOWN_ID FROM TOWN JOIN PLAYERS ON TOWN.PLAYER_ID = PLAYERS.PLAYER_ID WHERE PLAYERS.PLAYER_NAME = '".$_SESSION['USER']."' LIMIT 1;";
        $result2=$db->query($sql);
        $result2=$result2->fetch(PDO::FETCH_ASSOC);
        $townid = $result2['TOWN_ID'];

        $quantities2 = [
        "SWORDMAN" => 0,
        "PIKEMAN" => 0,
        "ARCHER" => 0,
        "L_CAVALRY" => 0,
        "H_CAVALRY" => 0,
        ];

        $quantities2PRE = $quantities2;


    }

     //Creamos un battle_log
    $sql = "SELECT PLAYER_ID FROM PLAYERS WHERE PLAYER_NAME = '".$_SESSION['USER']."';";
    $attID=$db->query($sql);
    $attID=$attID->fetch(PDO::FETCH_ASSOC);

    $sql = "SELECT PLAYER_ID FROM TOWN WHERE TOWN_ID = '".$objective."';";
    $deffID=$db->query($sql);
    $deffID=$deffID->fetch(PDO::FETCH_ASSOC);

   

    if ($ATTPOINT > $DEFPOINT){

        $sql = "SELECT WOOD,FOOD,STONE,IRON FROM TOWN WHERE TOWN_ID = '".$objective."';";
        $result3=$db->query($sql);
        $result3=$result3->fetch(PDO::FETCH_ASSOC);

        $wood = floor($result3['WOOD'] / 10);
        $food = floor($result3['FOOD'] / 10);
        $stone = floor($result3['STONE'] / 10);
        $iron = floor($result3['IRON'] / 10);

        $sql = "UPDATE TOWN SET
        WOOD = WOOD - '".$wood."',
        FOOD = FOOD - '".$food."',
        STONE = STONE - '".$stone."',
        IRON = IRON - '".$iron."'
        WHERE TOWN_ID = '".$objective."';";
        $db->query($sql);

        // Comprobamos que no supere el limite para que no de error
        $sql = "SELECT BUILDING FROM TOWN_BUILDINGS WHERE TOWN_ID = '".$townid."';";
        $result4=$db->query($sql);
        $result4=$result4->fetchALL(PDO::FETCH_ASSOC);

        $sql = "SELECT WOOD,FOOD,STONE,IRON FROM TOWN WHERE TOWN_ID = '".$townid."';";
        $result5=$db->query($sql);
        $result5=$result5->fetch(PDO::FETCH_ASSOC);

        $MAX=500;
        if ($result4[5]["BUILDING"] == "storehouse_2"){$MAX=1000;}
        else if ($result4[5]["BUILDING"] == "storehouse_3"){$MAX=1500;}

        

        $sql = "INSERT INTO BATTLE_LOG (
        ATT_ID, DEF_ID,
        ASWORDMAN, APIKEMAN, AARCHER, AL_CAVALRY, AH_CAVALRY,
        DSWORDMAN, DPIKEMAN, DARCHER, DL_CAVALRY, DH_CAVALRY,
        ASWORDMAN_LOOSES, APIKEMAN_LOOSES, AARCHER_LOOSES, AL_CAVALRY_LOOSES, AH_CAVALRY_LOOSES,
        DSWORDMAN_LOOSES, DPIKEMAN_LOOSES, DARCHER_LOOSES, DL_CAVALRY_LOOSES, DH_CAVALRY_LOOSES,
        WOOD,FOOD,STONE,IRON
        ) VALUES (
        '".$attID['PLAYER_ID']."', '".$deffID['PLAYER_ID']."',
        '".$quantities1PRE['SWORDMAN']."', '".$quantities1PRE['PIKEMAN']."', '".$quantities1PRE['ARCHER']."', '".$quantities1PRE['L_CAVALRY']."', '".$quantities1PRE['H_CAVALRY']."',
        '".$quantities2PRE['SWORDMAN']."', '".$quantities2PRE['PIKEMAN']."', '".$quantities2PRE['ARCHER']."', '".$quantities2PRE['L_CAVALRY']."', '".$quantities2PRE['H_CAVALRY']."',
        '".$quantities1['SWORDMAN']."', '".$quantities1['PIKEMAN']."', '".$quantities1['ARCHER']."', '".$quantities1['L_CAVALRY']."', '".$quantities1['H_CAVALRY']."',
        '".$quantities2['SWORDMAN']."', '".$quantities2['PIKEMAN']."', '".$quantities2['ARCHER']."', '".$quantities2['L_CAVALRY']."', '".$quantities2['H_CAVALRY']."',
        ".$wood.",".$food.",".$stone.",".$iron."
        );";
        $db->query($sql);

        if (($result5['WOOD']+$wood) > $MAX){$wood=$MAX;} else {$wood=$result5['WOOD']+$wood;}
        if (($result5['FOOD']+$food) > $MAX){$food=$MAX;} else {$food=$result5['FOOD']+$food;}
        if (($result5['STONE']+$stone) > $MAX){$stone=$MAX;} else {$stone=$result5['STONE']+$stone;}
        if (($result5['IRON']+$iron) > $MAX){$iron=$MAX;} else {$iron=$result5['IRON']+$iron;}

        $sql = "UPDATE TOWN SET
        WOOD = '".$wood."',
        FOOD = '".$food."',
        STONE = '".$stone."',
        IRON = '".$iron."'
        WHERE TOWN_ID = '".$townid."';";
        $db->query($sql);

    } else {
    $sql = "INSERT INTO BATTLE_LOG (
        ATT_ID, DEF_ID,
        ASWORDMAN, APIKEMAN, AARCHER, AL_CAVALRY, AH_CAVALRY,
        DSWORDMAN, DPIKEMAN, DARCHER, DL_CAVALRY, DH_CAVALRY,
        ASWORDMAN_LOOSES, APIKEMAN_LOOSES, AARCHER_LOOSES, AL_CAVALRY_LOOSES, AH_CAVALRY_LOOSES,
        DSWORDMAN_LOOSES, DPIKEMAN_LOOSES, DARCHER_LOOSES, DL_CAVALRY_LOOSES, DH_CAVALRY_LOOSES,
        WOOD,FOOD,STONE,IRON
        ) VALUES (
        '".$attID['PLAYER_ID']."', '".$deffID['PLAYER_ID']."',
        '".$quantities1PRE['SWORDMAN']."', '".$quantities1PRE['PIKEMAN']."', '".$quantities1PRE['ARCHER']."', '".$quantities1PRE['L_CAVALRY']."', '".$quantities1PRE['H_CAVALRY']."',
        '".$quantities2PRE['SWORDMAN']."', '".$quantities2PRE['PIKEMAN']."', '".$quantities2PRE['ARCHER']."', '".$quantities2PRE['L_CAVALRY']."', '".$quantities2PRE['H_CAVALRY']."',
        '".$quantities1['SWORDMAN']."', '".$quantities1['PIKEMAN']."', '".$quantities1['ARCHER']."', '".$quantities1['L_CAVALRY']."', '".$quantities1['H_CAVALRY']."',
        '".$quantities2['SWORDMAN']."', '".$quantities2['PIKEMAN']."', '".$quantities2['ARCHER']."', '".$quantities2['L_CAVALRY']."', '".$quantities2['H_CAVALRY']."',
        0,0,0,0
        );";
        $db->query($sql);
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

    $sql = "INSERT INTO TOWN (PLAYER_ID, WOOD, FOOD, STONE, IRON,TOWN_NAME) VALUES (".$playerid.",400,400,300,300,'".$_SESSION['USER']."');";
    $db->query($sql);


    $sql = "SELECT TOWN_ID FROM TOWN WHERE PLAYER_ID = '".$playerid."';";
    $result2=$db->query($sql);
    $result2=$result2->fetch(PDO::FETCH_ASSOC);

    $spawn=false;
    do{
        $square = rand(0,($result['WORLD_SIZE']*$result['WORLD_SIZE']));
        $sql = "SELECT TOWN_ID FROM MAP WHERE TOWN_ID = '".$square."';";
        $result3=$db->query($sql);
        $result3=$result3->fetch(PDO::FETCH_ASSOC);

        if (!$result3['TOWN_ID']){

        //Le asignamos cuadro en el mapa
            $sql = "UPDATE MAP SET PLAYER_ID = '".$playerid."', TOWN_ID = '".$result2['TOWN_ID']."' WHERE SQUARE_ID = ".$square.";";
            $result3=$db->query($sql);
            $spawn=true;
        }
    } while ($spawn==false);

    
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

function showResources($db,$world,$building){

    // Muestra recursos en world.php

    $sql = "USE ".$world.";";
    $db->query($sql);


    $sql = "SELECT TOWN.TOWN_ID FROM TOWN JOIN PLAYERS ON TOWN.PLAYER_ID = PLAYERS.PLAYER_ID WHERE PLAYERS.PLAYER_NAME = '".$_SESSION['USER']."' LIMIT 1;";
    $result=$db->query($sql);
    $result=$result->fetch(PDO::FETCH_ASSOC);
    $townid = $result['TOWN_ID'];


    $sql = "SELECT BUILDING 
            FROM TOWN_BUILDINGS 
            JOIN TOWN ON TOWN_BUILDINGS.TOWN_ID = TOWN.TOWN_ID 
            JOIN PLAYERS ON TOWN.PLAYER_ID = PLAYERS.PLAYER_ID 
            WHERE PLAYERS.PLAYER_NAME = '".$_SESSION['USER']."';";

    $result3 = $db->query($sql);
    $result3 = $result3->fetchAll(PDO::FETCH_ASSOC);

    for($i=0;$i<4;$i++){
        $PROD[$i] = $building[$result3[$i]['BUILDING']][1];
    }

    $sql = "SELECT FOOD, WOOD, STONE, IRON FROM TOWN WHERE TOWN_ID = '".$townid."'";
    $result2=$db->query($sql);
    $result2=$result2->fetch(PDO::FETCH_ASSOC);
    
    echo "<ul>
            <li>
                <img src='../assets/icon/world/wood_icon.png'>
                <p>".$result2['WOOD']." - ".$PROD[0]."/H</p>
            </li>
            <li>
                <img src='../assets/icon/world/food_icon.png'>
                <p>".$result2['FOOD']." - ".$PROD[1]."/H</p>
            </li>
            <li>
                <img src='../assets/icon/world/stone_icon.png'>
                <p>".$result2['STONE']." - ".$PROD[2]."/H</p>
            </li>
            <li>
                <img src='../assets/icon/world/iron_icon.png'>
                <p>".$result2['IRON']." - ".$PROD[3]."/H</p>
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
                $result3["STONE"] >= $cost[$NEWbuilding][2] &&
                $result3["IRON"] >= $cost[$NEWbuilding][3] ){

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
        
        

        $sql = "SELECT FOOD, WOOD, STONE, IRON FROM TOWN WHERE TOWN_ID = '".$townid."'";
        $result4=$db->query($sql);
        $result4=$result4->fetch(PDO::FETCH_ASSOC);

        for($i=0;$i<count($building);$i++){
            for($n=0;$n<count($result3);$n++){
                if($buildingKEY[$i] == $result3[$n]['BUILDING'] && $counter < 4){
                    $ammount = $buildingVALUE[$i][1] * $min;

                    //COGE NOMBRE DEL EDIFICIO Y LE TESTA 1
                    $txt = $buildingKEY[$i];
                    $resource = explode('_',$txt);

                    $wood = $result4['WOOD']+$ammount;
                    $food = $result4['FOOD']+$ammount;
                    $stone = $result4['STONE']+$ammount;                    
                    $iron = $result4['IRON']+$ammount;
                    
                    if($resource[0] == 'wood'){
                        if ($result3[5]["BUILDING"] == "storehouse_1" && $wood >= 500){$wood=500;} 
                        else if ($result3[5]["BUILDING"] == "storehouse_2" && $wood >= 1000){$wood=1000;}
                        else if ($result3[5]["BUILDING"] == "storehouse_3" && $wood >= 1500){$wood=1500;}

                            $sql = "UPDATE TOWN SET ".$resource[0]." = ".$wood." WHERE TOWN_ID = '".$townid."';";
                            $db->query($sql);                      

                    } else if ($resource[0] == 'food') {

                        if ($result3[5]["BUILDING"] == "storehouse_1" && ($result4['FOOD'] + $ammount) >= 500){$food=500;}
                        else if ($result3[5]["BUILDING"] == "storehouse_2" && ($result4['FOOD'] + $ammount) >= 1000){$food=1000;}
                        else if ($result3[5]["BUILDING"] == "storehouse_3" && ($result4['FOOD'] + $ammount) >= 1500){$food=1500;}

                            
                            $sql = "UPDATE TOWN SET ".$resource[0]." = ".$food." WHERE TOWN_ID = '".$townid."';";
                            $db->query($sql);
                        

                    } else if($resource[0] == 'stone'){
                    
                    if ($result3[5]["BUILDING"] == "storehouse_1" && ($result4['STONE'] + $ammount) >= 500){$stone=500;}
                    else if ($result3[5]["BUILDING"] == "storehouse_2" && ($result4['STONE'] + $ammount) >= 1000){$stone=1000;}
                    else if ($result3[5]["BUILDING"] == "storehouse_3" && ($result4['STONE'] + $ammount) >= 1500){$stone=1500;}

                            $sql = "UPDATE TOWN SET ".$resource[0]." = ".$stone." WHERE TOWN_ID = '".$townid."';";
                            $db->query($sql);
                    

                } else if ($resource[0] == 'iron'){
                    if ($result3[5]["BUILDING"] == "storehouse_1" && ($result4['IRON'] + $ammount) >= 500){$iron=500;}
                    else if ($result3[5]["BUILDING"] == "storehouse_2" && ($result4['IRON'] + $ammount) >= 1000){$iron=1000;}
                    else if ($result3[5]["BUILDING"] == "storehouse_3" && ($result4['IRON'] + $ammount) >= 1500){$iron=1500;}

                        $sql = "UPDATE TOWN SET ".$resource[0]." = ".$iron." WHERE TOWN_ID = '".$townid."';";
                        $db->query($sql);
   
                    }
                
                    $counter++;
                }
            }

   
        }


    }
}

function UNITPROD($db,$world,$armyCOST,$lang){
    
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
                echo "<script>alert('".$lang['info_3']."')</script>";
            }

        }
    }



}

?>