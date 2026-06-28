<?php 
session_start();
include("config.php");

$rentalID = $_POST["rentalID"];
$inventoryID = $_POST["inventoryID"];

mysqli_begin_transaction($conn);

try {

    $sql = "
        UPDATE rentalItem
        SET actualReturnDate = CURDATE()
        WHERE rentalID = ?
        AND inventoryID = ?
    ";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ii", $rentalID, $inventoryID);
    mysqli_stmt_execute($stmt);

    $sql = "
        UPDATE inventory
        SET inventoryStatus = 'Available'
        WHERE inventoryID = ?
    ";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $inventoryID);
    mysqli_stmt_execute($stmt);

    mysqli_commit($conn);

    $_SESSION["success"] = "Return successful!";
    header("Location: RentalStatus.php");
    exit();

} catch (Exception $e) {

    mysqli_rollback($conn);
    $_SESSION["error"] = "Return failed!";
    exit();

}
?>