<?php
    session_start();
    include("config.php");
    mysqli_begin_transaction($conn);

    try {
        if(empty($_SESSION["cart"])){
            throw new Exception("The cart is empty!");
        }
        if(!isset($_POST["paymentMethod"])){
            throw new Exception("No payment method chosen!");
        }
        $userID = $_SESSION["userID"];
        $paymentMethod = $_POST["paymentMethod"];
    
        $sql = "INSERT INTO Rental(userID, rentalBeginDate)
                VALUES (?,CURDATE())";
            
        $stmt  = mysqli_prepare($conn,$sql);
        mysqli_stmt_bind_param($stmt,"i",$userID);
        mysqli_stmt_execute($stmt);
    
        $rentalID = mysqli_insert_id($conn);

        foreach($_SESSION["cart"] as $item){
            $videoID = $item["videoID"];
            $qty = $item["quantity"];
            $rentalDuration = $item["rentalDuration"];

            for ($i = 0; $i < $qty; $i++) {
                $sql = "SELECT inventoryID
                        FROM Inventory
                        WHERE videoID = ? AND inventoryStatus ='AVAILABLE'
                        LIMIT 1";
                $stmt = mysqli_prepare($conn, $sql);
                mysqli_stmt_bind_param($stmt, "i", $videoID);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);
                
                if (!$inv = mysqli_fetch_assoc($result)) {
                    throw new Exception("Not enough stock for videoID $videoID");
                }

                $inventoryID = $inv["inventoryID"];

                $sql = "UPDATE Inventory
                        SET inventoryStatus = 'RENTED'
                        WHERE inventoryID = ?";
                
                $stmt = mysqli_prepare($conn, $sql);
                mysqli_stmt_bind_param($stmt, "i", $inventoryID);
                mysqli_stmt_execute($stmt);

                $sql = "INSERT INTO RentalItem (
                        rentalDuration,
                        dueRentalDate,
                        rentalID,
                        inventoryID
                    )
                    VALUES (?, DATE_ADD(CURDATE(), INTERVAL ? DAY), ?, ?)";

                $stmt = mysqli_prepare($conn, $sql);
                mysqli_stmt_bind_param($stmt, "iiii",
                    $rentalDuration,
                    $rentalDuration,
                    $rentalID,
                    $inventoryID
                );

                mysqli_stmt_execute($stmt);
            }
        }

        $totalAmount = 0;

        foreach($_SESSION["cart"] as $item){

        $videoID = $item["videoID"];
        $qty = $item["quantity"];
        $duration = $item["rentalDuration"];

    
        $sql = "SELECT videoRentalPrice
                FROM VideoTape
                WHERE videoID=?";

        $stmt = mysqli_prepare($conn,$sql);
        mysqli_stmt_bind_param($stmt,"i",$videoID);
        mysqli_stmt_execute($stmt);

        $video = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

        if(!$video){
            throw new Exception("Video not found.");
        }

        $price = $video["videoRentalPrice"];
        

        $totalAmount += $price * $qty * $duration;
        }

        $sql = "INSERT INTO Payment 
        (paymentAmount, paymentMethod, paymentStatus, rentalID)
        VALUES (?, ?, 'PAID', ?)";

        $stmt = mysqli_prepare($conn, $sql);
        if(!$stmt){
            throw new Exception(mysqli_error($conn));
        }
        mysqli_stmt_bind_param(
            $stmt,
            "dsi",
            $totalAmount,
            $paymentMethod,
            $rentalID
        );

        if(!mysqli_stmt_execute($stmt)){
            throw new Exception(mysqli_stmt_error($stmt));
        }

        mysqli_commit($conn);
        unset($_SESSION["cart"]);
        $_SESSION["success"] = "Payment success!";
        header("Location: rentalStatus.php");
        exit();

    }catch(Exception $e){
        mysqli_rollback($conn);
        $_SESSION["error"] = "Checkout failed: " . $e->getMessage();
        header("Location: cart.php");
        exit();
}
?>