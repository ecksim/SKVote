<?php
   $mysqli = new mysqli('localhost', 'root', '', 'abstimm_tool');
    if($mysqli->connect_error){
        die('Connect Error: ' . $mysqli->connect_errno . ': ' . $mysqli->connect_error);
    }
?>