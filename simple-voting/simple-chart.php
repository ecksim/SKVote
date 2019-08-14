<?php
include("../db-connect.php");

$query = "SELECT umfrageid, ausgewaehlt FROM simple_result WHERE umfrageid = '" . $_GET['umfrageid'] . "'";
$result = $mysqli->query($query);
if($result){
    while($obj = mysqli_fetch_object($result)){
        $voted_for_string[] = $obj->ausgewaehlt;
    };
}else{
    echo "Error1: {$mysqli->errno} : {$mysqli->error}";
}
$query = "SELECT umfragename, auswahl FROM settings_simple WHERE id ='" . $_GET['umfrageid'] . "'";
$result = $mysqli->query($query);
if($result){
    $obj = mysqli_fetch_object($result);
    $voting_options_string = $obj->auswahl;
    $voting_name = $obj->umfragename;
}else{
    echo "Error2: {$mysqli->errno} : {$mysqli->error}";
}
$voting_options = explode("*", $voting_options_string);
$count_votes_total = 0;
for($i=0; $i<count($voting_options); $i++){
    $count_votes_for[$i] = 0;
    for($j=0; $j<count($voted_for_string); $j++){
        $voted_for = explode("*", $voted_for_string[$j]); 
        for($k=0; $k<count($voted_for); $k++){
            if($voted_for[$k] == $i){
                $count_votes_for[$i]++; 
                $count_votes_total++;
            }
        }
    }
    $voting_result[$voting_options[$i]] = $count_votes_for[$i];
    //echo "Name: " . $voting_options[$i] . " ";
    //echo "Anzahl Stimmen: " . $count_votes_for[$i];
    //echo "<br>";
}
//echo "Anzahl abgegebener Stimmen: " . $count_votes_total;

for($i=0; $i<count($voting_result); $i++){
    $voting_percent[$i] = $voting_result[$voting_options[$i]] * 100 / $count_votes_total;
}
?>
<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="UTF-8">
    <title>Auswertung</title>
    <link rel="stylesheet" type="text/css" href="../master.css">
    <link rel="stylesheet" type="text/css" href="../shift-2017_grid.css">
    <style>
        .voting-container { 
            position: relative;
            color: #fff;
            text-shadow: -1px 0 black, 0 1px black, 1px 0 black, 0 -1px black;
            width: 100%;
            padding: 20px;
            margin: 10px 0;
        }
        .voting-bar{
            position: absolute;
            top: 0;
            left: 0;
            height: 100%;
            background-color: #006ab2;
            z-index: -1;           
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
                <h1>Auswertung zur Umfrage "<?php echo $voting_name ?>"</h1>
                <?php
                for($i=0; $i<count($voting_result); $i++){
                    $voting_percent[$i] = $voting_result[$voting_options[$i]] * 100 / $count_votes_total;
                    echo "<div class='voting-container'><div class='voting-bar' style='width: " . $voting_percent[$i] . "%'></div>" . $voting_options[$i] . " (" . round($voting_percent[$i]) . "%)</div>";
    }
        ?>
            </div>
        </div>
    </div>
</body>

</html>
