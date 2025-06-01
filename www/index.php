<?php
    
    session_start();
    include "config.php";
    include "functions/indexfunctions.php";

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Etravia</title>
    <link href="css/index-styles.css" rel="stylesheet">
    
    <link rel="icon" href="assets/icon/icon.png">
</head>
<body>
    
    <div class="menu">
        <div class="menuHeader">
            <div id="login">
                <h2><?php echo $lang["login_1"]?></h2>
            </div>
            <div id="register">
                <h2><?php echo $lang["register_1"]?></h2>
            </div>
        </div>
        <div id="loginBox">
            <?php
                echo '<form method="post">
                        '; echo '<h2>'.$lang["login_1"].'</h2>'; echo '
                        <div class="form">
                            <img src="assets/icon/login_user.png" width=20px heigth=20px>
                            <input type="text" name="username" placeholder = "'; echo $lang["login_2"]; echo '" required><br>
                        
                            <img src="assets/icon/login_pass.png" width=20px heigth=20px>
                            <input type="password" name="password" placeholder = "********"required><br>
                        
                            <button type="submit" class="formbutton" name="formdata" value="LOGIN">'; echo $lang["login_4"]; echo '</button>
                        </div>
                    </form>';
            ?>
        </div>

        <div id="registerBox">
            <?php
                echo '<form method="post">
                        '; echo '<h2>'.$lang["register_1"].'</h2>'; echo '
                        <div class="form">
                            <img src="assets/icon/login_user.png" width=20px heigth=20px>
                            <input type="text" name="username" placeholder = "'; echo $lang["login_2"]; echo '" required><br>
                        
                            <img src="assets/icon/login_pass.png" width=20px heigth=20px>
                            <input type="password" name="password" placeholder = "********"required><br>

                            <img src="assets/icon/login_pass.png" width=20px heigth=20px>
                            <input type="password" name="confirmpassword" placeholder = "********"required><br>

                            <img src="assets/icon/email.png" width=20px heigth=20px>
                            <input type="text" name="email" placeholder = "E-Mail"required><br>

                            <img src="assets/icon/birthday.png" width=20px heigth=20px>
                            <input type="date" name="birthdate" placeholder = "********"required><br>
                            
                            <button type="submit" class="formbutton" name="formdata" value="REGISTER">'; echo $lang["login_4"]; echo '</button>
                        </div>
                    </form>';
            ?>
        </div>
    </div>

    <div class="news">
        
    </div>

    <footer>
        <p>&copy; 2025 Etravia</p>
    </footer>
    <script src="script/index-script.js"></script>
</body>
</html>