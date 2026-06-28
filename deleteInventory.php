<?php
session_start();
require_once("config.php");

if (isset($_POST['id'])&&$_SESSION["role"]=="STAFF") {
    $id = $_POST['id'];

    mysqli_begin_transaction($conn);
    try{
    $sql = "UPDATE Inventory SET inventoryStatus = 'UNAVAILABLE' WHERE inventoryID = '$id'";

    if (mysqli_query($conn, $sql)) {
        mysqli_commit($conn);
        $_SESSION["success"] = "This inventory item has been successfully made unavailable!";
        header("Location: ListOfInventory.php");
        exit();
    } else {
        throw new Exception("Error in deleting video tape: ".mysqli_error($conn));
    }
    } catch (Exception $e) {
        mysqli_rollback($conn);
        $_SESSION["error"] = $e->getMessage();
        header("Location: ListOfInventory.php");
        exit();
    }
}else {
    $_SESSION["error"] = "You are not a staff!";
    header("Location: index.php");
    exit();
}

?>