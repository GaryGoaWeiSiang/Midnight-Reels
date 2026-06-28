<?php 
session_start();
include("config.php");
if($_SERVER["REQUEST_METHOD"] == "POST"){
    $videoName = $_POST['videoName'];
    $videoDescription = $_POST['videoDescription'];
    $genre = $_POST['videoGenre'];
    $videoDuration = $_POST['videoDuration'];
    $videoReleaseDate = $_POST['videoReleaseDate'];
    $videoRentalPrice = $_POST['videoRentalPrice'];
    $videoImage = $_POST['imagePath'];
    $videoStatus = $_POST['videoStatus'] ?? "AVAILABLE";

    if($videoImage == "") $videoImage = "./img/default.png";

    mysqli_begin_transaction($conn);
    try{
        $sql_add = "INSERT INTO VideoTape (videoName, videoDescription, videoGenre, videoDuration, videoReleaseDate, videoRentalPrice, videoImage, videoStatus) 
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
                $stmt = mysqli_prepare($conn, $sql_add);
                mysqli_stmt_bind_param($stmt, "sssssdss", $videoName, $videoDescription, $genre, $videoDuration, $videoReleaseDate, $videoRentalPrice, $videoImage, $videoStatus);
                if(mysqli_stmt_execute($stmt)){
                mysqli_commit($conn);
                $_SESSION["success"] = "New video tape record added successfully!";
                header("Location: ListOfTapes.php");
                exit();
                }
                else{
                    throw new Exception("Error adding Video Tape: ".mysqli_error($conn));
                }
    }catch(Exception $e){
        mysqli_rollback($conn);
        $_SESSION["error"] = $e->getMessage();
        header("Location: ListOfTapes.php");
        exit();
    }
}else{
    $_SESSION["error"] = "You are not a staff!";
    header("Location: index.php");
    exit();
}

?>