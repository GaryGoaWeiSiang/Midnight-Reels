<?php
    session_start();

    if(empty($_SESSION["role"])|| $_SESSION["role"] != "STAFF"){
    $_SESSION["error"] = "You are not a staff!";
    header("Location: index.php");
    exit();
    }

    if(isset($_GET["sort"])){
        $sort = $_GET["sort"];
    }else{$sort = "";}

    $orderByClause = "Rental.rentalID";
    if(!empty($sort)){
        switch ($sort) {
        case 'rentalID':
            $orderByClause = "Rental.rentalID ASC";
            break;
        case 'rentalBeginDate':
            $orderByClause = "Rental.rentalBeginDate ASC";
            break;
        case 'rentalDuaration':
            $orderByClause = "RentalItem.rentalDuration ASC";
            break;
        case 'actualReturnDate':
            $orderByClause = "RentalItem.actualReturnDate ASC";
            break;
        case 'userID':
            $orderByClause = "Rental.userID ASC";
            break;    
        case 'videoID':
            $orderByClause = "VideoTape.videoID ASC";
            break;
        case 'videoName':
            $orderByClause = "VideoTape.videoName ASC";
            break;
        case 'paymentAmount':
            $orderByClause = "paymentAmount ASC";
            break;
        case 'paymentMethod':
            $orderByClause = "paymentMethod ASC";
            break;
        case 'paymentStatus':
            $orderByClause = "paymentStatus DESC";
            break;
    }
    }
?>

<!DOCTYPE html>
<html lang = "en">
<head>
    <meta charset = "UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name = "description" content = "This is the page of list of rental in the Midnight Reels video rental management system.">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Midnight Reels - List of Rental</title>
    <link rel="stylesheet" href="website.css">
    <link rel = "stylesheet" href = "ListOfRental.css">
    <script src ='website.js' defer></script> 
    <script src ='listPage.js' defer></script>
</head>
<body>
    <header>
        <div id="titleImg">
            <img src = "./img/WebsiteLogo.png" class = 'logo' alt="Midnight Reels Logo">
        </div>
        <!--Same line as header, navigation to Rent and Status page-->
        <nav class ="navigation">
            <div class="toggle-container">
            <?php if(isset($_SESSION["staffRole"])&&$_SESSION["staffRole"]=="ADMIN"): ?>
                <button type="button" class="toggle-btn inactive" id="userBtn" onclick="window.location.href='ListOfUser.php'" alt="User List Button" title="Go to List of User">
                User
                </button>
            <?php endif; ?>
            <button type="button" class="toggle-btn inactive" id="tapesBtn" onclick="window.location.href='ListOfTapes.php'" alt="Video Tape List Button" title="Go to List of Video Tape">
                Tapes
            </button>
            <button type="button" class="toggle-btn active" id="rentalBtn">
                Rental
            </button>
            <button type="button" class="toggle-btn inactive" id="inventoryBtn" onclick="window.location.href='ListOfInventory.php'" alt="Inventory List Button" title="Go to List of Inventory">
                Inventory
            </button>
            </div>
            
                <a href="profile.php" class="profile_button">
                    <img src = './img/HomeProfile.png' class = 'logo' alt="Profile Button" title="Go to Profile">
                </a>
        </nav>
    </header>
    <main class="content-container">
        <div class="pageHeader">
            <button class="backButton" onclick="location.href ='staffPage.php'" alt="Back to Home Button" title="Go to Staff Home Page">< Back to Home</button>
            <h2>Rental</h2>
            <button class="add-btn" onclick="addRental()" alt="Add Button" title="Add">+</button>
        </div>
        <div class="table-wrapper">
        <div class="data-table">
            <div class="table-header">
                <span>Rental ID<img alt="Sort Button" title="Sort" onclick="sort('rentalID')" src="./img/arrow.png" style="width: 20px; height: 20px; margin-left: 10px; position: relative; top: 3px;"></span>
                <span>Rental Begin Date <img alt="Sort Button" title="Sort" onclick="sort('rentalBeginDate')" src="./img/arrow.png" style="width: 20px; height: 20px; margin-left: 10px; position: relative; top: 3px;"></span>
                <span>Rental Duration (Days)<img alt="Sort Button" title="Sort" onclick="sort('rentalDuration')" src="./img/arrow.png" style="width: 20px; height: 20px; margin-left: 10px; position: relative; top: 3px;"></span>
                <span>Actual Return Date<img alt="Sort Button" title="Sort" onclick="sort('actualReturnDate')" src="./img/arrow.png" style="width: 20px; height: 20px; margin-left: 10px; position: relative; top: 3px;"></span>
                <span>User ID<img alt="Sort Button" title="Sort" onclick="sort('userID')" src="./img/arrow.png" style="width: 20px; height: 20px; margin-left: 10px; position: relative; top: 3px;"></span>
                <span>Video ID<img alt="Sort Button" title="Sort" onclick="sort('videoID')" src="./img/arrow.png" style="width: 20px; height: 20px; margin-left: 10px; position: relative; top: 3px;"></span>
                <span>Video Name<img alt="Sort Button" title="Sort" onclick="sort('videoName')" src="./img/arrow.png" style="width: 20px; height: 20px; margin-left: 10px; position: relative; top: 3px;"></span>
                <span>Payment (For Rental)<img alt="Sort Button" title="Sort" onclick="sort('paymentAmount')" src="./img/arrow.png" style="width: 20px; height: 20px; margin-left: 10px; position: relative; top: 3px;"></span>
                <span>Payment Method<img alt="Sort Button" title="Sort" onclick="sort('paymentMethod')" src="./img/arrow.png" style="width: 20px; height: 20px; margin-left: 10px; position: relative; top: 3px;"></span>
                <span>Status<img alt="Sort Button" title="Sort" onclick="sort('paymentStatus')" src="./img/arrow.png" style="width: 20px; height: 20px; margin-left: 10px; position: relative; top: 3px;"></span>
                <span></span>
                <span></span>
            </div>

            <?php
            require_once ("config.php");
            $sql = "SELECT 
                    Rental.rentalID, 
                    Rental.rentalBeginDate, 
                    Rental.userID, 
                    VideoTape.videoID,
                    VideoTape.videoName,
                    Payment.paymentStatus,
                    Payment.paymentMethod,
                    RentalItem.rentalItemID,
                    RentalItem.actualReturnDate,
                    RentalItem.rentalDuration,
                    (SELECT paymentStatus FROM Payment WHERE Payment.rentalID = Rental.rentalID LIMIT 1) AS paymentStatus,
                    (SELECT paymentMethod FROM Payment WHERE Payment.rentalID = Rental.rentalID LIMIT 1) AS paymentMethod,
                    (SELECT SUM(ri.rentalDuration * vt.videoRentalPrice) 
                    FROM RentalItem ri
                    JOIN Inventory inv ON ri.inventoryID = inv.inventoryID
                    JOIN VideoTape vt ON inv.videoID = vt.videoID
                    WHERE ri.rentalID = Rental.rentalID) AS paymentAmount
                    FROM Rental
                    LEFT JOIN RentalItem ON Rental.rentalID = RentalItem.rentalID
                    LEFT JOIN Inventory ON RentalItem.inventoryID = Inventory.inventoryID
                    LEFT JOIN VideoTape ON Inventory.videoID = VideoTape.videoID
                    LEFT JOIN Payment ON Rental.rentalID = Payment.rentalID
                    GROUP BY Rental.rentalID, VideoTape.videoID
                    ORDER BY ".$orderByClause;
            $result = mysqli_query($conn, $sql);
            if ($result && $result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo '<div class="table-row">';
                    echo '  <span>' . $row["rentalID"] . '</span>';
                    echo '  <span>' . $row["rentalBeginDate"] . '</span>';
                    echo '  <span>' . $row["rentalDuration"] . '</span>';
                    echo '  <span>' . $row["actualReturnDate"] . '</span>';
                    echo '  <span>' . $row["userID"] . '</span>';
                    echo '  <span>' . $row["videoID"] . '</span>';
                    echo '  <span>' . $row["videoName"] . '</span>';
                    echo '  <span>RM ' . $row["paymentAmount"] . '</span>';
                    echo '  <span>' . $row["paymentMethod"] . '</span>';
                    echo '  <span>' . $row["paymentStatus"] . '</span>';

                    echo '  <button class="action-btn add-item-btn" 
                                    data-rentalID="' . $row["rentalID"] . '" 
                                    data-userID="' . $row["userID"] . '" 
                                    onclick="addItemToExistingRental(this)">+ Add Item</button>';
                    
                    // echo '  <div class="row-actions">';
                    echo '  <span class="action-card">'; 
                    echo '  <button class="action-btn edit-btn"
                            data-rentalID="' . $row["rentalID"] . '" 
                            data-rentalItemID="' . $row["rentalItemID"] . '" 
                            data-beginDate="' . $row["rentalBeginDate"] . '" 
                            data-duration="' . $row["rentalDuration"] . '" 
                            data-returnDate="' . $row["actualReturnDate"] . '" 
                            data-userID="' . $row["userID"] . '" 
                            data-videoID="' . $row["videoID"] . '" 
                            data-videoName="' . $row["videoName"] . '" 
                            data-payment="' . $row["paymentAmount"] . '"
                            data-paymentMethod="' . $row["paymentMethod"] . '"
                            data-status="' . $row["paymentStatus"] . '" 
                            onclick="editRental(this)">Edit</button>';
                    echo '  <button class="action-btn delete-btn"
                            data-rentalID="' . $row["rentalID"] . '" 
                            data-rentalItemID="' . $row["rentalItemID"] . '" 
                            onclick="deleteRental(this)">Delete</button>';
                    echo '  </span>';
                    // echo '  </div>';

                    echo '  </div>';
                }
            }
            ?>
        </div>
        </div>
    </main>
    <div id="overlay">
        <div id="errorBox" class="errorBox">
            <div id="errorText"></div>
            <div class="errorBoxButton">
                <button id="closeButton" onclick="closeError()">Close</button>
                <button id="confirmButton" onclick="confirmAction()">Confirm</button>
            </div>
        </div>
    </div> 

    <div id="editModal" class="modal-wrapper">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Edit Rental Details</h3>
            <span class="close-btn" onclick="closeModal()">&times;</span>
        </div>
        <form id="rentalForm" action="updateRental.php" method="POST">
            <input type="hidden" name="rentalItemID" id="modalRentalItemID">
            <div class="form-group" id="formRentalID">
                <label>Rental ID</label>
                <input type="text" name="rentalID" id="modalRentalID" readonly>
            </div>
            
            <div class="form-group">
                <label>Rental Begin Date</label>
                <input type="date" name="rentalBeginDate" id="modalBeginDate" required>
            </div>
            <div class="form-group">
                <label>Rental Duration</label>
                <input type="number" name="rentalDuration" id="modalDuration" required>
            </div>
            <div class="form-group">
                <label>Rental Return Date</label>
                <input type="date" name="actualReturnDate" id="modalReturnDate">
            </div>
            <div class="form-group">
                <label>User ID</label>
                <input type="text" name="userID" id="modalUserID" readonly>
            </div>
            <div class="form-group">
                <label>Video ID</label>
                <input type="text" name="videoID" id="modalVideoID" required>
            </div>
            <div class="form-group" id="formVideoName">
                <label>Video Name</label>
                <input type="text" name="videoName" id="modalName" readonly>
            </div>
            <div class="form-group" id="formPaymentAmount">
                <label>Payment Amount</label>
                <input type="text" name="paymentAmount" id="modalPayment" readonly>
            </div>
            <div class="form-group">
                <label>Payment Method</label>
                <select name="paymentMethod" id="modalPaymentMethod">
                    <option value="CASH">Cash</option>
                    <option value="CARD">Card</option>
                    <option value="CARD">QR</option>
                </select>
            </div>
            <div class="form-group">
                <label>Payment Status</label>
                <select name="paymentStatus" id="modalStatus">
                    <option value="PAID">Paid</option>
                    <option value="NOT PAID">Not Paid</option>
                </select>
            </div>
            <div class="modal-footer">
                <button type="button" class="cancel-btn" onclick="closeModal()">Cancel</button>
                <button type="submit" class="save-btn">Save Changes</button>
            </div>
        </form>
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
                showError("<?php echo addslashes($_SESSION["success"]); ?>");
            });
        </script>
    <?php
        unset($_SESSION["success"]);    
    }
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