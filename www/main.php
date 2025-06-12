<?php
error_reporting(0);
    session_start();
    include "config.php";
    include "functions/mainfunctions.php";

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Etravia</title>
    <link href="css/main-styles.css" rel="stylesheet">
    
    <link rel="icon" href="assets/icon/icon.png">
</head>
<body>
    
    <div class="menu-top">
    <?php
            echo "<h3>".$lang["welcome_1"].": ".$_SESSION['USER']."</h3>";

            echo "
            <div class='lang_selector'>
                <div class='lang_main'>".$lang['language']."</div>
                <ul class='lang_menu'>
                    <li><a href='main.php?lang=es'><img src='../assets/icon/es_flag.png' width='20px' height='20px'> Español</a></li>
                    <li><a href='main.php?lang=en'><img src='../assets/icon/en_flag.png' width='20px' height='20px'> English</a></li>
                    <li><a href='main.php?lang=de'><img src='../assets/icon/de_flag.png' width='20px' height='20px'> Deutsch</a></li>
                </ul>
            </div>";

            echo '<a href="index.php?logout"><img id="config" src="assets/icon/exit.png"></a>';
        ?>
    </div>


    <div class="menu">
        <div class="worldSelector">
            <?php
    
            echo "<h2>".$lang["worldselector_1"].":</h2>";
    
            ?>
        </div>

        <div class="worldList">
            <?php
    
                showWorlds($db,$lang);

            ?>
        </div>
    </div>



    <footer>
        <p>&copy; 2025 Etravia |</p><?php echo '<a href="world-pages/contact.php">'.$lang['ticket_1'].'</a>' ?>
    </footer>
    <script src="script/index-script.js"></script>
</body>
</html>