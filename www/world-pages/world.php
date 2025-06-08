<?php
    session_start();
    
    include "../param/param.php";
    include "world-top.php";
    include "../functions/worldfunctions.php";
    
?>

<?php

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
            <div class='Resources'>";
                $id=showResources($db,$WORLD);
            echo "</div>
            <div class='background'>";
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