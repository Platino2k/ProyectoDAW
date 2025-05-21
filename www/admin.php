<?php

    session_start();

    include "config.php";
    include "adminfunctions.php";
    
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="css/admin-styles.css" rel="stylesheet">
</head>
<body>

<?php


// Compruebo si se ha iniciado sesion, si NO se ha iniciado, va al else

if(isset($check) && $check == true){

        echo '
        <div class="sidebar">
            <h1>Proyecto Daw</h1>

            <hr>
            <div class="button-list">
                <button class="create-world-button">
                    ';echo $lang["createworld_1"];echo '
                </button>

                <button class="world-list-button">
                    ';echo $lang["worldlist_1"];echo '
                </button>
                
                <button class="player-list-button">
                    ';echo $lang["playerlist_1"];echo '
                </button>
            </div>

            <hr class="separator">

            <div class="footer">
                <img id="config" src="assets/icon/config.png">
                <a href="admin.php?logout"><img id="config" src="assets/icon/exit.png"></a>
            </div>
        </div>

       


        <div class="main">

            <h1 id="main-text">PROYECTO DAW</h1>
            
        </div>

        <script>

            worldlist=document.getElementsByClassName("world-list-button")[0];
            worldlist.addEventListener("click", () =>{

                text=document.getElementById("main-text");
                if(text){
                    text.remove();

                    panel = document.createElement("div");
                    panel.id = "create-world";
                    panel.style.display = "block";
                    panel.style.backgroundColor = "white";
                    panel.style.width = "70vw";
                    panel.style.height = "90vh";
                    panel.style.margin = "auto";
                    panel.style.color = "black";
                    panel.style.border = "5px solid #0097A7";
                    panel.style.boxShadow = "2px 2px 3px #0097A7";
                    
                   
                    document.getElementsByClassName("main")[0].appendChild(panel); 
                }

                    panel.innerHTML = "";
                    panel.innerHTML = `
                    <div class="panelTop">
                    <p>';echo $lang["worldlist_1"];echo '</p>
                    <hr>
                    </div>
                    <div class="panelMain"> 
                    ';


                    // Creo Cabecera de la tabla
                     echo "<table class='MAINTABLE' style='border-collapse: collapse;'>";
                            echo "<tr>
                                <td>";echo $lang['worldlist_3']; echo "</td>
                                <td>";echo $lang['worldlist_4']; echo "</td>
                                <td>";echo $lang['worldlist_5']; echo "</td>
                            </tr>";
                    $WORLDLIST = WORLDLIST($db);
                    if (empty($WORLDLIST)){
                        echo "<tr><td>";
                        echo $lang["worldlist_2"];
                        echo "</td></tr>";
                    }
                        echo "</table>";
                
                    echo '</div>
                    `;


            })

            playerlist=document.getElementsByClassName("player-list-button")[0];
            playerlist.addEventListener("click", () =>{

                text=document.getElementById("main-text");
                if(text){
                    text.remove();

                    panel = document.createElement("div");
                    panel.id = "create-world";
                    panel.style.display = "block";
                    panel.style.backgroundColor = "white";
                    panel.style.width = "70vw";
                    panel.style.height = "90vh";
                    panel.style.margin = "auto";
                    panel.style.color = "black";
                    panel.style.border = "5px solid #0097A7";
                    panel.style.boxShadow = "2px 2px 3px #0097A7";
                    
                   
                    document.getElementsByClassName("main")[0].appendChild(panel); 
                }

                    panel.innerHTML = "";
                    panel.innerHTML = `
                    <div class="panelTop">
                    <p>';echo $lang["playerlist_1"];echo '</p>
                    <hr>
                    </div>
                    
                    `;


            })

            createworld=document.getElementsByClassName("create-world-button")[0];
            createworld.addEventListener("click", () =>{

                text=document.getElementById("main-text");
                if(text){
                    text.remove();

                    panel = document.createElement("div");
                    panel.id = "create-world";
                    panel.style.display = "block";
                    panel.style.backgroundColor = "white";
                    panel.style.width = "70vw";
                    panel.style.height = "90vh";
                    panel.style.margin = "auto";
                    panel.style.color = "black";
                    panel.style.border = "5px solid #0097A7";
                    panel.style.boxShadow = "2px 2px 3px #0097A7";
                    
                   
                    document.getElementsByClassName("main")[0].appendChild(panel); 
                }

                    panel.innerHTML = "";
                    panel.innerHTML = `
                    <div class="panelTop">
                        <p>';echo $lang["createworld_1"];echo '</p>
                    </div>
                    <hr>

                    <div class="panelMain">
                        <form method="post">

                            <label>';echo $lang["createworld_2"];echo '</label><br>
                            <input type="text" id="name" name="world_name" placeholder="';echo $lang["createworld_3"];echo '"><br>

                            <label>';echo $lang["createworld_4"];echo '</label><br>
                            <input type="number" id="playerqty" name="player_qty" placeholder="Min = 8 / Max = 64"  max="64" min="8"><br>

                            <label>';echo $lang["createworld_5"];echo '</label><br>
                            <input type="number" id="mapsize" name="map_size" placeholder="Max = 100" max="100"> <br>
                            
                            <input type="submit"  id="submit" name="createWorld_form" value="';echo $lang["createworld_1"];echo '">

                        </form>
                    </div>

                    `;


            })

        </script>

        ';
        
} else {
    echo '

        
        <div class="login">
            
            <h1>PROYECTO DAW</h1>

                <h3>';echo $lang["login_1"]; echo '</h3>

                    <form method="post">
                        <div class="form">
                            <img src="assets/icon/login_user.png" width=20px heigth=20px>
                            <input type="text" name="username" placeholder = "'; echo $lang["login_2"]; echo '" required><br>
                        </div>
                        <div class="form">
                        <img src="assets/icon/login_pass.png" width=20px heigth=20px>
                        <input type="password" name="password" placeholder = "********"required><br>
                        </div>
                        <button type="submit" class="formbutton" name="formdata" value="LOGIN">'; echo $lang["login_4"]; echo '</button>

                    </form>
                    <hr class="separator">
                <div class="lang_selector">

                    <ul>';echo $lang["language"];echo '

                        <li><a href="admin.php?lang=es"><img src="assets/icon/es_flag.png" width=20px heigth=20px> Spanish</a></li>
                        <li><a href="admin.php?lang=en"><img src="assets/icon/en_flag.png" width=20px heigth=20px> English</a></li>
                        <li><a href="admin.php?lang=de"><img src="assets/icon/de_flag.png" width=20px heigth=20px> German</a></li>

                    </ul>

                </div>

        </div> 
    ';


}



?>

</body>
</html>