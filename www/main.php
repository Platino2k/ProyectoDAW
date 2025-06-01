<?php
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
        <p>&copy; 2025 Etravia</p>
    </footer>
    <script src="script/index-script.js"></script>
</body>
</html>