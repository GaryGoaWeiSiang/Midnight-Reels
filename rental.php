<?php
    session_start();
    include("config.php");

    if (!isset($_SESSION["userID"])) {
        $_SESSION["error"] = "Please Login first before proceeding.";
        header("Location: index.php");
        exit();
    }

    $search = "";

    if(isset($_GET['search'])){
        $search = trim($_GET['search']);
    }
    if($search == ""){
        $sql = "SELECT vt.videoID, vt.videoName, vt.videoRentalPrice, vt.videoImage,
                    COUNT(i.inventoryID) AS availableTape
                FROM VideoTape vt
                LEFT JOIN Inventory i
                    ON vt.videoID = i.videoID
                    AND i.inventoryStatus = 'AVAILABLE'
                WHERE vt.videoStatus = 'AVAILABLE'
                GROUP BY
                    vt.videoID,
                    vt.videoName,
                    vt.videoRentalPrice,
                    vt.videoImage
                ORDER BY vt.videoName";
        $stmt = mysqli_prepare($conn, $sql);
    }else{
        $sql = "SELECT vt.videoID, vt.videoName, vt.videoRentalPrice, vt.videoImage,
                    COUNT(i.inventoryID) AS availableTape
                FROM VideoTape vt
                LEFT JOIN Inventory i
                    ON vt.videoID = i.videoID
                    AND i.inventoryStatus = 'AVAILABLE'
                WHERE vt.videoName LIKE ? AND vt.videoStatus = 'AVAILABLE'
                GROUP BY
                    vt.videoID,
                    vt.videoName,
                    vt.videoRentalPrice,
                    vt.videoImage
                ORDER BY vt.videoName";
        $stmt = mysqli_prepare($conn, $sql);
        $searchWord = "%".$search."%";
        mysqli_stmt_bind_param($stmt,"s", $searchWord);
    }

    mysqli_stmt_execute($stmt);

    $query = mysqli_stmt_get_result($stmt);

    $totalCartItem = 0;

    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    if(isset($_SESSION['cart'])){
        $totalCartItem = count($_SESSION['cart']);
    }
    else{
        $totalCartItem = 0;
    }
?>

<!DOCTYPE HTML>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Midnight Reels: Rental Page</title>
    <link rel="stylesheet" type="text/css" href="website.css">
    <link rel="stylesheet" type="text/css" href="rental.css">
    <script src="website.js"></script>
</head>
<body>
    <header>
        <div id="titleImg"><img src="./img/WebsiteLogo.png" alt="Midnight Reels Logo"></div>
        <nav class ="navigation">
            <button onclick="location.href ='index.php'" class="navButton" alt="Home Button" title="Go to Home">Home</button>
            <button onclick="location.href ='rentalStatus.php'" class="navButton">Rental Status</button>
            <a href="profile.php" class="profile_button">
                <img src = './img/HomeProfile.png' class = 'logo' alt="Profile Button" title="Go to Profile">
            </a>
        </nav>
    </header>
    <main class="content">
        <div class="pageHeader">
        <div class="pageTitle"><h2>Rental</h2></div>
        <div class="searchBox">
            <form method="GET">
                <input id="searchBox" type="text" name="search" value="<?php echo htmlspecialchars($search);?>" title="Search Box" placeholder="Search Video Tape">
                <button id="searchButton" type="submit" title="Search"><img src="./img/search.png" alt="Search"></button>
            </form>
        </div>
        </div>
        <div class="videoList">
        <?php 
        if($query->num_rows > 0){
            while($row = mysqli_fetch_array($query)){
                echo "<div class=video>";
                echo "<a class='imageLink' href='videoTape.php?id=$row[videoID]'><img class='videoTape' src='$row[videoImage]' alt='".$row['videoName']."' title='".$row['videoName']." Cover Art'></a>";
                echo "<div class='videoName'><h3>".$row['videoName']."</h3></div>";
                echo "<div class='price'><h4>RM ".$row['videoRentalPrice']."/day</h4></div>";
                echo "<div class='inventoryQuantity'><p>Available: ".$row['availableTape']."</p></div>";
                echo "<a class='viewButtonLink' href='videoTape.php?id=$row[videoID]'><div class='viewButton'>View Details</div></a>";
                echo "</div>";
            }
        }
        else{
            echo "<div id='noVideoError'><h2>No tapes found.</h2></div>";
        }
        ?>
        </div>
    </main>
<footer class="cart">
    <div class="itemAmount"><h3>Total Item in Cart: <?php echo $totalCartItem?></h3></div>
    <button class="cartIcon" onclick="location.href='cart.php'" title="Go To Cart"><img src="./img/cart.png" alt="Cart"></button>
</footer>
</body>
    <script>
    document.addEventListener("DOMContentLoaded", function () {
        reloadOnBack()});
    </script> 
</html>