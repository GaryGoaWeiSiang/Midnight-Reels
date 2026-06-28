<?php

require_once("config.php");

$tableName = "VideoTape";
$sql = "CREATE TABLE $tableName (
videoID INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
videoName VARCHAR(100) NOT NULL,
videoDescription VARCHAR(1000),
videoGenre ENUM('ACTION', 'COMEDY', 'SCI-FI', 'HORROR', 'ROMANCE') NOT NULL,
videoDuration VARCHAR(50) NOT NULL,
videoReleaseDate DATE NOT NULL,
videoRentalPrice DECIMAL(10, 2) NOT NULL,
videoImage VARCHAR(200) NOT NULL DEFAULT './img/default.png',
videoStatus ENUM('AVAILABLE','DELETED') NOT NULL DEFAULT 'AVAILABLE'
)";

if(mysqli_query($conn, $sql)){
    echo "Table ".$tableName," created successfully!";
}
else{
    echo "Error when creating table ".$tableName.": ".mysqli_error($conn);
}

?>