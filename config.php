<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbName = "MidnightReels";

$conn = mysqli_connect($servername, $username, $password, $dbName);

if(!$conn){
    echo("Connection failed: " . mysqli_connect_error());
    exit();
}

?>