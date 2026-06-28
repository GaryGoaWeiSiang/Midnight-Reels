<?php
    session_start();
    if (!isset($_SESSION["userID"])) {
        $_SESSION["error"] = "Please Login first before proceeding.";
        header("Location: rental.php");
        exit();
    }
    include("config.php");
    header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
    header("Cache-Control: post-check=0, pre-check=0", false);
    header("Pragma: no-cache");
    header("Expires: 0");

    $totalCartItem = isset($_SESSION["cart"]) ? count($_SESSION["cart"]) :0;
    $totalPrice = 0;
?>

<!DOCTYPE HTML>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Midnight Reels: Your Cart</title>
    <link rel="stylesheet" type="text/css" href="website.css">
    <link rel="stylesheet" type="text/css" href="cart.css">
    <script src="website.js" defer></script>
</head>
<body>
    <header>
        <div id="titleImg"><img src="./img/WebsiteLogo.png" alt="Midnight Reels Logo"></div>
        <nav class ="navigation">
            <button class="navButton" onclick="location.href = 'index.php'" alt="Home Button" title="Go to Home">Home</button>
            <button class="navButton" onclick="location.href = 'rentalStatus.php'" alt="Rental Status Button" title="Go to Rental Status">Rental Status</button>
            <a href="profile.php" class="profile_button">
                <img src = './img/HomeProfile.png' class = 'logo' alt="Profile Button" title="Go to Profile">
            </a>
        </nav>
    </header>
    <main class="content">
        <div class="pageHeader">
            <button class="backButton" onclick="location.href ='rental.php'" alt="Back Button" title="Back Button">
            <img src="./img/back_button.png" alt="Midnight Reels Logo"></button>
            <div class="pageTitle"><h2>Your Cart</h2></div>
        </div>
        <div class="cartList">
            <?php 
        if(!isset($_SESSION['cart']) || empty($_SESSION['cart'])){
            echo "<div id='cartEmptyError'><h2>No items in cart.</h2></div>";
        }
        else{
            foreach($_SESSION['cart'] as $index => $item){
                $videoID = $item['videoID'];
                $sql = "SELECT videoName, videoRentalPrice, videoImage
                        FROM VideoTape
                        WHERE videoID = ?";
                $stmt = mysqli_prepare($conn, $sql);
                mysqli_stmt_bind_param($stmt,"i", $videoID);
                mysqli_stmt_execute($stmt);

                $result = mysqli_stmt_get_result($stmt);

                if($video = mysqli_fetch_assoc($result)){
                    $subtotal = $video['videoRentalPrice'] * $item['quantity'] * $item['rentalDuration'];

                    echo "<div class='cartItem'><div class='cartMain'>";
                    echo "<div class='cartVideoImage'><img src='".$video["videoImage"]."' alt='".$video["videoName"]."' title='".$video["videoName"]."'></div>";
                    echo "<div class='cartItemDetails'>";
                    echo "<div class='cartVideoName'><h2><b>".$video["videoName"]."</b></h2></div>";
                    echo "<div class='cartVideoPrice'><h3>RM ".$video["videoRentalPrice"]."/day</h3></div>";
                    echo "</div>";   
                    echo "<div class='cartRentalDetail'>";
                    echo "<div class='itemQuantity'><h3><b>Quantity</b><p>".$item["quantity"]."</p></h3></div>";
                    echo "<div class='rentalDuration'><h3><b>Rental Duration</b><p>".$item["rentalDuration"]." day</p></h3></div>";
                    echo "<div class='itemSubtotal'><h3><b>Subtotal</b><p>RM ".number_format($subtotal,2)."</p></h3></div>";
                    echo "</div></div>"; 
                    echo "<form class='cartRemoveItem' action='removeCart.php' method='POST'> 
                            <input type='hidden' name='index' value='$index'>
                            <button type='button' class='cartRemoveButton' onclick='confirmRemove(this)'>
                                <img src='./img/cancel.png'>
                            </button>
                          </form>";
                    echo "</div>";

                    $totalPrice += $subtotal;
                }
            }
        }
        ?>
        </div>
    </main>
    <div class="cart">
        <div class="itemAmount"><h3>Total Item: <?php echo $totalCartItem ?></h3></div>
        <div class="cartPrice"><h3>Total: RM <?php echo number_format($totalPrice,2)?></h3></div>
        <form action="Payment.php" method="POST">
            <button class="payment" type="submit"><img src="./img/checkout.png" alt="Checkout"><p>To Pay</p></button>
        </form>
    </div>
    <div id="overlay">
        <div id="errorBox" class="errorBox">
            <div id="errorText"></div>
            <div class="errorBoxButton">
                <button id="closeButton" onclick="closeError()">Cancel</button>
                <button id="confirmButton" onclick="confirmAction()">Confirm</button>
            </div>
        </div>
    </div>
    <script>
    document.addEventListener("DOMContentLoaded", function () {
        reloadOnBack()});
    </script> 
</body>



<?php
if(isset($_SESSION["error"])){
        ?>
        <script>
            document.addEventListener("DOMContentLoaded", function () {
                showError("<?php echo addslashes($_SESSION["error"]); ?>");
            });
        </script>
    <?php
        unset($_SESSION["error"]);    
    }
?>