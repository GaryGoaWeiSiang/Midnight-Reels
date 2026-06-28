<?php
session_start();
require_once("config.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $videoID = $_POST['videoID'];
    $status = $_POST['inventoryStatus'];

    mysqli_begin_transaction($conn);
    try{
    $sql = "INSERT INTO Inventory (videoID, inventoryStatus)
             VALUES ('$videoID', '$status')";
    
    if (mysqli_query($conn, $sql)) {
        mysqli_commit($conn);
        $_SESSION["success"] = "New inventory record added successfully!";
        header("Location: ListOfInventory.php");
        exit();
    }else{
        throw new Exception("Error adding Inventory: ".mysqli_error($conn));
    }
    }catch(Exception $e){
        mysqli_rollback($conn);
        $_SESSION["error"] = $e->getMessage();
        header("Location: ListOfInventory.php");
        exit();
    }
}else{
    $_SESSION["error"] = "You are not a staff!";
    header("Location: index.php");
    exit();
}
?>