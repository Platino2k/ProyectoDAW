<?php
    error_reporting(0);
    
    session_start();
    
    include "../param/param.php";
    include "world-top.php";

    include "../functions/worldfunctions.php";
    
    
?>

<?php



    checkWorldStatus($db,$WORLD);

    echo "<div class='menuTop'>
        <img src='../assets/icon/config.png' id='config' width='30' heigth='30'>
        <a href='../index.php?logout'><img src='../assets/icon/exit.png' width=30px heigth=30px></a>
    </div>";

    
    echo '<div id="configuration" style="display: none;">
    
                <button id="closeConfig">X</button>
                <button id="changePass">';echo $lang['config_1'];echo '</button>
                <button id="changePass" onclick="window.location.href=`../main.php`">';echo $lang['config_5'];echo '</button>

                <div class="lang_selector">

                    <ul>';echo $lang["language"];echo '

                        <li><a href="world.php?world='.$WORLD.'&lang=es"><img src="../assets/icon/es_flag.png" width=20px heigth=20px> Español</a></li>
                        <li><a href="world.php?world='.$WORLD.'&lang=en"><img src="../assets/icon/en_flag.png" width=20px heigth=20px> English</a></li>
                        <li><a href="world.php?world='.$WORLD.'&lang=de"><img src="../assets/icon/de_flag.png" width=20px heigth=20px> Deutsch</a></li>

                    </ul>

                </div>
                

                

        </div>
        
        <div id="changePassword" style="display: none;">

                <button id="closeConfig2">X</button>
                <form method="post" action="world.php?world='.$WORLD.'">
                    <p>';echo $lang["config_2"];echo '</p>
                    <input type="password" name="oldPass">
                    <p>';echo $lang["config_3"];echo '</p>
                    <input type="password" name="newPass"><br>
                    <input type="submit" name="changePass" value="';echo $lang["config_4"];echo '">

                </form>

        </div>
        
        <script>
            config=document.getElementById("config");
            configuration=document.getElementById("configuration");
            changePass=document.getElementById("changePass");
            changePassword=document.getElementById("changePassword");
            
            closeConfig=document.getElementById("closeConfig");
            closeConfig2=document.getElementById("closeConfig2");

            config.addEventListener("click", () =>{
            
                configuration.style.display = "block";
            
            })

            changePass.addEventListener("click", () =>{
            
                configuration.style.display = "none";
                changePassword.style.display = "block";
            
            })

            closeConfig.addEventListener("click", () =>{
            
                configuration.style.display = "none";
            
            })
            closeConfig2.addEventListener("click", () =>{
            
                changePassword.style.display = "none";
            
            })
        

        </script>
        ';
    

    echo "<div class='main'>

        <div class='leftMenu'>

            <ul>
                <li id='info'>
                    Info
                </li>
                <li id='resources'>
                    ";echo $lang["worldnavbar_1"];echo"
                </li>
                <li id='city'>
                    ";echo $lang["worldnavbar_2"];echo"
                </li>
                <li id='barrack'>
                    ";echo $lang["worldnavbar_6"];echo"
                </li>
                <li id='stable'>
                    ";echo $lang["worldnavbar_7"];echo"
                </li>
                <li id='map'>
                    ";echo $lang["worldnavbar_3"];echo"
                </li>
                <li id='msg'>
                    ";echo $lang["worldnavbar_4"];echo"
                </li>
                <li id='battle'>
                    ";echo $lang["worldnavbar_5"];echo"
                </li>
            </ul>

        </div>
    
        <div class='mainBody'>
            <div class='Resources' style='display:flex'>";
                $id=showResources($db,$WORLD,$building);
            
                $sql = "SELECT * FROM TOWN JOIN PLAYERS ON TOWN.PLAYER_ID = PLAYERS.PLAYER_ID WHERE PLAYERS.PLAYER_NAME = '".$_SESSION['USER']."';";
                $result = $db->query($sql);
                $result=$result->fetch(PDO::FETCH_ASSOC);
                echo "<div id='townContainer'>
        <img id='townEdit' src='../assets/icon/edit.png' width='30px' height='30px'>
        <h3 id='townName'>".$result["TOWN_NAME"]."</h3>
      </div>";

        echo "<script>
            const editIcon = document.getElementById('townEdit');
            const townName = document.getElementById('townName');
            const container = document.getElementById('townContainer');

            editIcon.addEventListener('click', () => {

                // Crear formulario
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = 'world.php?world=".$WORLD."';

                const input = document.createElement('input');
                input.type = 'text';
                input.name = 'town_name';
                input.id = 'inputTxt';
                input.value = '".$result["TOWN_NAME"]."';
                input.required = true;

                const hiddenId = document.createElement('input');
                hiddenId.type = 'hidden';
                hiddenId.name = 'town_id';
                hiddenId.value = '".$result["TOWN_ID"]."';

                const submit = document.createElement('input');
                submit.type = 'submit';
                submit.id = 'submitButton';
                submit.name = 'changeName';
                submit.value = 'Guardar';

                form.appendChild(input);
                form.appendChild(hiddenId);
                form.appendChild(submit);

                container.replaceChild(form, townName);
            });
        </script>";


            echo "</div>";
            echo "<div class='background'>";
            if (($id%2) == 0){
                echo "<img src='../assets/images/TOWN1.jpg'>";
            } else {
                echo "<img src='../assets/images/TOWN2.jpg'>";
            }
            echo "</div>
        </div>

    </div>";
?>


<?php
    include "../functions/worldfunctions2.php";
    include "world-bottom.php";
?>