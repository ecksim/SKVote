<?php
include("../db-connect.php");
$saved_voting = false;
if(!empty($_POST['set'])){   
        for($i=0; $i<=$_POST['fieldindex']; $i++){
            $field = "field" . ($i+1);
            $fields[] = $_POST[$field];
        }
        $field_string = implode("*",$fields);
        $query = "INSERT INTO settings_simple (umfragename, beschreibung, auswahl, userstimmen) VALUES ('" . $_POST['voting-name'] . "','" . $_POST['description'] . "','" . $field_string . "','" . $_POST['votings'] . "')";
        $insert = $mysqli->query($query);
        if($insert){
            $saved_voting = true;
        }else{
            die("Error: {$mysqli->errno} : {$mysqli->error}");
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
        .error-div {
            background-color: #ff5335;
            color: #fff;
            display: none;
            height: fit-content;
            padding: 20px;
        }

        .success-div {
            background-color: #4bca81;
            color: #fff;
            height: fit-content;
            padding: 20px;
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
                <a href="listing_simple-voting.php" title="Aktive Votings" class="active-votings">Aktive Votings</a>
            </div>
        </div>
    </nav>
    <div class="container">
        <div class="row">
            <header class="col-12">
                <h1>Einfache Abstimmung</h1>
            </header>
            <div class="col-12 col-md-6">
                <form method="post" class="voting-form">

                    <h2>Name des Votings</h2>

                    <input type="text" name="voting-name" id="votingName">

                    <h2>Berschreibung (optional)</h2>

                    <textarea name="description"></textarea>

                    <h2>Geben Sie die Punkte an, die zur Auswahl stehen sollen</h2>

                    <div id="fieldsDiv">
                        <input type="text" id="field1" name="field1">
                        <input type="text" id="field2" name="field2">
                    </div>
                    <input type="hidden" name="fieldindex" value="" id="fieldindex">

                    <input type="button" value="+" id="add">
                    <input type="button" value="-" id="remove"><br><br>

                    <h2>Wie viele Stimmen soll der User vergeben können?</h2>
                    <input type="number" name="votings" id="votings" value="1" min="1" max="2"><br><br>

                    <input type="submit" name="set" id="set" value="Bestätigen">
                </form>
            </div>
            <?php 
            if($saved_voting == true){  
                echo '<div class="col-12 col-md-4">';
                echo '<div class="success-div" id="successDiv">';
                echo "<h4>Die Umfrage wurde gespeichert</h4>";
                echo "</div>";
                echo "</div>";
            }
            ?>
            <div class="col-12 col-md-4">
                <div class="error-div" id="errorDiv">
                    <h4>Fehlermeldungen: </h4><br>
                    <div id="errorMsg"></div>
                </div>
            </div>
        </div>
    </div>

    <script>
        var addButton = document.getElementById("add");
        var removeButton = document.getElementById("remove");
        var set = document.getElementById("set");
        var fields = document.getElementById("fieldsDiv");
        var i = 1;
        var votings = document.getElementById("votings");

        addButton.addEventListener("click", function() {
            i++;
            var newInput = document.createElement("input");
            newInput.setAttribute("type", "text");
            newInput.setAttribute("name", "field" + (i + 1));
            newInput.setAttribute("id", "field" + (i + 1));
            fields.appendChild(newInput);
            votings.setAttribute("max", i + 1);
        });
        removeButton.addEventListener("click", function() {
            if (i > 1) {
                var removeInput = document.getElementById("field" + (i + 1));
                removeInput.parentElement.removeChild(removeInput);
                votings.setAttribute("max", i - 1);
                i--;
            }
        });
        set.addEventListener("click", function(e) {
            document.getElementById("fieldindex").value = i;
            var valide = true;
            var errmsg = "";
            if (document.getElementById("votingName").value == "") {
                errmsg += "- Die Umfrage braucht einen Namen <br>";
                valide = false;
            }
            if (document.getElementById("field1").value == "" || document.getElementById("field2").value == "") {
                errmsg += "- Es müssen mindestens zwei Optionen zu Auswahl stehen";
                valide = false;
            }
            if (!valide) {
                document.getElementById("errorMsg").innerHTML = errmsg;
                var sucmsg = document.getElementById("successDiv");
                if(sucmsg != null){
                document.getElementById("successDiv").style.display = "none";                    
                }
                document.getElementById("errorDiv").style.display = "block";
                e.preventDefault();
            }

        });

    </script>
</body>

</html>
