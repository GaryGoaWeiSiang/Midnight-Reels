<?php
session_start();
include("config.php");

if(!isset($_SESSION["cart"])){
    $_SESSION["cart"] = [];
}

$videoID = (int)$_POST["videoID"];
$quantity = (int)$_POST["quantity"];
$rentalDuration = (int)$_POST["rentalDuration"];


if($quantity <= 0){
    $quantity = 1;
}

if($rentalDuration <= 0){
    $rentalDuration = 1;
}

$found = false;

foreach($_SESSION["cart"] as &$item){
    if(
        $item['videoID'] == $videoID &&
        $item['rentalDuration'] == $rentalDuration
    ){
        $item['quantity'] += $quantity;

        $found = true;
        break;
    }
}

unset($item);

if(!$found){
    $_SESSION['cart'][] = [
        "videoID" => $videoID,
        "rentalDuration" => $rentalDuration,
        "quantity"=> $quantity
    ];
}

header("Location: rental.php");
exit;

?>