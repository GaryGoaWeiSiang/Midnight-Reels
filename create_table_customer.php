<?php

require_once("config.php");

$tableName = "Customer";
$sql = "CREATE TABLE $tableName (
userID INT NOT NULL,
address VARCHAR(100) NOT NULL,
PRIMARY KEY (userID),
FOREIGN KEY (userID) REFERENCES Users(userID)
)";

if(mysqli_query($conn, $sql)){
    echo "Table ".$tableName," created successfully!";
}
else{
    echo "Error when creating table ".$tableName.": ".mysqli_error($conn);
}
?>