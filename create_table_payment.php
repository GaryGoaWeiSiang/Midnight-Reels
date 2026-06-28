<?php

require_once("config.php");

$tableName = "Payment";
$sql = "CREATE TABLE $tableName (
paymentID INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
paymentAmount DECIMAL(10, 2) NOT NULL,
paymentMethod ENUM('CASH','CARD','QR'),
paymentDateTime DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
paymentStatus ENUM('NOT PAID', 'PAID'),
rentalID INT NOT NULL,
FOREIGN KEY (rentalID) REFERENCES Rental(rentalID)
)";

if(mysqli_query($conn, $sql)){
    echo "Table ".$tableName," created successfully!";
}
else{
    echo "Error when creating table ".$tableName.": ".mysqli_error($conn);
}
?>