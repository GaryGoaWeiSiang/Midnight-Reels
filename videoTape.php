<?php
    session_start();
    include("config.php");

    if(!isset($_SESSION["userID"])){
    $_SESSION["error"] = "Please Login Before Proceeding.";
    header("Location: index.php");
    exit();
    }

    $id = $_GET["id"];

    $sql = "SELECT vt.videoID, vt.videoName, vt.videoRentalPrice, vt.videoImage, vt.videoGenre, vt.videoDescription, vt.videoDuration, vt.videoReleaseDate,
                    COUNT(i.inventoryID) AS availableTape
                FROM VideoTape vt
                LEFT JOIN Inventory i
                    ON vt.videoID = i.videoID
                    AND i.inventoryStatus = 'AVAILABLE'
                WHERE vt.videoID = ?
                GROUP BY
                    vt.videoID,
                    vt.videoName,
                    vt.videoRentalPrice,
                    vt.videoImage";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt,"i", $id);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    $video = mysqli_fetch_assoc($result);
?>

<!DOCTYPE HTML>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $video['videoName']." Details" ?></title>
    <link rel="stylesheet" type="text/css" href="website.css">
    <link rel="stylesheet" type="text/css" href="videoTape.css">
    <script src="website.js" defer></script>
</head>
<body>
    <header>
        <div id="titleImg"><img src="./img/WebsiteLogo.png" alt="Midnight Reels Logo"></div>
        <nav class ="navigation">
            <button class="navButton" onclick="location.href = 'index.php'">Home</button>
            <button class="navButton" onclick="location.href = 'rentalStatus.php'">Rental Status</button>
            <a href="profile.php" class="profile_button">
                <img src = './img/HomeProfile.png' class = 'logo' alt="Profile Button" title="Go to Profile">
            </a>
        </nav>
    </header>
    <main class="content">
        <div class="pageHeader">
            <button class="backButton" onclick="history.back()" alt="Back Button" title="Go Back">
            <img src="./img/back_button.png" alt="Back Button"></button>
        </div>
    <?php 
        echo "<div class='videoImage'><img src='".$video['videoImage']."' alt='".$video['videoName']."' title='".$video['videoName']." Cover Art'></div>";
        echo "<div class='videoName'><h2>".$video['videoName']."</h2></div>";
        echo "<div class='description'><p>".$video['videoDescription']."</p></div>";
        echo "<div class='videoDetails'>";
        echo "<div class='videoGenre'><h3><b>Video Genre</b></h3><p>".$video['videoGenre']."</p></div>";
        echo "<div class='videoDuration'><h3><b>Video Duration</b></h3><p>".$video['videoDuration']."</p></div>";
        echo "<div class='videoReleaseDate'><h3><b>Video Release Date</b></h3><p>".$video['videoReleaseDate']."</p></div>";
        echo "</div>";
        echo "<div class='videoDetails'>";
        echo "<div class='videoPrice'><h3><b>Rental Price</b></h3><p>RM ".$video['videoRentalPrice']."/day</p></div>";
        echo "<div class='videoAvailable'><h3><b>Available</b></h3><p>".$video['availableTape']."</p></div>";
        echo "</div>";
    ?>
    <div class="cartForm">
    <form action="addCart.php" method="POST" class="rentalDetailForm" onSubmit="return checkInventory(this)">
        <input type="hidden" name="videoID" value="<?php echo $id?>">

        <div class="formTitle">
            <label><b>Rental</b></label>
        </div>

        <div class="formInputArea">

        <div class="formInput">
            <div class="formLabel">
                <label>Quantity</label>
            </div>
            <div class="formEnter">
            <input type="number" name="quantity" min="1" value="1">
            </div>
        </div>

        <div class="formInput">
            <div class="formLabel">
                <label>Rental Duration (Days)</label>
            </div>
            <div class="formEnter">
            <input type="number" name="rentalDuration" min="1" value="1">
            </div>
        </div>

        </div>

        <div class="formButton">
            <button type="submit">Add to Cart</button>
        </div>
    </form>
    </div>
    </main>
    <div id="overlay">
        <div id="errorBox" class="errorBox">
            <div id="errorText"></div>
            <div class="errorBoxButton">
                <button id="closeButton" onclick="closeError()">Cancel</button>
                <button id="confirmButton" onclick="confirmAction()">Confirm</button>
            </div>
        </div>
    </div> 
</body>
</html>

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