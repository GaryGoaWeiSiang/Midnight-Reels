<?php

require_once("config.php");

$tableName = "Rental";
$sql = "CREATE TABLE $tableName (
rentalID INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
rentalBeginDate DATE NOT NULL DEFAULT (CURDATE()),
userID INT NOT NULL,
FOREIGN KEY (userID) REFERENCES Customer(userID)
)";

if(mysqli_query($conn, $sql)){
    echo "Table ".$tableName," created successfully!";
}
else{
    echo "Error when creating table ".$tableName.": ".mysqli_error($conn);
}

?>