<?php
if ($check == true){
    if(isset($_POST['sendMSG'])){
            SENDMSG($db,$WORLD,$lang);
    }
    LOADTOWN($db,$WORLD,$lang,$buildingCOST,$armyCOST);

}



function LOADTOWN($db,$world,$lang,$buildingCOST,$armyCOST){
    // Carga la ciudad

    $sql = "USE ".$world.";";
    $db->query($sql);

    // Selecciona el id de la ciudad
    $sql = "SELECT TOWN.TOWN_ID FROM TOWN JOIN PLAYERS ON TOWN.PLAYER_ID = PLAYERS.PLAYER_ID WHERE PLAYERS.PLAYER_NAME = '".$_SESSION['USER']."' LIMIT 1;";
    $result=$db->query($sql);
    $result=$result->fetch(PDO::FETCH_ASSOC);
    $townid = $result['TOWN_ID'];

    // recoge los edificios
    $sql = "SELECT * FROM TOWN_BUILDINGS WHERE TOWN_ID = '".$townid."';";
    $result2=$db->query($sql);
    $result2=$result2->fetchAll(PDO::FETCH_ASSOC);


    $level = [];
    $i=0;
    $nextBuilding=[];

    // esto es para poner el nombre del siguiente nivel
    foreach($result2 as $building){
        $txt = $building['BUILDING'];
        $split = explode('_',$txt);
        $level[$i] = $split[1];
        $nextBuilding[$i] = $split[0]."_".($split[1]+1);
        $i++;
    }
    // selecciona los recursos de la ciudad
    $sql = "SELECT FOOD, WOOD, STONE, IRON FROM TOWN WHERE TOWN_ID = '".$townid."'";
    $result3=$db->query($sql);
    $result3=$result3->fetch(PDO::FETCH_ASSOC);
    
    // Recursos

        echo "<div class='window' id='resourcesCITY' style='display: none;'>";
    for($i=0;$i<4;$i++){

        if($i==0){$resource = "food";}
        if($i==1){$resource = "wood";}
        if($i==2){$resource = "stone";}
        if($i==3){$resource = "iron";}


        echo "<div id='".$resource."'>
            <p>";echo $lang['resources_'.($i+1)];echo"</p>
            <img src='../assets/images/".$resource.".jpg' width='50px' height='50px'>";

            if(!empty($buildingCOST[$nextBuilding[$i]])){

            echo "<p>".$lang['info_4']." ";echo $level[$i];echo"</p>";
             echo "<ul>
            <p><img src='../assets/icon/world/food_icon.png' width='15px' height='15px'>";
            echo $buildingCOST[$nextBuilding[$i]][0].
            " </p><p><img src='../assets/icon/world/wood_icon.png' width='15px' height='15px'>"
            .$buildingCOST[$nextBuilding[$i]][1].
            " </p><p><img src='../assets/icon/world/stone_icon.png' width='15px' height='15px'>"
            .$buildingCOST[$nextBuilding[$i]][2].
            " </p><p><img src='../assets/icon/world/iron_icon.png' width='15px' height='15px'>"
            .$buildingCOST[$nextBuilding[$i]][3];
            echo "</p></ul>";

            if ($result3["FOOD"] >= $buildingCOST[$nextBuilding[$i]][0] &&
                $result3["WOOD"] >= $buildingCOST[$nextBuilding[$i]][1] &&
                $result3["STONE"] >= $buildingCOST[$nextBuilding[$i]][2] &&
                $result3["IRON"] >= $buildingCOST[$nextBuilding[$i]][3] ){
                echo "<button id='resButton' onclick='window.location.href=`world.php?world=".$world."&building=".$nextBuilding[$i]."`;'>".$lang['info_5']."</button>";
            } else {
                echo "<button id='resButton' onclick='window.location.href=`world.php?world=".$world."&building=".$nextBuilding[$i]."`;' disabled>".$lang['info_5']."</button>";
            }
        } else {
            echo "<h4>".$lang['city_5']."</h4>";
        }
        echo "</div>";
    }
    echo "</div>";

    // Ciudad

    echo "<div class='window' id='cityCITY' style='display: none;'>";
    for($i=4;$i<8;$i++){

        if($i==4){$resource = "townhall";}
        if($i==5){$resource = "storehouse";}
        if($i==6){$resource = "barracks";}
        if($i==7){$resource = "stable";}


        echo "<div id='".$resource."'>
            <p>";echo $lang['city_'.($i+-3)];echo"</p>
            <img src='../assets/images/".$resource.".jpg' width='50px' height='50px'>";

            if(!empty($buildingCOST[$nextBuilding[$i]])){

            echo "<p>".$lang['info_4']." ";echo $level[$i];echo"</p>";
            echo "<ul>
            <p><img src='../assets/icon/world/food_icon.png' width='15px' height='15px'>";
            echo $buildingCOST[$nextBuilding[$i]][0].
            " </p><p><img src='../assets/icon/world/wood_icon.png' width='15px' height='15px'>"
            .$buildingCOST[$nextBuilding[$i]][1].
            " </p><p><img src='../assets/icon/world/stone_icon.png' width='15px' height='15px'>"
            .$buildingCOST[$nextBuilding[$i]][2].
            " </p><p><img src='../assets/icon/world/iron_icon.png' width='15px' height='15px'>"
            .$buildingCOST[$nextBuilding[$i]][3];
            echo "</p></ul>";

            

            if ($result3["FOOD"] >= $buildingCOST[$nextBuilding[$i]][0] &&
                $result3["WOOD"] >= $buildingCOST[$nextBuilding[$i]][1] &&
                $result3["STONE"] >= $buildingCOST[$nextBuilding[$i]][2] &&
                $result3["IRON"] >= $buildingCOST[$nextBuilding[$i]][3] ){
                echo "<button id='resButton' onclick='window.location.href=`world.php?world=".$world."&building=".$nextBuilding[$i]."`;'>".$lang['info_5']."</button>";
            } else {
                echo "<button id='resButton' onclick='window.location.href=`world.php?world=".$world."&building=".$nextBuilding[$i]."`;' disabled>".$lang['info_5']."</button>";
            }
        } else {
            echo "<h4>".$lang['city_5']."</h4>";
        }
        echo "</div>";
    }
echo "</div>";


    // Cuartel Reclutamiento

    echo "<div class='window' id='barrackCITY' style='display: none;'>";

    $sql = "SELECT * FROM TOWN_BUILDINGS WHERE TOWN_ID = '".$townid."';";
    $milbuilding=$db->query($sql);
    $milbuilding=$milbuilding->fetchALL(PDO::FETCH_ASSOC);
    



            echo "
        <div id='SWORDMAN'>
            <p>";echo $lang['barrack_1'];echo"</p>
            
            <img src='../assets/images/swordman.jpg' width='60px' height='60px'>
            <ul>
            <p><img src='../assets/icon/world/food_icon.png' width='15px' height='15px'>";
            echo $armyCOST['SWORDMAN'][0].
            " </p><p><img src='../assets/icon/world/wood_icon.png' width='15px' height='15px'>"
            .$armyCOST['SWORDMAN'][1].
            " </p><p><img src='../assets/icon/world/stone_icon.png' width='15px' height='15px'>"
            .$armyCOST['SWORDMAN'][2].
            " </p><p><img src='../assets/icon/world/iron_icon.png' width='15px' height='15px'>"
            .$armyCOST['SWORDMAN'][3];
            echo "</p></ul>";
           
            if ($milbuilding[6]['BUILDING'] == "barracks_1" ){
                 echo "<form method='POST' action='world.php'>";
            echo "<input type='hidden' name='world' value='".$world."'>";
            echo "<input type='hidden' name='unit' value='SWORDMAN'>";
            
            echo "<input type='number' name='cuantity'>";
                echo "<input id='submit' type='submit' name='UNITPROD' value='".$lang['info_6']."'>";
            } else {
                echo "<p>".$lang['barrack_4']."</p>";
            }
             echo "</form>";
            echo "</div>

        <div id='PIKEMAN'>
            <p>";echo $lang['barrack_2'];echo"</p>

            <img src='../assets/images/pikeman.jpg' width='60px' height='60px'>
            <ul>
            <p><img src='../assets/icon/world/food_icon.png' width='15px' height='15px'>";
            echo $armyCOST['PIKEMAN'][0].
            " </p><p><img src='../assets/icon/world/wood_icon.png' width='15px' height='15px'>"
            .$armyCOST['PIKEMAN'][1].
            " </p><p><img src='../assets/icon/world/stone_icon.png' width='15px' height='15px'>"
            .$armyCOST['PIKEMAN'][2].
            " </p><p><img src='../assets/icon/world/iron_icon.png' width='15px' height='15px'>"
            .$armyCOST['PIKEMAN'][3];
            echo "</p></ul>";
            
            if ($milbuilding[6]['BUILDING'] == "barracks_1" ){
                echo "<form method='POST' action='world.php'>";
            echo "<input type='hidden' name='world' value='".$world."'>";
            echo "<input type='hidden' name='unit' value='PIKEMAN'>";
            
            echo "<input type='number' name='cuantity'>";
                echo "<input type='submit' name='UNITPROD' value='".$lang['info_6']."'>";
            } else {
                echo "<p>".$lang['barrack_4']."</p>";
            }
             echo "</form>";
            echo "</div>

        <div id='ARCHER'>
            <p>";echo $lang['barrack_3'];echo"</p>

            <img src='../assets/images/archer.jpg' width='60px' height='60px'>
            <ul>
            <p><img src='../assets/icon/world/food_icon.png' width='15px' height='15px'>";
            echo $armyCOST['ARCHER'][0].
            " </p><p><img src='../assets/icon/world/wood_icon.png' width='15px' height='15px'>"
            .$armyCOST['ARCHER'][1].
            " </p><p><img src='../assets/icon/world/stone_icon.png' width='15px' height='15px'>"
            .$armyCOST['ARCHER'][2].
            " </p><p><img src='../assets/icon/world/iron_icon.png' width='15px' height='15px'>"
            .$armyCOST['ARCHER'][3];

            echo "</p></ul>";
            if ($milbuilding[6]['BUILDING'] == "barracks_1" ){
                echo "<form method='POST' action='world.php'>";
            echo "<input type='hidden' name='world' value='".$world."'>";
            echo "<input type='hidden' name='unit' value='ARCHER'>";
            
            echo "<input type='number' name='cuantity'>";
                echo "<input type='submit' name='UNITPROD' value='".$lang['info_6']."'>";
            } else {
                echo "<p>".$lang['barrack_4']."</p>";
            }
             echo "</form>";
            echo "</div>

    </div>
    ";

     // Establo Reclutamiento

    echo "<div class='window' id='stableCITY' style='display: none;'>

        <div id='L-CAVALRY'>
            <p>";echo $lang['stable_1'];echo"</p>

            <img src='../assets/images/lightcab.png' width='60px' height='60px'>
            <ul>
            <p><img src='../assets/icon/world/food_icon.png' width='15px' height='15px'>";
            echo $armyCOST['L_CAVALRY'][0].
            " </p><p><img src='../assets/icon/world/wood_icon.png' width='15px' height='15px'>"
            .$armyCOST['L_CAVALRY'][1].
            " </p><p><img src='../assets/icon/world/stone_icon.png' width='15px' height='15px'>"
            .$armyCOST['L_CAVALRY'][2].
            " </p><p><img src='../assets/icon/world/iron_icon.png' width='15px' height='15px'>"
            .$armyCOST['L_CAVALRY'][3];

            echo "</p></ul>";
            if ($milbuilding[7]['BUILDING'] == "stable_1" ){
                echo "<form method='POST' action='world.php'>";
            echo "<input type='hidden' name='world' value='".$world."'>";
            echo "<input type='hidden' name='unit' value='L_CAVALRY'>";
            
            echo "<input type='number' name='cuantity'>";
                echo "<input type='submit' name='UNITPROD' value='".$lang['info_6']."'>";
            } else {
                echo "<p>".$lang['stable_3']."</p>";
            }
            
            echo "</form>";
            echo "</div>

        <div id='H-CAVALRY'>
            <p>";echo $lang['stable_2'];echo"</p>

            <img src='../assets/images/heavycab.png' width='60px' height='60px'>
            <ul>
            <p><img src='../assets/icon/world/food_icon.png' width='15px' height='15px'>";
            echo $armyCOST['H_CAVALRY'][0].
            " </p><p><img src='../assets/icon/world/wood_icon.png' width='15px' height='15px'>"
            .$armyCOST['H_CAVALRY'][1].
            " </p><p><img src='../assets/icon/world/stone_icon.png' width='15px' height='15px'>"
            .$armyCOST['H_CAVALRY'][2].
            " </p><p><img src='../assets/icon/world/iron_icon.png' width='15px' height='15px'>"
            .$armyCOST['H_CAVALRY'][3];

            echo "</p></ul>";
            if ($milbuilding[7]['BUILDING'] == "stable_1" ){
                 echo "<form method='POST' action='world.php'>";
            echo "<input type='hidden' name='world' value='".$world."'>";
            echo "<input type='hidden' name='unit' value='H_CAVALRY'>";
            
            echo "<input type='number' name='cuantity'>";
                echo "<input type='submit' name='UNITPROD' value='".$lang['info_6']."'>";
            } else {
                echo "<p>".$lang['stable_3']."</p>";
            }
            echo "</form>";
            echo "</div>


    </div>
    ";


     echo "<div class='windowINFO' id='infoCITY' style='display: block;'>

        <div id='army'>
            <h4>".$lang['info_1']."</h4>";
            ARMYLIST($db,$world,$townid,$lang);
            echo"
        </div>
    
    </div>
    ";

     echo "<div class='windowMAP' id='mapCITY' style='display: none;'>";


    SHOWMAP($db,$world,$lang);   


    echo "</div>";

    //Formulario enviar MSG
     echo "<div class='formMSG' id='formMSG' style='display: none;'>
    <button id='closeMSG'>X</button><br>
            <form method='post'>
            <h3>".$lang['msg_7']."</h3>
                <label>".$lang['msg_8'].":</label>
                <input type='text' name='receiver'>
                <label>".$lang['msg_9'].":</label>
                <input type='text' name='tittle' maxlength='30'><br><br>
                
                <label>".$lang['msg_10'].":</label><br>
                <input type='textarea' id='textarea' name='content' maxlength='1000'><br>
                <input type='submit' id='submit' name='sendMSG' value='".$lang['msg_11']."'>


            </form>


     </div>";

    //Mostrar mensajes
    echo "<div class='windowMSG' id='msgCITY' style='display: none;'>";

    echo "<p>".$lang['msg_1']."</p>
    <button id='sendMSG'>".$lang['msg_2']."</button>
    
    <script>

        sendMSG = document.getElementById('sendMSG');
        formMSG = document.getElementById('formMSG');
        closeMSG = document.getElementById('closeMSG');

    sendMSG.addEventListener('click', () => {
        formMSG.style.display='block';
    }) 

    closeMSG.addEventListener('click', () => {
        formMSG.style.display='none';
    }) 


    </script>

    <hr>";


    SHOWMSG($db,$world,$lang);


    echo "</div>";

    // Muestra batallas
    echo "<div class='windowBATTLE' id='battleCITY' style='display: none;'>";

    echo "<p>".$lang['battleinf_1']."</p>
    
    <script>

        sendMSG = document.getElementById('sendMSG');
        formMSG = document.getElementById('formMSG');
        closeMSG = document.getElementById('closeMSG');

    sendMSG.addEventListener('click', () => {
        formMSG.style.display='block';
    }) 

    closeMSG.addEventListener('click', () => {
        formMSG.style.display='none';
    }) 


    </script>

    <hr>";


    
    SHOWBATTLE($db,$world,$lang);


    echo "</div>";

    

    //Script Listener Tarjeta
    echo "
    
    <script>
    
    city = document.getElementById('city');
    resources = document.getElementById('resources');
    barrack = document.getElementById('barrack');
    stable = document.getElementById('stable');
    info = document.getElementById('info');
    map = document.getElementById('map');
    msg = document.getElementById('msg');
    battle = document.getElementById('battle');

    resourcesCITY = document.getElementById('resourcesCITY');
    cityCITY = document.getElementById('cityCITY');
    barrackCITY = document.getElementById('barrackCITY');
    stableCITY = document.getElementById('stableCITY');
    mapCITY = document.getElementById('mapCITY');
    msgCITY = document.getElementById('msgCITY');
    battleCITY = document.getElementById('battleCITY');

    resources.addEventListener('click', () => {
        resourcesCITY.style.display='flex';
        cityCITY.style.display='none';
        barrackCITY.style.display='none';
        stableCITY.style.display='none';
        infoCITY.style.display='none';
        mapCITY.style.display='none';
        msgCITY.style.display='none';
        formMSG.style.display='none';
        battleCITY.style.display='none';
        configuration.style.display='none';
        changePassword.style.display='none';
    }) 

    city.addEventListener('click', () => {

        resourcesCITY.style.display='none';
        cityCITY.style.display='flex';
        barrackCITY.style.display='none';
        stableCITY.style.display='none';
        infoCITY.style.display='none';
        mapCITY.style.display='none';
        msgCITY.style.display='none';
        formMSG.style.display='none';
        configuration.style.display='none';
        changePassword.style.display='none';
    }) 

    barrack.addEventListener('click', () => {

        resourcesCITY.style.display='none';
        cityCITY.style.display='none';
        barrackCITY.style.display='flex';
        stableCITY.style.display='none';
        infoCITY.style.display='none';
        mapCITY.style.display='none';
        msgCITY.style.display='none';
        formMSG.style.display='none';
        battleCITY.style.display='none';
        configuration.style.display='none';
        changePassword.style.display='none';
    }) 

    stable.addEventListener('click', () => {

        resourcesCITY.style.display='none';
        cityCITY.style.display='none';
        barrackCITY.style.display='none';
        stableCITY.style.display='flex';
        infoCITY.style.display='none';
        mapCITY.style.display='none';
        msgCITY.style.display='none';
        formMSG.style.display='none';
        battleCITY.style.display='none';
        configuration.style.display='none';
        changePassword.style.display='none';
    }) 

    info.addEventListener('click', () => {

        resourcesCITY.style.display='none';
        cityCITY.style.display='none';
        barrackCITY.style.display='none';
        stableCITY.style.display='none';
        infoCITY.style.display='block';
        mapCITY.style.display='none';
        msgCITY.style.display='none';
        formMSG.style.display='none';
        battleCITY.style.display='none';
        configuration.style.display='none';
        changePassword.style.display='none';
    }) 
    
    map.addEventListener('click', () => {

        resourcesCITY.style.display='none';
        cityCITY.style.display='none';
        barrackCITY.style.display='none';
        stableCITY.style.display='none';
        infoCITY.style.display='none';
        mapCITY.style.display='block';
        msgCITY.style.display='none';
        formMSG.style.display='none';
        battleCITY.style.display='none';
        configuration.style.display='none';
        changePassword.style.display='none';
    }) 

    msg.addEventListener('click', () => {

        resourcesCITY.style.display='none';
        cityCITY.style.display='none';
        barrackCITY.style.display='none';
        stableCITY.style.display='none';
        infoCITY.style.display='none';
        mapCITY.style.display='none';
        msgCITY.style.display='block';
        formMSG.style.display='none';
        battleCITY.style.display='none';
        configuration.style.display='none';
        changePassword.style.display='none';
    }) 

    battle.addEventListener('click', () => {

        resourcesCITY.style.display='none';
        cityCITY.style.display='none';
        barrackCITY.style.display='none';
        stableCITY.style.display='none';
        infoCITY.style.display='none';
        mapCITY.style.display='none';
        msgCITY.style.display='none';
        formMSG.style.display='none';
        battleCITY.style.display='block';
        configuration.style.display='none';
        changePassword.style.display='none';
    }) 
    
    
    </script>";
    

}

function SENDMSG($db,$world,$lang){

    
    $sql = "USE ".$world.";";
    $db->query($sql);

    
    $user = $_SESSION['USER'];

    $sql = "SELECT PLAYER_ID FROM PLAYERS wHERE PLAYER_NAME = '".$user."';";
    $result=$db->query($sql);
    $result=$result->fetch(PDO::FETCH_ASSOC);

    
    $to = $_POST['receiver'];
    
    $sql = "SELECT PLAYER_ID FROM PLAYERS wHERE PLAYER_NAME = '".$to."';";
    $result2=$db->query($sql);
    $result2=$result2->fetch(PDO::FETCH_ASSOC);

    $content = $_POST['content'];
    $tittle = $_POST['tittle'];
    
    if (!isset($result2['PLAYER_ID'])) {
        echo "<script>alert('".$lang['msg_4']." ".$to." ".$lang['msg_5']."!');</script>";

    } else if($result2 && ($_SESSION['CONTENT'] != $content || !isset($_SESSION['CONTENT']))){
        
        $sql = "INSERT INTO LETTER (SENDER_ID,RECEIVER_ID,CONTENT,TITTLE) VALUES (".$result['PLAYER_ID'].",".$result2['PLAYER_ID'].",'".$content."','".$tittle."');";
        $db->query($sql);
        $_SESSION['CONTENT'] = $content;

    }
    


    

}
function SHOWMSG($db,$world,$lang){

    $user = $_SESSION['USER'];

    $sql = "USE ".$world.";";
    $db->query($sql);

    $sql = "SELECT * FROM LETTER JOIN PLAYERS ON LETTER.RECEIVER_ID = PLAYERS.PLAYER_ID WHERE PLAYERS.PLAYER_NAME = '".$user."' ORDER BY LETTER.LETTER_ID DESC;";
    $result=$db->query($sql);
    $result=$result->fetchALL(PDO::FETCH_ASSOC);

    if (!empty($result)){

        foreach($result as $content){

            $sql = "SELECT PLAYER_NAME FROM PLAYERS WHERE PLAYER_ID = '".$content['SENDER_ID']."';";
            $result2=$db->query($sql);
            $result2=$result2->fetch(PDO::FETCH_ASSOC);

            echo "<div id='msg'>";
            echo "<p>".$lang['msg_3'].$result2['PLAYER_NAME']."</p>";            
            echo "<p>".$lang['msg_9'].": ".$content['TITTLE']."</p>";            
            echo "<p>".$lang['msg_10'].": ".$content['CONTENT']."</p>";


            echo "</div>";
            echo "<hr>";
        }

    } else {
        echo "<p>".$lang['msg_6']."</p>";
    }

}

function SHOWBATTLE($db,$world,$lang){

    $user = $_SESSION['USER'];

    $sql = "USE ".$world.";";
    $db->query($sql);

    $sql = "SELECT PLAYER_ID FROM PLAYERS WHERE PLAYER_NAME = '".$user."';";
    $result=$db->query($sql);
    $result=$result->fetch(PDO::FETCH_ASSOC);

    $sql = "SELECT * FROM BATTLE_LOG WHERE ATT_ID = '".$result['PLAYER_ID']."' OR DEF_ID = '".$result['PLAYER_ID']."' ORDER BY BATTLE_ID DESC;";
    $result2=$db->query($sql);
    $result2=$result2->fetchALL(PDO::FETCH_ASSOC);

    // Defino las tropas
    $unitTypes = ['SWORDMAN', 'PIKEMAN', 'ARCHER', 'L_CAVALRY', 'H_CAVALRY'];

    if (!empty($result2)) {
    for ($i = 0; $i < count($result2); $i++) {
        $log = $result2[$i];

        echo "<h3>".$lang['battleinf_6']." #{$log['BATTLE_ID']}</h3>";

        // Tropas Atacantes
        echo "<h4>".$lang['battleinf_2']."</h4>
        <table border='1'><tr>";
        echo "<th> </th>";
        for ($n = 0; $n < count($unitTypes); $n++) {
            echo "<th>{$unitTypes[$n]}</th>";
        }
        echo "</tr><tr>";
        
        echo "<td>".$lang['battleinf_2']."</td>";
        for ($n = 0; $n < count($unitTypes); $n++) {
            echo "<td>{$log['A'.$unitTypes[$n]]}</td>";
        }
        echo "</tr>";
        // Bajas Atacantes
        echo "<tr>";
        
        echo "<td>".$lang['battleinf_4']."</td>";
        for ($n = 0; $n < count($unitTypes); $n++) {
            echo "<td>{$log['A'.$unitTypes[$n].'_LOOSES']}</td>";
        }
        echo "</tr></table>";


        // Tropas Defensoras
        echo "<h4>".$lang['battleinf_3']."</h4>
        <table border='1'><tr>";
        
        echo "<th> </th>";
        for ($n = 0; $n < count($unitTypes); $n++) {
            echo "<th>{$unitTypes[$n]}</th>";
        }
        echo "</tr><tr>";
        
        echo "<td>".$lang['battleinf_3']."</td>";
        for ($n = 0; $n < count($unitTypes); $n++) {
            echo "<td>{$log['D'.$unitTypes[$n]]}</td>";
        }
        echo "</tr>";
        
        echo "<tr>";
        
        echo "<td>".$lang['battleinf_4']."</td>";
        for ($n = 0; $n < count($unitTypes); $n++) {
            echo "<td>{$log['D'.$unitTypes[$n].'_LOOSES']}</td>";
        }
        echo "</tr></table>";

        echo "El atacante robó: MADERA: ".$log["WOOD"]." | COMIDA: ".$log["FOOD"]." | PIEDRA: ".$log["STONE"]." | HIERRO ".$log["IRON"];

        echo "<hr>";
    }
}

}
function ARMYLIST($db,$world,$townid,$lang): void{

    $sql = "USE ".$world.";";
    $db->query($sql);

    $sql = "SELECT SWORDMAN,PIKEMAN,ARCHER,L_CAVALRY,H_CAVALRY FROM ARMY WHERE POSITION=".$townid.";";
    $result=$db->query($sql);
    $result=$result->fetch(PDO::FETCH_ASSOC);
    if($result){

    
        foreach ($result as $key => $unit){
            if ($unit > 0){
                
                echo "<img src='../assets/icon/world/".$key.".png' width='30px' height='30px'>";
                echo $unit;
            }
        }
    } else {
        echo "<p>".$lang['info_2']."</p>";
    }
}

function SHOWMAP($db,$world,$lang){

    $sql = "USE USERS_DB;";
    $db->query($sql);

    $sql = "SELECT WORLD_SIZE FROM WORLDSTATUS WHERE WORLD_ID ='".$world."';";

    $result=$db->query($sql);
    $result=$result->fetch(PDO::FETCH_ASSOC);

    $sql = "USE ".$world.";";
    $db->query($sql);

    $sql = "SELECT * FROM MAP;";
    $result2=$db->query($sql);
    $result2=$result2->fetchALL(PDO::FETCH_ASSOC);
    $counter = 0;

    //Aqui se muestra el ataque
     echo "<div class='window' id='atack' style='display: none;'>
                        
    </div>";   


    echo "<table>";

    //Div donde muestra para lanzar el ataque
    $user = $_SESSION['USER'];
    $sql = "SELECT PLAYER_ID FROM PLAYERS WHERE PLAYER_NAME = '".$user."';";
    $result3=$db->query($sql);
    $result3=$result3->fetch(PDO::FETCH_ASSOC);

    $sql = "SELECT SWORDMAN,PIKEMAN,ARCHER,L_CAVALRY,H_CAVALRY FROM ARMY WHERE PLAYER_ID = '".$result3['PLAYER_ID']."';";
    $result4=$db->query($sql);
    $result4=$result4->fetch(PDO::FETCH_ASSOC);

    if(empty($result4['SWORDMAN'])){$result4['SWORDMAN'] = 0;}
    if(empty($result4['PIKEMAN'])){$result4['PIKEMAN'] = 0;}
    if(empty($result4['ARCHER'])){$result4['ARCHER'] = 0;}
    if(empty($result4['L_CAVALRY'])){$result4['L_CAVALRY'] = 0;}
    if(empty($result4['H_CAVALRY'])){$result4['H_CAVALRY'] = 0;}


    for($i=0;$i<$result['WORLD_SIZE'];$i++){
        echo "<tr>";
        for($n=0;$n<$result['WORLD_SIZE'];$n++){
            // Si hay ciudad
            if($result2[$counter]['TOWN_ID']){

                

                // Si es del jugador o de otro
                if ($result2[$counter]['PLAYER_ID'] != $result3['PLAYER_ID']){

                echo "<td class='click' id='".$result2[$counter]['TOWN_ID']."'>";
                echo "<img src='../assets/images/city_MAP.jpg' style='width:3vw; height:3vw; display:block;'>";
                echo "</td>";
                

                echo "
                <script>
                
                 document.addEventListener('DOMContentLoaded', function() {
                    element = document.getElementById('".$result2[$counter]['TOWN_ID']."');    
                    let container = document.getElementById('atack');          
                    element.addEventListener('click', () => {
                        container.style.display='block';

                        container.innerHTML = '';

                        form = document.createElement('form');
                        form.method = 'post';
                        form.action = 'world.php';

                        
                        world = document.createElement('input');
                        world.type = 'hidden';
                        world.name = 'objective';
                        world.value = '".$result2[$counter]['TOWN_ID']."';
                        form.appendChild(world);

                        world = document.createElement('input');
                        world.type = 'hidden';
                        world.name = 'world';
                        world.value = '".$world."';
                        form.appendChild(world);

                        text = document.createElement('p');
                        text.textContent = '".$lang['info_1']."';
                        form.appendChild(text);
                        
                        br=document.createElement('br');
                        form.appendChild(br);

                        img1=document.createElement('img');
                        img1.style.width='30px';
                        img1.style.height='30px';
                        img1.src = '../assets/icon/world/swordman.png';
                        form.appendChild(img1);

                        swordman=document.createElement('input');
                        swordman.type='number';
                        swordman.name='swordman';
                        swordman.max=".$result4['SWORDMAN'].";
                        swordman.value='0';
                        form.appendChild(swordman);

                        img2=document.createElement('img');
                        img2.style.width='30px';
                        img2.style.height='30px';
                        img2.src = '../assets/icon/world/pikeman.png';
                        form.appendChild(img2);

                        pikeman=document.createElement('input');
                        pikeman.type='number';
                        pikeman.name='pikeman';
                        pikeman.max=".$result4['PIKEMAN'].";
                        pikeman.value='0';
                        form.appendChild(pikeman);

                        br=document.createElement('br');
                        form.appendChild(br);

                        img3=document.createElement('img');
                        img3.style.width='30px';
                        img3.style.height='30px';
                        img3.src = '../assets/icon/world/archer.png';
                        form.appendChild(img3);

                        archer=document.createElement('input');
                        archer.type='number';
                        archer.name='archer';
                        archer.max=".$result4['ARCHER'].";
                        archer.value='0';
                        form.appendChild(archer);

                        img4=document.createElement('img');
                        img4.style.width='30px';
                        img4.style.height='30px';
                        img4.src = '../assets/icon/world/l_cavalry.png';
                        form.appendChild(img4);

                        l_cavalry=document.createElement('input');
                        l_cavalry.type='number';
                        l_cavalry.name='l_cavalry';
                        l_cavalry.max=".$result4['L_CAVALRY'].";
                        l_cavalry.value='0';
                        form.appendChild(l_cavalry);

                        
                        br=document.createElement('br');
                        form.appendChild(br);

                        img5=document.createElement('img');
                        img5.style.width='30px';
                        img5.style.height='30px';
                        img5.src = '../assets/icon/world/h_cavalry.png';
                        form.appendChild(img5);

                        h_cavalry=document.createElement('input');
                        h_cavalry.type='number';
                        h_cavalry.name='h_cavalry';
                        h_cavalry.max=".$result4['H_CAVALRY'].";
                        h_cavalry.value='0';
                        form.appendChild(h_cavalry);
                        
                        br=document.createElement('br');
                        form.appendChild(br);


                        submit = document.createElement('input');
                        submit.type = 'submit';
                        submit.name = 'battle';
                        submit.value = '".$lang['info_7']."';
                        form.appendChild(submit);

                        close = document.createElement('button');
                        close.textContent='X';
                        close.id='closeAtack';
                        container.appendChild(close);


                        container.appendChild(form);

                        close.addEventListener('click', () => {
                            container.style.display = 'none';
                        });
                        
                    });
                });

                    
                </script>
                ";
                } else {
                    echo "<td class='click' id='".$result2[$counter]['TOWN_ID']."'>";
                    echo "<img src='../assets/images/city_MAP_PLAYER.jpg' style='width:3vw; height:3vw; display:block;'>";
                    echo "</td>";
                }
                



            } else {
                echo "<td>";
                echo "<img src='../assets/images/empty_MAP.jpg' style='width:3vw; height:3vw; display:block;'>";
                echo "</td>";
            }
            $counter++;
        }
        echo "</tr>";
    }

    echo "</table>";

}

?>