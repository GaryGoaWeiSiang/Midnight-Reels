<?php
session_start();
include("config.php");

$videoID = (int)$_POST["videoID"];
$quantity = (int)$_POST["quantity"];

$sql = "SELECT COUNT(*) AS available
        FROM Inventory
        WHERE videoID = ?
        AND inventoryStatus = 'AVAILABLE';
        ";

$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $videoID);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
$row = mysqli_fetch_assoc($result);

$available = (int)$row["available"];

$cartQuantity = 0;
foreach($_SESSION["cart"] as $item){
    if($item["videoID"] == $videoID){
        $cartQuantity += $item["quantity"];
    }
}

if($cartQuantity + $quantity > $available){
    echo "Item added exceeds availability!<br>Current availability: $available <br>Quantity in cart: $cartQuantity";
}
else{
    echo "OK";
}

?>