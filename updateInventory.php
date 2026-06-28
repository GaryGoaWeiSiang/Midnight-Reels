<?php
session_start();
require_once("config.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $inventoryID = $_POST['inventoryID'];
    $videoID = $_POST['videoID'];
    $status = $_POST['inventoryStatus'];

    mysqli_begin_transaction($conn);
    try{

    $sql = "UPDATE Inventory SET videoID='$videoID', inventoryStatus='$status' WHERE inventoryID='$inventoryID'";
    
    if (mysqli_query($conn, $sql)) {
        mysqli_commit($conn);
        $_SESSION["success"] = "Edit successfully!";
        header("Location: ListOfInventory.php");
        exit();
    } else {
        throw new Exception("Error in updating: ". mysqli_error($conn));
    }
    }catch(Exception $e){
        mysqli_rollback($conn);
        $_SESSION["error"] = $e->getMessage();
        header("Location: ListOfUser.php");
        exit();
    }
}
?>