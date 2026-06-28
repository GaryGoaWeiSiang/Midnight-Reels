<?php
session_start();
require_once("config.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $videoID = $_POST['videoID'];
    $videoName = $_POST['videoName'];
    $videoDescription = $_POST['videoDescription'];
    $genre = $_POST['videoGenre'];
    $videoDuration = $_POST['videoDuration'];
    $videoReleaseDate = $_POST['videoReleaseDate'];
    $videoRentalPrice = $_POST['videoRentalPrice'];
    $videoStatus = $_POST['videoStatus'];
    $imagePath = $_POST['imagePath'] ?? ""; 

    if($imagePath == "") $imagePath = "./img/default.png";

    try{
    $sql = "UPDATE VideoTape SET 
                videoName = ?, 
                videoDescription = ?, 
                videoGenre = ?, 
                videoDuration = ?, 
                videoReleaseDate = ?, 
                videoRentalPrice = ?, 
                videoImage = ?,
                videoStatus = ? 
                WHERE videoID = ?";
    $stmt = mysqli_prepare($conn, $sql);

            
    if ($stmt) {
        mysqli_stmt_bind_param(
                $stmt, 
                "ssssssssi", 
                $videoName, 
                $videoDescription, 
                $genre, 
                $videoDuration, 
                $videoReleaseDate, 
                $videoRentalPrice, 
                $imagePath, 
                $videoStatus, 
                $videoID
            );
        if (mysqli_stmt_execute($stmt)) {
                mysqli_commit($conn);
                $_SESSION["success"] = "Tape edit successfully!";
                mysqli_stmt_close($stmt);
                header("Location: ListOfTapes.php");
                exit();
            } else {
                throw new Exception(mysqli_stmt_error($stmt));
            }
    } else {
        throw new Exception(mysqli_error($conn));
    }
    }catch(Exception $e){
        mysqli_rollback($conn);
        $_SESSION["error"] = "Error in updating: ". $e->getMessage();
        header("Location: ListOfTapes.php");
        exit();
    }

}
?>