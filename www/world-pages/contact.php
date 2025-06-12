<?php
    error_reporting(0);
    
    session_start();
    
    include "../param/param.php";
    include "world-top.php";
?>


<?php

    echo '<a id="menuButton" href="../main.php">'.$lang['ticket_2'].'</a>

    <form id="contact" method="POST">

    <label>EMAIL</label><br>
    <input type="email" name="email" placeholder="Email" required><br>
    <label>'.$lang['ticket_3'].'</label><br>
    <textarea name="content" id="contentArea" maxlenght="1000" required></textarea><br>
    <input type="submit" name="ticket" value="'.$lang['ticket_4'].'">


    </form>';

    if(!empty($_POST['email']) && !empty($_POST['content'])){
        $user = "root";
        $server = "mariadb";
        $password = "rootpassword";

        $db = NEW PDO ("mysql:host=$server",$user,$password);
        
        $sql = "use USERS_DB;";
        $db->query($sql);

        
        $sql = "INSERT INTO TICKETS (EMAIL,CONTENT) VALUES ('".$_POST['email']."','".$_POST['content']."');";
        $db->query($sql);

        echo "<script>alert('Ticket enviado con Exito')</script>";
    } else {
        
        echo "<script>alert('Hubo algún error')</script>";
    }

?>





<?php
    include "world-bottom.php";
?>