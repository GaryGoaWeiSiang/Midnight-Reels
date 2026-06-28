<?php

require_once("config.php");

$tableName = "Users";
$sql = "CREATE TABLE $tableName (
userID INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
username VARCHAR(100) NOT NULL,
emailAddress VARCHAR(100) UNIQUE NOT NULL,
phoneNumber VARCHAR(15) UNIQUE NOT NULL,
password VARCHAR(255) NOT NULL,
role ENUM('CUSTOMER', 'STORE MANAGER', 'ADMIN') NOT NULL,
accountCreationDate DATE NOT NULL DEFAULT (CURDATE()),
userStatus ENUM('ACTIVE','DELETED') NOT NULL DEFAULT 'ACTIVE'
)";

if(mysqli_query($conn, $sql)){
    echo "Table ".$tableName," created successfully!";
}
else{
    echo "Error when creating table ".$tableName.": ".mysqli_error($conn);
}

?>