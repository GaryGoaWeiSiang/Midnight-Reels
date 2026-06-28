<?php

require_once("config.php");

$tableName = "RentalItem";
$sql = "CREATE TABLE $tableName (
rentalItemID INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
rentalDuration INT NOT NULL,
dueRentalDate DATE NOT NULL,
actualReturnDate DATE,
rentalID INT NOT NULL,
inventoryID INT NOT NULL,
FOREIGN KEY (rentalID) REFERENCES Rental (rentalID),
FOREIGN KEY (inventoryID) REFERENCES Inventory (inventoryID)
)";

if(mysqli_query($conn, $sql)){
    echo "Table ".$tableName," created successfully!";
}
else{
    echo "Error when creating table ".$tableName.": ".mysqli_error($conn);
}

?>