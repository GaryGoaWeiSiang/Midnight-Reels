<?php
    session_start();
    include("config.php");

    if (!isset($_SESSION["userID"])) {
        $_SESSION["error"] = "Please Login first before proceeding.";
        header("Location: index.php");
        exit();
    }

    $sql = "
    SELECT
        u.userID,

        r.rentalID,
        r.rentalBeginDate,

        ri.rentalDuration,
        ri.dueRentalDate,
        ri.actualReturnDate,
        i.inventoryID,

        v.videoName,
        v.videoImage,

        p.paymentStatus,
        p.paymentAmount

    FROM rental r
    JOIN users u ON r.userID = u.userID
    JOIN rentalItem ri ON r.rentalID = ri.rentalID
    JOIN inventory i ON ri.inventoryID = i.inventoryID
    JOIN videotape v ON i.videoID = v.videoID
    LEFT JOIN payment p ON p.rentalID = r.rentalID
    WHERE r.userID = ?

    ORDER BY r.rentalID DESC
    ";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt,"s", $_SESSION["userID"]);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    $rentalData = [];

    while ($row = mysqli_fetch_assoc($result)) {

    $rentalID = $row['rentalID'];

        if (!isset($rentalData[$rentalID])) {
            $rentalData[$rentalID] = 
            [   'rentalBeginDate' => $row['rentalBeginDate'],
                'paymentStatus' => $row['paymentStatus'],
                'paymentAmount' => $row['paymentAmount'],
                'items' => []
            ];
        }

        $rentalData[$rentalID]['items'][] = 
        [   'videoName' => $row['videoName'],
            'videoImage' => $row['videoImage'],
            'rentalDuration' => $row['rentalDuration'],
            'dueRentalDate' => $row['dueRentalDate'],
            'actualReturnDate' => $row['actualReturnDate'],
            'inventoryID' => $row['inventoryID']
        ];

    }

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Midnight Reels - Rental Status Page</title>

    <link rel='stylesheet' type='text/css' href='website.css'/>
    <link rel='stylesheet' type='text/css' href='rentalStatus.css'/>
    <script src ='website.js'></script>

</head>

<body>
    <header>
        <div id="titleImg"><img src="./img/WebsiteLogo.png"></div>
        <nav class ="navigation">
            <button class="navButton" onclick="location.href = 'index.php'">Home</button>
            <button class="navButton" onclick="location.href = 'rental.php'">Rental</button>
            <a href="profile.php" class="profile_button" aria-label="Profile">
                <img src="img/HomeProfile.png" alt="Profile">
            </a>
        </nav>
    </header>

    <main class="statusContainer">
        <section class="statusTitleHeader">
            <button type="button" id="backButton" onclick="history.back()"><img src="./img/back_button.png"></button>
            <h2>Your Rental Status</h2>
        </section>
        <section class = "statusMain">
            
            <div class="rentalIDContainer">
                
                <?php if (empty($rentalData)): ?>
                    <p style="color:white; text-align:center;">No rental history yet.</p>
                <?php else: ?>
                
                    <?php foreach ($rentalData as $rentalID => $rental) { ?>

                        <div class="rentalContainer">

                            <div class="rentalHeader">
                            <h2>Rental ID: <?php echo $rentalID; ?></h2>
                            </div>

                            <div class = "rentalItemContainer">
                                <?php foreach ($rental['items'] as $item) { ?>

                                    <div class = "rentalItem">

                                        <img src="<?php echo $item['videoImage']; ?>" class="videoImg">

                                        <div>
                                            <h3><?php echo $item['videoName']; ?></h3>
                                            <p>Duration: <?php echo $item['rentalDuration']; ?> days</p>
                                            <p>Due: <?php echo $item['dueRentalDate']; ?></p>
                                            <?php if(empty($item["actualReturnDate"])): ?>
                                                <form action="returnTape.php" method="post">
                                                <input type="hidden" name="rentalID" value="<?php echo $rentalID; ?>">
                                                <input type="hidden" name="inventoryID" value="<?php echo $item['inventoryID']; ?>">
                                                <button type="button" id="returnButton" onclick="showError('Confirm you want to return<br><?php echo $item['videoName']; ?>?','confirm',()=>form.submit(),'Return','Cancel')">Return</button>
                                                </form>
                                            <?php else: ?>
                                                <div id="returnMessage"><h3>Returned on: <?php echo $item["actualReturnDate"] ?></h3></div>
                                            <?php endif ?>
                                        </div>


                                    </div>

                                <?php } ?>
                                
                                
                            </div>
                            <div class="paymentContainer">
                                <div class="paymentStatus">
                                    <h2>Payment Status: <?php echo $rental['paymentStatus']; ?> </h2>
                                </div>

                                <div class="paymentTotal">
                                    <h2>Total: RM <?php echo number_format($rental['paymentAmount'], 2); ?> </h2>
                                </div>
                                </div>
                        </div>

                    <?php } ?>
                <?php endif; ?>

            </div>

        </div>

        </section>




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
        <script>
            document.addEventListener("DOMContentLoaded", function () {
                reloadOnBack();
            });
        </script>



</html>

<?php
if(isset($_SESSION["success"])){
        ?>
        <script>
            document.addEventListener("DOMContentLoaded", function () {
                showError("<?php echo addslashes($_SESSION["success"]); ?>","error",null,"","Close");
            });
        </script>
    <?php
        unset($_SESSION["success"]);    
    }
if(isset($_SESSION["error"])){
        ?>
        <script>
            document.addEventListener("DOMContentLoaded", function () {
                showError("<?php echo addslashes($_SESSION["error"]); ?>","error",null,"","Close");
            });
        </script>
    <?php
        unset($_SESSION["error"]);  
}
?>