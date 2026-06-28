<?php

$servername = "localhost";
$username = "root";
$password = "";

$conn = mysqli_connect($servername, $username, $password);

if(!$conn){
    die("Connection failed: " . mysqli_connect_error());
}


$dbName = "MidnightReels";
$sql = "CREATE DATABASE $dbName";
if(mysqli_query($conn, $sql)){
    echo "Database ". $dbName ." created successfully!";
}else{
    echo "Database ".$dbName."encountered an error when creating: ". mysqli_error($conn);
}

require_once("create_table_users.php");
require_once("create_table_customer.php");
require_once("create_table_staff.php");
require_once("create_table_videoTape.php");
require_once("create_table_inventory.php");
require_once("create_table_rental.php");
require_once("create_table_rentalItem.php");
require_once("create_table_payment.php");

mysqli_close($conn);
?>