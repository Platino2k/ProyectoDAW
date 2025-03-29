<?php
    include "functions.php";
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
    
<div class="wrapper">

        <div></div>

        <div class="menu">
            <p>DAWVIAN</p>
            <br>
            
        </div> 

        <div> 

        </div>

        <div></div>

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
                    <input type="submit" name="formdata" value="Crear">

                </form>
        </div>

        <div>

                <h3>Iniciar Sesion</h3>
                <form method="post">

                    <label>Nombre de Usuario</label>
                    <input type="text" name="username" required><br>
                    <label>Contraseña</label>
                    <input type="text" name="password" required><br>
                    <input type="submit" name="formdata" value="Entrar">

                </form>

        </div>

        <div>

        <h3>Crear nuevo mundo</h3>
        <form method="post">

            <input type="submit" name="formdata" value="CrearMundo">

        </form>

        </div>

    </div>

</body>
</html>