<?php 
session_start();
include("config.php");
if(empty($_SESSION["role"])|| $_SESSION["role"] != "STAFF"){
    $_SESSION["error"] = "You are not a staff!";
    header("Location: index.php");
    exit();
}

$sql = "SELECT COUNT(userID) AS activeUser FROM Users WHERE userStatus = 'ACTIVE'";
$result = mysqli_query($conn,$sql);
$row = mysqli_fetch_array($result);

$userCount = $row["activeUser"];

$sql = "SELECT COUNT(videoID) AS totalVideo FROM VideoTape";
$result = mysqli_query($conn,$sql);
$row = mysqli_fetch_array($result);

$videoCount = $row["totalVideo"];

$sql = "SELECT COUNT(CASE WHEN inventoryStatus = 'AVAILABLE' THEN 1 END) AS available,
        COUNT(CASE WHEN inventoryStatus = 'RENTED' THEN 1 END) AS rented,
        COUNT(CASE WHEN inventoryStatus = 'BROKEN' THEN 1 END) AS broken
        FROM Inventory";
$result = mysqli_query($conn,$sql);
$row = mysqli_fetch_array($result);

$availableInventoryCount = $row["available"];
$rentedInventoryCount = $row["rented"];
$brokenInventoryCount = $row["broken"];

$sql = "SELECT COUNT(DISTINCT r.rentalID) AS activeRental
        FROM Rental r
        JOIN RentalItem ri ON r.rentalID = ri.rentalID
        WHERE ri.actualReturnDate IS NULL;";
$result = mysqli_query($conn,$sql);
$row = mysqli_fetch_array($result);

$activeRentalCount = $row["activeRental"];
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Midnight Reels - Staff Page</title>

    <link rel='stylesheet' type='text/css' href='website.css'/>
    <link rel='stylesheet' type='text/css' href='staffPage.css'/>
    <script src ='website.js'></script>
    <script src='staffPage.js'></script>

</head>
<body>
    <header>
        <div id="titleImg">
            <img src = "./img/WebsiteLogo.png" class = 'logo' alt="Midnight Reels Logo">
        </div>
        <!--Same line as header, navigation to Rent and Status page-->
        <nav class ="navigation">
                <a href="profile.php" class="profile_button">
                    <img src = './img/HomeProfile.png' class = 'logo' alt="Profile Button" title="Go to Profile">
                </a>
        </nav>
    </header>
    <main>
        <section id="greeting">
            <h1>Hello, <?php echo $_SESSION["username"]?>!</h1>
            <h2>What would you like to do?</h2>
        </section>
        <section class="sectionTitle">
            <h1>Dashboard</h1>
        </section>
        <section id="dashboard">
            <div class="dashboardCard" id="userDashboard">
                <div class="dashboardCardTitle"><h2>Total Active User</h2></div>
                <div class="dashboardCardData"><h3><?php echo $userCount?></h3></div>
            </div>
            <div class="dashboardCard" id="videoDashboard">
                <div class="dashboardCardTitle"><h2>Total Video Tapes</h2></div>
                <div class="dashboardCardData"><h3><?php echo $videoCount?></h3></div>
            </div>
            <div class="dashboardCard" id="inventoryDashboard">
                <div class="dashboardCardTitle"><h2>Total Inventory</h2></div>
                <div class="dashboardCardData">
                    <h3>Available: <?php echo $availableInventoryCount?></h3>
                    <h3>Rented: <?php echo $rentedInventoryCount?></h3>
                    <h3>Broken: <?php echo $brokenInventoryCount?></h3>
            </div>
            </div>
            <div class="dashboardCard" id="rentalDashboard">
                <div class="dashboardCardTitle"><h2>Total Ongoing Rental</h2></div>
                <div class="dashboardCardData"><h3><?php echo $activeRentalCount?></h3></div>
            </div>
        </section>
        <section class="sectionTitle">
            <h1>Data List</h1>
        </section>
        <button class="selectionCard" id="listUser" onclick="location.href= 'ListOfUser.php'" alt="User List Button" title="Go to List of User">
            <section class="selectionCardDetail">
            <div class="selectionIcon"><img src="./img/multi-user.png" alt="User"></div>
            <h2>List of Users</h2>
            </section>
        </button>
        <button class="selectionCard" id="listRental" onclick="location.href= 'ListOfRental.php'" alt="Rental List Button" title="Go to List of Rental">
            <section class="selectionCardDetail">
            <div class="selectionIcon"><img src="./img/rental.png" alt="Rental"></div>
            <h2>List of Rentals</h2>
            </section>
        </button>
        <button class="selectionCard" id="listVideo" onclick="location.href= 'ListOfTapes.php'" alt="Video Tape List Button" title="Go to List of Video Tape">
            <section class="selectionCardDetail">
            <div class="selectionIcon"><img src="./img/video.png" alt="Video Tape"></div>
            <h2>List of Video Tapes</h2>
            </section>
        </button>
        <button class="selectionCard" id="listInventory" onclick="location.href= 'ListOfInventory.php'" alt="Inventory List Button" title="Go to List of Inventory">
            <section class="selectionCardDetail">
            <div class="selectionIcon"><img src="./img/inventory.png" alt="Inventory"></div>
            <h2>List of Inventory</h2>
            </section>
        </button>
    </main>
</body>
    <script>
    document.addEventListener("DOMContentLoaded", function () {
        <?php if ($_SESSION["staffRole"] == "STORE MANAGER") { ?>
            showStoreManager();
        <?php } else if($_SESSION["staffRole"]== "ADMIN") { ?>
            showAdmin();
        <?php } else { ?>
            reloadOnBack();
        <?php } ?>
    });
    </script>
</html>
