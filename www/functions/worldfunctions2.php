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
    echo "<div class='window' id='resourcesCITY' style='display: none;'>

        <div id='food'>
            <p>";echo $lang['resources_1'];echo"</p>
            <ul>
            <p>COSTE:";echo $buildingCOST[$nextBuilding[0]][0].
            " </p><p>".$buildingCOST[$nextBuilding[0]][1].
            " </p><p>".$buildingCOST[$nextBuilding[0]][2].
            " </p><p>".$buildingCOST[$nextBuilding[0]][3];
            echo "</p></ul>
            <img src='../assets/images/food.jpg' width='100px' height='100px'>";

            if ($result3["FOOD"] >= $buildingCOST[$nextBuilding[0]][0] &&
                $result3["WOOD"] >= $buildingCOST[$nextBuilding[0]][1] &&
                $result3["STONE"] >= $buildingCOST[$nextBuilding[0]][2] &&
                $result3["IRON"] >= $buildingCOST[$nextBuilding[0]][3] ){
                echo "<button id='resButton' onclick='window.location.href=`world.php?world=".$world."&building=".$nextBuilding[0]."`;'>Mejorar</button>";
            } else {
                echo "<button id='resButton' onclick='window.location.href=`world.php?world=".$world."&building=".$nextBuilding[0]."`;' disabled>Mejorar</button>";
            }
            echo "<p>NIVEL: ";echo $level[0];echo"</p>
        </div>

        <div id='wood'>
            <p>";echo $lang['resources_2'];echo"</p>
            <ul>
            <p>COSTE:";echo $buildingCOST[$nextBuilding[1]][0].
            " </p><p>".$buildingCOST[$nextBuilding[1]][1].
            " </p><p>".$buildingCOST[$nextBuilding[1]][2].
            " </p><p>".$buildingCOST[$nextBuilding[1]][3];
            echo "</p></ul>
            <img src='../assets/images/wood.jpg' width='100px' height='100px'>";

            if ($result3["FOOD"] >= $buildingCOST[$nextBuilding[1]][0] &&
                $result3["WOOD"] >= $buildingCOST[$nextBuilding[1]][1] &&
                $result3["STONE"] >= $buildingCOST[$nextBuilding[1]][2] &&
                $result3["IRON"] >= $buildingCOST[$nextBuilding[1]][3] ){
                echo "<button id='resButton' onclick='window.location.href=`world.php?world=".$world."&building=".$nextBuilding[1]."`;'>Mejorar</button>";
            } else {
                echo "<button id='resButton' onclick='window.location.href=`world.php?world=".$world."&building=".$nextBuilding[1]."`;' disabled>Mejorar</button>";
            }
            echo "<p>NIVEL: ";echo $level[1];echo"</p>
        </div>

        <div id='stone'>
            <p>";echo $lang['resources_3'];echo"</p>
            <ul>
            <p>COSTE:";echo $buildingCOST[$nextBuilding[2]][0].
            " </p><p>".$buildingCOST[$nextBuilding[2]][1].
            " </p><p>".$buildingCOST[$nextBuilding[2]][2].
            " </p><p>".$buildingCOST[$nextBuilding[2]][3];
            echo "</p></ul>
            <img src='../assets/images/stone.jpg' width='100px' height='100px'>";

            if ($result3["FOOD"] >= $buildingCOST[$nextBuilding[2]][0] &&
                $result3["WOOD"] >= $buildingCOST[$nextBuilding[2]][1] &&
                $result3["STONE"] >= $buildingCOST[$nextBuilding[2]][2] &&
                $result3["IRON"] >= $buildingCOST[$nextBuilding[2]][3] ){
                echo "<button id='resButton' onclick='window.location.href=`world.php?world=".$world."&building=".$nextBuilding[2]."`;'>Mejorar</button>";
            } else {
                echo "<button id='resButton' onclick='window.location.href=`world.php?world=".$world."&building=".$nextBuilding[2]."`;' disabled>Mejorar</button>";
            }
            echo "<p>NIVEL: ";echo $level[2];echo"</p>
        </div>

        <div id='iron'>
            <p>";echo $lang['resources_4'];echo"</p>
            <ul>
            <p>COSTE:";echo $buildingCOST[$nextBuilding[3]][0].
            " </p><p>".$buildingCOST[$nextBuilding[3]][1].
            " </p><p>".$buildingCOST[$nextBuilding[3]][2].
            " </p><p>".$buildingCOST[$nextBuilding[3]][3];
            echo "</p></ul>
            <img src='../assets/images/iron.jpg' width='100px' height='100px'>";

            if ($result3["FOOD"] >= $buildingCOST[$nextBuilding[3]][0] &&
                $result3["WOOD"] >= $buildingCOST[$nextBuilding[3]][1] &&
                $result3["STONE"] >= $buildingCOST[$nextBuilding[3]][2] &&
                $result3["IRON"] >= $buildingCOST[$nextBuilding[3]][3] ){
                echo "<button id='resButton' onclick='window.location.href=`world.php?world=".$world."&building=".$nextBuilding[3]."`;'>Mejorar</button>";
            } else {
                echo "<button id='resButton' onclick='window.location.href=`world.php?world=".$world."&building=".$nextBuilding[3]."`;' disabled>Mejorar</button>";
            }
            echo "<p>NIVEL: ";echo $level[3];echo"</p>
        </div>

    </div>
    ";

    // Ciudad

    echo "<div class='window' id='cityCITY' style='display: none;'>

        <div id='townhall'>
            <p>";echo $lang['city_1'];echo"</p>
            <ul>
            <p>COSTE:";echo $buildingCOST[$nextBuilding[4]][0].
            " </p><p>".$buildingCOST[$nextBuilding[4]][1].
            " </p><p>".$buildingCOST[$nextBuilding[4]][2].
            " </p><p>".$buildingCOST[$nextBuilding[4]][3];
            echo "</p></ul>
            <img src='../assets/images/townhall.jpg' width='100px' height='100px'>";

            if ($result3["FOOD"] >= $buildingCOST[$nextBuilding[4]][0] &&
                $result3["WOOD"] >= $buildingCOST[$nextBuilding[4]][1] &&
                $result3["STONE"] >= $buildingCOST[$nextBuilding[4]][2] &&
                $result3["IRON"] >= $buildingCOST[$nextBuilding[4]][3] ){
                echo "<button id='resButton' onclick='window.location.href=`world.php?world=".$world."&building=".$nextBuilding[4]."`;'>Mejorar</button>";
            } else {
                echo "<button id='resButton' onclick='window.location.href=`world.php?world=".$world."&building=".$nextBuilding[4]."`;' disabled>Mejorar</button>";
            }
            echo "<p>NIVEL: ";echo $level[4];echo"</p>
        </div>

        <div id='storehouse'>
            <p>";echo $lang['city_2'];echo"</p>
            <ul>
            <p>COSTE:";echo $buildingCOST[$nextBuilding[5]][0].
            " </p><p>".$buildingCOST[$nextBuilding[5]][1].
            " </p><p>".$buildingCOST[$nextBuilding[5]][2].
            " </p><p>".$buildingCOST[$nextBuilding[5]][3];
            echo "</p></ul>
            <img src='../assets/images/storehouse.jpg' width='100px' height='100px'>";

            if ($result3["FOOD"] >= $buildingCOST[$nextBuilding[5]][0] &&
                $result3["WOOD"] >= $buildingCOST[$nextBuilding[5]][1] &&
                $result3["STONE"] >= $buildingCOST[$nextBuilding[5]][2] &&
                $result3["IRON"] >= $buildingCOST[$nextBuilding[5]][3] ){
                echo "<button id='resButton' onclick='window.location.href=`world.php?world=".$world."&building=".$nextBuilding[5]."`;'>Mejorar</button>";
            } else {
                echo "<button id='resButton' onclick='window.location.href=`world.php?world=".$world."&building=".$nextBuilding[5]."`;' disabled>Mejorar</button>";
            }
            echo "<p>NIVEL: ";echo $level[5];echo"</p>
        </div>

        <div id='barracks'>
            <p>";echo $lang['city_3'];echo"</p>
            <ul>
            <p>COSTE:";echo $buildingCOST[$nextBuilding[6]][0].
            " </p><p>".$buildingCOST[$nextBuilding[6]][1].
            " </p><p>".$buildingCOST[$nextBuilding[6]][2].
            " </p><p>".$buildingCOST[$nextBuilding[6]][3];
            echo "</p></ul>
            <img src='../assets/images/barracks.jpg' width='100px' height='100px'>";

            if ($result3["FOOD"] >= $buildingCOST[$nextBuilding[6]][0] &&
                $result3["WOOD"] >= $buildingCOST[$nextBuilding[6]][1] &&
                $result3["STONE"] >= $buildingCOST[$nextBuilding[6]][2] &&
                $result3["IRON"] >= $buildingCOST[$nextBuilding[6]][3] ){
                echo "<button id='resButton' onclick='window.location.href=`world.php?world=".$world."&building=".$nextBuilding[6]."`;'>Mejorar</button>";
            } else {
                echo "<button id='resButton' onclick='window.location.href=`world.php?world=".$world."&building=".$nextBuilding[6]."`;' disabled>Mejorar</button>";
            }
            echo "<p>NIVEL: ";echo $level[6];echo"</p>
        </div>

        <div id='stable'>
            <p>";echo $lang['city_4'];echo"</p>
            <ul>
            <p>COSTE:";echo $buildingCOST[$nextBuilding[7]][0].
            " </p><p>".$buildingCOST[$nextBuilding[7]][1].
            " </p><p>".$buildingCOST[$nextBuilding[7]][2].
            " </p><p>".$buildingCOST[$nextBuilding[7]][3];
            echo "</p></ul>
            <img src='../assets/images/stable.jpg' width='100px' height='100px'>";

            if ($result3["FOOD"] >= $buildingCOST[$nextBuilding[7]][0] &&
                $result3["WOOD"] >= $buildingCOST[$nextBuilding[7]][1] &&
                $result3["STONE"] >= $buildingCOST[$nextBuilding[7]][2] &&
                $result3["IRON"] >= $buildingCOST[$nextBuilding[7]][3] ){
                echo "<button id='resButton' onclick='window.location.href=`world.php?world=".$world."&building=".$nextBuilding[7]."`;'>Mejorar</button>";
            } else {
                echo "<button id='resButton' onclick='window.location.href=`world.php?world=".$world."&building=".$nextBuilding[7]."`;' disabled>Mejorar</button>";
            }
            echo "<p>NIVEL: ";echo $level[7];echo"</p>
        </div>

    </div>
    ";


    // Cuartel Reclutamiento

    echo "<div class='window' id='barrackCITY' style='display: none;'>

        <div id='SWORDMAN'>
            <p>";echo $lang['barrack_1'];echo"</p>
            <ul>
            <p>COSTE:";echo $armyCOST['SWORDMAN'][0].
            " </p><p>".$armyCOST['SWORDMAN'][1].
            " </p><p>".$armyCOST['SWORDMAN'][2].
            " </p><p>".$armyCOST['SWORDMAN'][3];
            echo "</p></ul>
            <img src='../assets/images/swordman.jpg' width='100px' height='100px'>";
            echo "<form method='POST' action='world.php'>";
            echo "<input type='hidden' name='world' value='".$world."'>";
            echo "<input type='hidden' name='unit' value='SWORDMAN'>";
            
            echo "<input type='number' name='cuantity'>";
            if ($result3["FOOD"] >= $armyCOST['SWORDMAN'][0] &&
                $result3["WOOD"] >= $armyCOST['SWORDMAN'][1] &&
                $result3["STONE"] >= $armyCOST['SWORDMAN'][2] &&
                $result3["IRON"] >= $armyCOST['SWORDMAN'][3] ){
                echo "<input type='submit' name='UNITPROD' value='MEJORAR'>";
            } else {
                echo "<input type='submit name='UNITPROD' value='MEJORAR' disabled>";
            }
             echo "</form>";
            echo "<p>NIVEL: ";echo $level[4];echo"</p>
        </div>

        <div id='PIKEMAN'>
            <p>";echo $lang['barrack_2'];echo"</p>
            <ul>
            <p>COSTE:";echo $armyCOST['PIKEMAN'][0].
            " </p><p>".$armyCOST['PIKEMAN'][1].
            " </p><p>".$armyCOST['PIKEMAN'][2].
            " </p><p>".$armyCOST['PIKEMAN'][3];
            echo "</p></ul>
            <img src='../assets/images/pikeman.jpg' width='100px' height='100px'>";
            echo "<form method='POST' action='world.php'>";
            echo "<input type='hidden' name='world' value='".$world."'>";
            echo "<input type='hidden' name='unit' value='PIKEMAN'>";
            
            echo "<input type='number' name='cuantity'>";
            if ($result3["FOOD"] >= $armyCOST['PIKEMAN'][0] &&
                $result3["WOOD"] >= $armyCOST['PIKEMAN'][1] &&
                $result3["STONE"] >= $armyCOST['PIKEMAN'][2] &&
                $result3["IRON"] >= $armyCOST['PIKEMAN'][3] ){
                echo "<input type='submit' name='UNITPROD' value='MEJORAR'>";
            } else {
                echo "<input type='submit name='UNITPROD' value='MEJORAR' disabled>";
            }
             echo "</form>";
            echo "<p>NIVEL: ";echo $level[5];echo"</p>
        </div>

        <div id='ARCHER'>
            <p>";echo $lang['barrack_3'];echo"</p>
            <ul>
            <p>COSTE:";echo $armyCOST['ARCHER'][0].
            " </p><p>".$armyCOST['ARCHER'][1].
            " </p><p>".$armyCOST['ARCHER'][2].
            " </p><p>".$armyCOST['ARCHER'][3];
            echo "</p></ul>
            <img src='../assets/images/archer.jpg' width='100px' height='100px'>";
            echo "<form method='POST' action='world.php'>";
            echo "<input type='hidden' name='world' value='".$world."'>";
            echo "<input type='hidden' name='unit' value='ARCHER'>";
            
            echo "<input type='number' name='cuantity'>";
            if ($result3["FOOD"] >= $armyCOST['ARCHER'][0] &&
                $result3["WOOD"] >= $armyCOST['ARCHER'][1] &&
                $result3["STONE"] >= $armyCOST['ARCHER'][2] &&
                $result3["IRON"] >= $armyCOST['ARCHER'][3] ){
                echo "<input type='submit' name='UNITPROD' value='MEJORAR'>";
            } else {
                echo "<input type='submit name='UNITPROD' value='MEJORAR' disabled>";
            }
             echo "</form>";
            echo "<p>NIVEL: ";echo $level[6];echo"</p>
        </div>

    </div>
    ";

     // Establo Reclutamiento

    echo "<div class='window' id='stableCITY' style='display: none;'>

        <div id='L-CAVALRY'>
            <p>";echo $lang['stable_1'];echo"</p>
            <ul>
            <p>COSTE:";echo $armyCOST['L_CAVALRY'][0].
            " </p><p>".$armyCOST['L_CAVALRY'][1].
            " </p><p>".$armyCOST['L_CAVALRY'][2].
            " </p><p>".$armyCOST['L_CAVALRY'][3];
            echo "</p></ul>
            <img src='../assets/images/lightcab.png' width='100px' height='100px'>";
            echo "<form method='POST' action='world.php'>";
            echo "<input type='hidden' name='world' value='".$world."'>";
            echo "<input type='hidden' name='unit' value='L_CAVALRY'>";
            
            echo "<input type='number' name='cuantity'>";
            if ($result3["FOOD"] >= $armyCOST['L_CAVALRY'][0] &&
                $result3["WOOD"] >= $armyCOST['L_CAVALRY'][1] &&
                $result3["STONE"] >= $armyCOST['L_CAVALRY'][2] &&
                $result3["IRON"] >= $armyCOST['L_CAVALRY'][3] ){
                echo "<input type='submit' name='UNITPROD' value='MEJORAR'>";
            } else {
                echo "<input type='submit name='UNITPROD' value='MEJORAR' disabled>";
            }
            
            echo "</form>";
            echo "<p>NIVEL: ";echo $level[4];echo"</p>
        </div>

        <div id='H-CAVALRY'>
            <p>";echo $lang['stable_2'];echo"</p>
            <ul>
            <p>COSTE:";echo $armyCOST['H_CAVALRY'][0].
            " </p><p>".$armyCOST['H_CAVALRY'][1].
            " </p><p>".$armyCOST['H_CAVALRY'][2].
            " </p><p>".$armyCOST['H_CAVALRY'][3];
            echo "</p></ul>
            <img src='../assets/images/heavycab.png' width='100px' height='100px'>";
            echo "<form method='POST' action='world.php'>";
            echo "<input type='hidden' name='world' value='".$world."'>";
            echo "<input type='hidden' name='unit' value='H_CAVALRY'>";
            
            echo "<input type='number' name='cuantity'>";
            if ($result3["FOOD"] >= $armyCOST['H_CAVALRY'][0] &&
                $result3["WOOD"] >= $armyCOST['H_CAVALRY'][1] &&
                $result3["STONE"] >= $armyCOST['H_CAVALRY'][2] &&
                $result3["IRON"] >= $armyCOST['H_CAVALRY'][3] ){
                echo "<input type='submit' name='UNITPROD' value='MEJORAR'>";
            } else {
                echo "<input type='submit name='UNITPROD' value='MEJORAR' disabled>";
            }
            echo "</form>";
            echo "<p>NIVEL: ";echo $level[5];echo"</p>
        </div>


    </div>
    ";


     echo "<div class='windowINFO' id='infoCITY' style='display: block;'>

        <div id='construccion'>
            <p>LISTA DE CONSTRUCCION</p>
        </div>

        <div id='reclutamiento'>
            <p>LISTA DE Reclutamiento</p>
        </div>

        <div id='army'>
            ";
            ARMYLIST($db,$world,$townid);
            echo"
        </div>
        
        <div id='viaje'>
            <p>LISTA DE viaje/p>
        </div>


    </div>
    ";

     echo "<div class='windowMAP' id='mapCITY' style='display: none;'>";


    SHOWMAP($db,$world);   


    echo "</div>";

    //Formulario enviar MSG
     echo "<div class='formMSG' id='formMSG' style='display: none;'>
    <button id='closeMSG'>X</button>
            <form method='post'>
                <label>Enviar A:</label>
                <input type='text' name='receiver'>
                <label>CONTENIDO:</label>
                <input type='textarea' name='content' maxlength='1000'>
                <input type='submit' name='sendMSG' value='ENVIAR'>


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


    //SHOWMSG($db,$world,$lang);


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
    }) 

    battle.addEventListener('click', () => {

        resourcesCITY.style.display='none';
        cityCITY.style.display='none';
        barrackCITY.style.display='none';
        stableCITY.style.display='none';
        infoCITY.style.display='none';
        mapCITY.style.display='none';
        msgCITY.style.display='block';
        formMSG.style.display='none';
        battleCITY.style.display='none';
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

    if($result2 && ($_SESSION['CONTENT'] != $content || !isset($_SESSION['CONTENT']))){
        
        $sql = "INSERT INTO LETTER (SENDER_ID,RECEIVER_ID,CONTENT) VALUES (".$result['PLAYER_ID'].",".$result2['PLAYER_ID'].",'".$content."')";
        $db->query($sql);
        $_SESSION['CONTENT'] = $content;

    } else if (!isset($result2['PLAYER_ID'])) {
        echo "<script>alert('".$lang['msg_4']." ".$to." ".$lang['msg_5']."!";
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
            echo "<p>".$content['CONTENT']."</p>";


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

    $sql = "SELECT * FROM LETTER JOIN PLAYERS ON LETTER.SENDER_ID = PLAYERS.PLAYER_ID WHERE PLAYERS.PLAYER_NAME = '".$user."' ORDER BY LETTER.LETTER_ID DESC;";
    $result=$db->query($sql);
    $result=$result->fetchALL(PDO::FETCH_ASSOC);

    if (!empty($result)){

        foreach($result as $content){

            $sql = "SELECT PLAYER_NAME FROM PLAYERS WHERE PLAYER_ID = '".$content['SENDER_ID']."';";
            $result2=$db->query($sql);
            $result2=$result2->fetch(PDO::FETCH_ASSOC);

            echo "<div id='msg'>";
            echo "<p>".$lang['msg_3'].$result2['PLAYER_NAME']."</p>";            
            echo "<p>".$content['CONTENT']."</p>";


            echo "</div>";
            echo "<hr>";
        }

    } else {
        echo "<p>".$lang['msg_6']."</p>";
    }

}
function ARMYLIST($db,$world,$townid): void{

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
    }   
}

function SHOWMAP($db,$world){

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


     echo "<div class='window' id='atack' style='display: none;'>
                        
    </div>";   


    echo "<table>";

    //Div donde muestra para lanzar el ataque
    $user = $_SESSION['USER'];
    $sql = "SELECT PLAYER_ID FROM PLAYERS WHERE PLAYER_NAME = '".$user."';";
    $result3=$db->query($sql);
    $result3=$result3->fetch(PDO::FETCH_ASSOC);


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
                    element = document.getElementById('".$result2[$counter]['TOWN_ID']."');    
                    container = document.getElementById('atack');          
                    element.addEventListener('click', () => {
                        container.style.display='block';

                        container.innerHTML = '';

                        form = document.createElement('form');
                        form.method = 'post';
                        form.action = 'world.php';

                        
                        world = document.createElement('input');
                        world.type = 'hidden';
                        world.name = 'world';
                        world.value = '".$world."';
                        form.appendChild(world);

                        text = document.createElement('p');
                        text.textContent = 'EJERCITO';
                        form.appendChild(text);

                        text2 = document.createElement('p');
                        text2.textContent = 'OBJETIVO';
                        form.appendChild(text2);

                        objective=document.createElement('input');
                        objective.type='number';
                        objective.name='objective';
                        objective.value='".$result2[$counter]['TOWN_ID']."';
                        objective.readOnly=true;
                        form.appendChild(objective);

                        img1=document.createElement('img');
                        img1.style.width='30px';
                        img1.style.height='30px';
                        img1.src = '../assets/icon/world/swordman.png';
                        form.appendChild(img1);

                        swordman=document.createElement('input');
                        swordman.type='number';
                        swordman.name='swordman';
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
                        pikeman.value='0';
                        form.appendChild(pikeman);

                        img3=document.createElement('img');
                        img3.style.width='30px';
                        img3.style.height='30px';
                        img3.src = '../assets/icon/world/archer.png';
                        form.appendChild(img3);

                        archer=document.createElement('input');
                        archer.type='number';
                        archer.name='archer';
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
                        l_cavalry.value='0';
                        form.appendChild(l_cavalry);

                        img5=document.createElement('img');
                        img5.style.width='30px';
                        img5.style.height='30px';
                        img5.src = '../assets/icon/world/h_cavalry.png';
                        form.appendChild(img5);

                        h_cavalry=document.createElement('input');
                        h_cavalry.type='number';
                        h_cavalry.name='h_cavalry';
                        h_cavalry.value='0';
                        form.appendChild(h_cavalry);


                        submit = document.createElement('input');
                        submit.type = 'submit';
                        submit.name = 'battle';
                        submit.value = 'Enviar';
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