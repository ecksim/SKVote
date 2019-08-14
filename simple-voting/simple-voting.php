<?php
include("../db-connect.php");
$query = "SELECT umfragename, beschreibung, auswahl, userstimmen FROM settings_simple WHERE id = '" .  $_GET['umfrageid'] . "'";
$result = $mysqli->query($query);
if($result){
    $obj = mysqli_fetch_object($result);
    $field_string = $obj->auswahl;     
    $voting_name = $obj->umfragename;
    $description = $obj->beschreibung;
    $votings = $obj->userstimmen;
    }else{
    die("Error: {$mysqli->errno} : {$mysqli->error}");
}
$fields = explode("*", $field_string);
if(!empty($_POST['submit-vote'])){
    foreach ($_POST as $key => $value) {
        if($key != "submit-vote" || $value == "on"){            
            $voted_options[] = $key;
        }
    }
    $number_user_votes = count($voted_options);
    $voted_options_string = implode("*", $voted_options);
    $query = "INSERT INTO simple_result (umfrageid, ausgewaehlt) VALUES ('".$_GET['umfrageid']."', '".$voted_options_string."')";
    $insert = $mysqli->query($query);
    if($insert){
        echo "Danke fürs abstimmen";
    }else{
        echo "Error: ".$mysqli->errno. ": ".$mysqli->error;
    }
}

?>

<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="UTF-8">
    <title>Einfaches Voting</title>
    <link rel="stylesheet" type="text/css" href="../master.css">
    <link rel="stylesheet" type="text/css" href="../shift-2017_grid.css">
    <style>
        .vote-cb {
            display: none;
        }

        .vote-form label {
            position: relative;
            display: block;
            padding: 10px;
            text-align: center;
            background-color: #f7f7f7;
            border: 1px solid #dbdbdb;
            cursor: pointer;
        }

        .vote-form label:hover {
            background-color: #fff;
        }

        .vote-form input[type=checkbox]:checked+label {
            background-color: #006ab2;
            color: #ffffff;
        }

        p {
            margin: 10px 0;
        }

    </style>
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
            <div class="col-12">

                <h1>Einfache Umfrage über <?php echo $voting_name ?></h1>
                <p>Beschreibug:</p>
                <p><?php echo $description ?></p>
                <p>Sie haben <?php echo $votings ?> Stimmen zur Verfügung</p>

                <form method="post" action="" class="vote-form">
                    <ul>
                        <?php
                        
                        for($i=0; $i<count($fields); $i++){
                            echo "<li>";                    
                            echo '<input class="vote-cb" type="checkbox" name="' . $i . '" id="cb' . $i . '">';
                            echo '<label for="cb' . $i . '" onclick="checked(cb' . $i .')">' . $fields[$i]; 
                            echo '</label>';
                            echo "</li>";
                        };
                        ?>
                    </ul>
                    <input type="submit" name="submit-vote" value="Abstimmen">
                </form>
            </div>
        </div>
    </div>
    <script>
        var voteCb = document.getElementsByClassName("vote-cb");

        function checked(cbId) {
            var checkedBoxes = 0;
            for (var i = 0; i < voteCb.length; i++) {
                if (voteCb[i].checked == true) {
                    checkedBoxes++;
                }
            }
            if (checkedBoxes >= <?php echo $votings ?>) {
                cbId.checked = true;
            }
        }

    </script>
</body>

</html>
