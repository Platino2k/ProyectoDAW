<?php
if ($check == true){
    ADDPLAYER($db,$WORLD);
    LOADTOWN($db,$WORLD);

}

function LOADTOWN($db,$world){
    // Carga la ciudad

    $sql = "USE ".$world.";";
    $db->query($sql);


    $sql = "SELECT TOWN.TOWN_ID FROM TOWN JOIN PLAYERS ON TOWN.PLAYER_ID = PLAYERS.PLAYER_ID WHERE PLAYERS.PLAYER_NAME = '".$_SESSION['USER']."' LIMIT 1;";
    $result=$db->query($sql);
    $result=$result->fetch(PDO::FETCH_ASSOC);
    $townid = $result['TOWN_ID'];

    $sql = "SELECT * FROM TOWN_BUILDINGS WHERE TOWN_ID = '".$townid."';";
    $result2=$db->query($sql);
    $result2=$result2->fetchAll(PDO::FETCH_ASSOC);


    $level = [];
    $i=0;
    
    foreach($result2 as $building){
        $txt = $building['BUILDING'];
        $split = explode('_',$txt);
        $level[$i] = $split[1];
        $i++;
    }


    // Recursos
    echo "<div class='window' id='resourcesCITY' style='display: none;'>

        <div id='food'>
            <img src='../assets/images/food.jpg' width='100px' height='100px'>
            <a>Mejorar</a>
            <p>NIVEL: ";echo $level[0];echo"</p>
        </div>

        <div id='wood'>
            <img src='../assets/images/wood.jpg' width='100px' height='100px'>
            <a>Mejorar</a>
            <p>NIVEL: ";echo $level[1];echo"</p>
        </div>

        <div id='stone'>
            <img src='../assets/images/stone.jpg' width='100px' height='100px'>
            <a>Mejorar</a>
            <p>NIVEL: ";echo $level[2];echo"</p>
        </div>

        <div id='iron'>
            <img src='../assets/images/iron.jpg' width='100px' height='100px'>
            <a>Mejorar</a>
            <p>NIVEL: ";echo $level[3];echo"</p>
        </div>

    </div>
    ";

    // Ciudad

    echo "<div class='window' id='cityCITY' style='display: none;'>

        <div id='townhall'>
            <img src='../assets/images/townhall.jpg' width='100px' height='100px'>

            <a>Mejorar</a>
            <p>NIVEL: ";echo $level[4];echo"</p>
        </div>

        <div id='storehouse'>
            <img src='../assets/images/storehouse.jpg' width='100px' height='100px'>
            <a>Mejorar</a>
            <p>NIVEL: ";echo $level[5];echo"</p>
        </div>

        <div id='barracks'>
            <img src='../assets/images/barracks.jpg' width='100px' height='100px'>
            <a>Mejorar</a>
            <p>NIVEL: ";echo $level[6];echo"</p>
        </div>

        <div id='stable'>
            <img src='../assets/images/stable.jpg' width='100px' height='100px'>
            <a>Mejorar</a>
            <p>NIVEL: ";echo $level[7];echo"</p>
        </div>

    </div>
    ";


    //Script Listener
    echo "
    
    <script>
    
    city = document.getElementById('city');
    resources = document.getElementById('resources');

    resourcesCITY = document.getElementById('resourcesCITY');
    cityCITY = document.getElementById('cityCITY');

    resources.addEventListener('click', () => {
        resourcesCITY.style.display='block';
        cityCITY.style.display='none';
    }) 

    city.addEventListener('click', () => {

        resourcesCITY.style.display='none';
        cityCITY.style.display='block';
    }) 
    
    </script>";
    

}

?>