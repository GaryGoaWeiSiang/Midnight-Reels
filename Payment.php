<?php
    session_start();
    
    if (empty($_SESSION["cart"])) {
        $_SESSION["error"] = "The cart is empty!";
        header("Location: cart.php");
        exit();
    }
    
    include("config.php");

    $userID = $_SESSION['userID'];
    // $rentalID = $_SESSION["cart"]["rentalID"];

    // $sql = "SELECT paymentStatus
    //         FROM payment
    //         WHERE rentalID = ?
    //         ";
            
    // $stmt = mysqli_prepare($conn, $sql);
    // mysqli_stmt_bind_param($stmt, "i", $rentalID);
    // mysqli_stmt_execute($stmt);
    // $result = mysqli_stmt_get_result($stmt);

    $totalPrice = 0;

        // $sql = "SELECT r.rentalID
        //         FROM Rental r
        //         JOIN Payment p ON p.rentalID = r.rentalID 
        //         WHERE userID = ?
        //         ORDER BY rentalID DESC
        //         LIMIT 1";

        // $stmt = mysqli_prepare($conn, $sql);
        // mysqli_stmt_bind_param($stmt, "i", $userID);
        // mysqli_stmt_execute($stmt);

        // $result = mysqli_stmt_get_result($stmt);
        // $row = mysqli_fetch_assoc($result);
    foreach($_SESSION['cart'] as $index => $item){
                $videoID = $item['videoID'];
                $sql = "SELECT videoName, videoRentalPrice, videoImage
                        FROM VideoTape
                        WHERE videoID = ?";
                $stmt = mysqli_prepare($conn, $sql);
                mysqli_stmt_bind_param($stmt,"i", $videoID);
                mysqli_stmt_execute($stmt);

                $result = mysqli_stmt_get_result($stmt);
                $subtotal = 0;

                if($video = mysqli_fetch_assoc($result)){
                    $subtotal = $video['videoRentalPrice'] * $item['quantity'] * $item['rentalDuration'];
                }
                $totalPrice += $subtotal;
    }
        

    // $sql = "SELECT ri.rentalDuration,
    //             v.videoRentalPrice
    //         FROM RentalItem ri
    //         JOIN Inventory i ON ri.inventoryID = i.inventoryID
    //         JOIN VideoTape v ON i.videoID = v.videoID
    //         WHERE ri.rentalID = ?";

    // $stmt = mysqli_prepare($conn, $sql);
    // mysqli_stmt_bind_param($stmt, "i", $rentalID);
    // mysqli_stmt_execute($stmt);

    // $result = mysqli_stmt_get_result($stmt);

    // while ($row = mysqli_fetch_assoc($result)) {
    //     $totalPrice += $row['videoRentalPrice'] * $row['rentalDuration'];
    // }
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Midnight Reels - Payment Page</title>

    <link rel='stylesheet' type='text/css' href='website.css'/>
    <link rel='stylesheet' type='text/css' href='Payment.css'/>
    <script src ='website.js' defer></script>
    <script src='payment.js' defer></script>

</head>

<body>
    <header>
        <div id="titleImg"><img src="./img/WebsiteLogo.png" alt="Midnight Reels Logo"></div>
    </header>

    <main class = "paymentContainer">
        <div class="pageHeader">
            <button class="backButton" onclick="history.back()" alt="Back Button" title="Go Back">
            <img src="./img/back_button.png"></button>
        </div>

        <section class = "paymentMain">

            <div id="paymentTitle"><h2>Payment Details</h2></div>

            <div><h2>Total: RM <?php echo number_format($totalPrice,2)?></h2></div>

            <h2>We Accept These:</h2>

                    <form method="POST" action="addRentalAndPayment.php" class = "paymentForm" >

                        <div class = "paymentMethodContainer">

                            <div class="paymentMethodImage" data-payment= "CASH">
                                <img src="./img/cash.png"   class = "paymentImage" alt="Cash Button" title="Pay by Cash">
                            </div>

                            <div class="paymentMethodImage" data-payment= "CARD">
                                <img src="./img/card.png"   class = "paymentImage" alt="Card Button" title="Pay by Card">
                            </div>

                            <div class="paymentMethodImage" data-payment= "QR">
                                <img src="./img/qr.png" class = "paymentImage" alt="QR Button" title="Pay by QR">
                            </div>

                        </div>

                        <input type="hidden" name="paymentMethod" id="paymentMethod">
                            
                            <div class="paymentSubmit">
                                <button type="submit" id="paymentSubmitButton" alt="Payment Button" title="Confrm Payment">Confirm Payment</button>
                            </div>

                    </form>
            
                
        </section>
        
    </main>

</body>
        <script>
            document.addEventListener("DOMContentLoaded", function () {
                reloadOnBack();
            });
        </script>
</html>

