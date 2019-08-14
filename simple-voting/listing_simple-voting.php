<?php 
    include("../db-connect.php");
    $query = "SELECT id, umfragename FROM settings_simple";
    $result = $mysqli->query($query);
    if($result){
        while($obj = mysqli_fetch_object($result)){
            $voting_ids[] = $obj->id;
            $voting_name[] = $obj->umfragename;            
        }
    }else{
            die('Error: ' . $mysqli->errno . ": " . $mysqli->error);
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Alle laufenden einfachen Votings</title>
    <link rel="stylesheet" type="text/css" href="../master.css">
    <link rel="stylesheet" type="text/css" href="../shift-2017_grid.css">
</head>

<body>
    <nav class="navbar">
        <div class=container>
            <div class="row">
                <div class="navbar-logo">
                    <img alt="SÜDKURIER" src="https://cdn.suedkurier.de/content/images/logos/SK_LOGO_schwarz.svg">
                    <span>Vote</span>
                </div>

            </div>
        </div>
    </nav>
    <div class="container">
        <div class="row">
            <h1 class="col-12">Aktuelle einfache Abstimmungen</h1>
            <ul class="col-12">
                <?php
                if(count($voting_ids) > 0){
                    foreach($voting_ids as $index => $voting_id){
                        echo "<li class='voting-li'>";
                        echo "<a class='' href='simple-voting.php?umfrageid=" . $voting_id . "'>";
                        echo $voting_name[$index];
                        echo "</a>";
                        echo "</li>";
                    }
                }else{
                    echo "<h2>Derzeit keine laufenden Abstimmungen<h2><br>";
                    echo "<a class='bt' href='settings_simple-voting.php' title='Einstellungen einfache Abstimmung'>Abstimmung erstellen</a>";
                }
                ?>
            </ul>
        </div>
    </div>
</body>

</html>
