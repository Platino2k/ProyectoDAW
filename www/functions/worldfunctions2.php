<?php
if ($check == true){
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

            if ($result3["FOOD"] >= $armyCOST['SWORDMAN'][0] &&
                $result3["WOOD"] >= $armyCOST['SWORDMAN'][1] &&
                $result3["STONE"] >= $armyCOST['SWORDMAN'][2] &&
                $result3["IRON"] >= $armyCOST['SWORDMAN'][3] ){
                echo "<button id='resButton' onclick='window.location.href=`world.php?world=".$world."&building=".$nextBuilding[4]."`;'>Mejorar</button>";
            } else {
                echo "<button id='resButton' onclick='window.location.href=`world.php?world=".$world."&building=".$nextBuilding[4]."`;' disabled>Mejorar</button>";
            }
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

            if ($result3["FOOD"] >= $armyCOST['PIKEMAN'][0] &&
                $result3["WOOD"] >= $armyCOST['PIKEMAN'][1] &&
                $result3["STONE"] >= $armyCOST['PIKEMAN'][2] &&
                $result3["IRON"] >= $armyCOST['PIKEMAN'][3] ){
                echo "<button id='resButton' onclick='window.location.href=`world.php?world=".$world."&building=".$nextBuilding[5]."`;'>Mejorar</button>";
            } else {
                echo "<button id='resButton' onclick='window.location.href=`world.php?world=".$world."&building=".$nextBuilding[5]."`;' disabled>Mejorar</button>";
            }
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

            if ($result3["FOOD"] >= $armyCOST['ARCHER'][0] &&
                $result3["WOOD"] >= $armyCOST['ARCHER'][1] &&
                $result3["STONE"] >= $armyCOST['ARCHER'][2] &&
                $result3["IRON"] >= $armyCOST['ARCHER'][3] ){
                echo "<button id='resButton' onclick='window.location.href=`world.php?world=".$world."&building=".$nextBuilding[6]."`;'>Mejorar</button>";
            } else {
                echo "<button id='resButton' onclick='window.location.href=`world.php?world=".$world."&building=".$nextBuilding[6]."`;' disabled>Mejorar</button>";
            }
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

            if ($result3["FOOD"] >= $armyCOST['L_CAVALRY'][0] &&
                $result3["WOOD"] >= $armyCOST['L_CAVALRY'][1] &&
                $result3["STONE"] >= $armyCOST['L_CAVALRY'][2] &&
                $result3["IRON"] >= $armyCOST['L_CAVALRY'][3] ){
                echo "<button id='resButton' onclick='window.location.href=`world.php?world=".$world."&building=".$nextBuilding[4]."`;'>Mejorar</button>";
            } else {
                echo "<button id='resButton' onclick='window.location.href=`world.php?world=".$world."&building=".$nextBuilding[4]."`;' disabled>Mejorar</button>";
            }
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

            if ($result3["FOOD"] >= $armyCOST['H_CAVALRY'][0] &&
                $result3["WOOD"] >= $armyCOST['H_CAVALRY'][1] &&
                $result3["STONE"] >= $armyCOST['H_CAVALRY'][2] &&
                $result3["IRON"] >= $armyCOST['H_CAVALRY'][3] ){
                echo "<button id='resButton' onclick='window.location.href=`world.php?world=".$world."&building=".$nextBuilding[5]."`;'>Mejorar</button>";
            } else {
                echo "<button id='resButton' onclick='window.location.href=`world.php?world=".$world."&building=".$nextBuilding[5]."`;' disabled>Mejorar</button>";
            }
            echo "<p>NIVEL: ";echo $level[5];echo"</p>
        </div>


    </div>
    ";

    //Script Listener Tarjeta
    echo "
    
    <script>
    
    city = document.getElementById('city');
    resources = document.getElementById('resources');
    barrack = document.getElementById('barrack');
    stable = document.getElementById('stable');
    info = document.getElementById('info');

    resourcesCITY = document.getElementById('resourcesCITY');
    cityCITY = document.getElementById('cityCITY');
    barrackCITY = document.getElementById('barrackCITY');
    stableCITY = document.getElementById('stableCITY');

    resources.addEventListener('click', () => {
        resourcesCITY.style.display='flex';
        cityCITY.style.display='none';
        barrackCITY.style.display='none';
        stableCITY.style.display='none';
    }) 

    city.addEventListener('click', () => {

        resourcesCITY.style.display='none';
        cityCITY.style.display='flex';
        barrackCITY.style.display='none';
        stableCITY.style.display='none';
    }) 

    barrack.addEventListener('click', () => {

        resourcesCITY.style.display='none';
        cityCITY.style.display='none';
        barrackCITY.style.display='flex';
        stableCITY.style.display='none';
    }) 

    stable.addEventListener('click', () => {

        resourcesCITY.style.display='none';
        cityCITY.style.display='none';
        barrackCITY.style.display='none';
        stableCITY.style.display='flex';
    }) 

    info.addEventListener('click', () => {

        resourcesCITY.style.display='none';
        cityCITY.style.display='none';
        barrackCITY.style.display='none';
        stableCITY.style.display='none';
    }) 
    
    
    </script>";
    

}

?>