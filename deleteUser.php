<?php
session_start();   
require_once("config.php");

if (isset($_POST['id'])&&$_SESSION["staffRole"]=="ADMIN") {
    $id = $_POST['id'];
    if($_SESSION['userID']==$id){
        $_SESSION["error"] = "Cannot delete currently login account!";
        header("Location: ListOfUser.php");
        exit();
    }

    mysqli_begin_transaction($conn);

    try{
    $sql = "UPDATE Users SET userStatus = 'DELETED' WHERE userID = '$id'";

    if (mysqli_query($conn, $sql)) {
        mysqli_commit($conn);
        $_SESSION["success"] = "User deleted successfully!";
        header("Location: ListOfUser.php");
        exit();
    } else {
        echo "Error in deleting user: " . mysqli_error($conn);
    }
    } catch (mysqli_sql_exception $e) {
        $_SESSION["error"] = $e->getMessage();
        header("Location: ListOfUser.php");
        exit();
    }
}else {
    $_SESSION["error"] = "You are not an admin!";
    header("Location: index.php");
    exit();
}
?>