<?php

    session_start();

    include "adminfunctions.php";
    include "config.php";
    
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="styles.css" rel="stylesheet">
</head>
<body>

<?php

// COMPRUEBO QUE SI NO HAY USUARIOS Y CREO EL PRIMERO



if(isset($check) && $check == true){


        echo '
        <h1 id="txtTOP">PROJECT CONTROL PANEL</h1>

        <div class="wrapper">

            <div class="lang_selector">

                <ul>';echo $lang["language"];echo '

                    <li><a href="admin.php?lang=es"><img src="assets/icon/es_flag.png" width=20px heigth=20px> Spanish</a></li>
                    <li><a href="admin.php?lang=en"><img src="assets/icon/en_flag.png" width=20px heigth=20px> English</a></li>
                    <li><a href="admin.php?lang=de"><img src="assets/icon/de_flag.png" width=20px heigth=20px> German</a></li>

                </ul>

            </div>  
                <div> 
                    <h3>CREAR USUARIO</h3>
                            <form method="post">

                                <label>Nombre de Usuario</label>
                                <input type="text" name="username" required><br>
                                <label>Contraseña</label>
                                <input type="text" name="password" required><br>
                                <label>Email</label>
                                <input type="email" name="email" required><br>
                                <label>Fecha de Nacimiento</label>
                                <input type="date" name="birthdate" required><br>
                                <input type="submit" name="formdata" value="REGISTER">

                            </form>
                </div>

                <div>
                    


                </div> 

                <div> 
                    <h3>Crear nuevo mundo</h3>
                    <form method="post">

                        <input type="submit" name="formdata" value="CREATEWORLD">

                    </form>
                </div>

                <div></div>

                <div>
                    
                </div>

                <div>


                </div>

                <div>

                

                </div>

            </div>



        ';
        
        echo '
        <form method="post">
            <button type="submit" name="logout">Cerrar Sesión</button>
        </form>';
} else {
    echo '

        <div class="lang_selector">

            <ul>';echo $lang["language"];echo '

                <li><a href="admin.php?lang=es"><img src="assets/icon/es_flag.png" width=20px heigth=20px> Spanish</a></li>
                <li><a href="admin.php?lang=en"><img src="assets/icon/en_flag.png" width=20px heigth=20px> English</a></li>
                <li><a href="admin.php?lang=de"><img src="assets/icon/de_flag.png" width=20px heigth=20px> German</a></li>

            </ul>

        </div>

        <div>
                
                <h3>';echo $lang["login_1"]; echo '</h3>

                    <form method="post">

                        <label>'; echo $lang["login_2"]; echo '</label>
                        <input type="text" name="username" required><br>

                        <label>'; echo $lang["login_3"]; echo '</label>
                        <input type="text" name="password" required><br>
                        
                        <button type="submit" name="formdata" value="LOGIN">'; echo $lang["login_4"]; echo '</button>

                    </form>


            </div> 
    ';


}



?>

</body>
</html>