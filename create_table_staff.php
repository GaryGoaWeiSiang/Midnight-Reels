<?php

require_once("config.php");

$tableName = "Staff";
$sql = "CREATE TABLE $tableName (
userID INT NOT NULL,
staffID VARCHAR(50) UNIQUE NOT NULL,
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