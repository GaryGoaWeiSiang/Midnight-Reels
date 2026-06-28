<?php 
session_start();
if(isset($_GET["sort"])){
        $sort = $_GET["sort"];
    }else{$sort = "";}

    $orderByClause = "inventoryID ASC";
    if(!empty($sort)){
        switch ($sort) {
        case 'inventoryID':
            $orderByClause = "Inventory.inventoryID ASC";
            break;
        case 'videoID':
            $orderByClause = "Inventory.videoID ASC";
            break;
        case 'videoName':
            $orderByClause = "VideoTape.videoName ASC";
            break;
        case 'inventoryStatus':
            $orderByClause = "Inventory.inventoryStatus ASC";
            break;    
    }
    }
?>

<!DOCTYPE html>
<html lang = "en">
<head>
    <meta charset = "UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name = "description" content = "This is the list of inventory page of Midnight Reels video rental management system.">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Midnight Reels - List of Inventory</title>
    <link rel="stylesheet" href="website.css">
    <link rel = "stylesheet" href = "ListOfInventory.css"> 
    <script src="website.js" defer></script>
    <script src="listPage.js" defer></script>
</head>

<body>

    <header>
        <div id="titleImg">
            <img class="logo" src="img/WebsiteLogo.png" alt="Midnight Reels" alt="Midnight Reels Logo">
        </div>

        <nav class="navigation">
            <div class="toggle-container">
            <?php if(isset($_SESSION["staffRole"])&&$_SESSION["staffRole"]=="ADMIN"): ?>
                <button type="button" class="toggle-btn inactive" id="userBtn" onclick="window.location.href='ListOfUser.php'" alt="User List Button" title="Go to List of User">
                User
                </button>
                <?php endif; ?>
            <button type="button" class="toggle-btn inactive" id="tapesBtn" onclick="window.location.href='ListOfTapes.php'" alt="Video Tape List Button" title="Go to List of Video Tape">
                Tapes
            </button>
            <button type="button" class="toggle-btn inactive" id="rentalBtn" onclick="window.location.href='ListOfRental.php'" alt="Rental List Button" title="Go to List of Rental">
                Rental
            </button>
            <button type="button" class="toggle-btn active" id="inventoryBtn">
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
            <h2>Inventory</h2>
            <button class="add-btn" onclick="addInventory()" alt="Add Button" title="Add">+</button>
        </div>
        <div class="table-wrapper">
        <div class="data-table">
            <div class="table-header">
                <span >Inventory ID<img alt="Sort Button" title="Sort" onclick="sort('inventoryID')" src="img/arrow.png" style="width: 20px; height: 20px; margin-left: 10px; position: relative; top: 3px;"></span>
                <span onclick="">Video ID<img alt="Sort Button" title="Sort" onclick="sort('videoID')" src="img/arrow.png" style="width: 20px; height: 20px; margin-left: 10px; position: relative; top: 3px;"></span>
                <span onclick="">Video Name<img alt="Sort Button" title="Sort" onclick="sort('videoName')" src="img/arrow.png" style="width: 20px; height: 20px; margin-left: 10px; position: relative; top: 3px;"></span>
                <span onclick="">Inventory Status<img alt="Sort Button" title="Sort" onclick="sort('inventoryStatus')" src="img/arrow.png" style="width: 20px; height: 20px; margin-left: 10px; position: relative; top: 3px;"></span>
                <span></span>
            </div>

            <?php
            require_once ("config.php");
            $sql = "SELECT 
                    Inventory.inventoryID, 
                    Inventory.videoID,
                    VideoTape.videoName,
                    Inventory.inventoryStatus  
                    FROM Inventory
                    LEFT JOIN VideoTape ON Inventory.videoID=VideoTape.videoID
                    ORDER BY ".$orderByClause;
            $result = mysqli_query($conn, $sql);
            if ($result && $result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo '<div class="table-row">';
                    echo '  <span>' . $row["inventoryID"] . '</span>';
                    echo '  <span>' . $row["videoID"] . '</span>';
                    echo '  <span>' . $row["videoName"] . '</span>';
                    echo '  <span>' . $row["inventoryStatus"] . '</span>';
                    
                    //echo '  <div class="row-actions">';
                    echo '  <span class="action-card">';

                    echo '  <button class="action-btn edit-btn"
                            data-inventoryid="' . $row["inventoryID"] . '" 
                            data-videoid="' . $row["videoID"] . '" 
                            data-status="' . $row["inventoryStatus"] . '" 
                            onclick="editInventory(this)">Edit</button>';

                    echo '  <button class="action-btn delete-btn" 
                            data-inventoryid="' . $row["inventoryID"] . '" 
                            onclick="deleteInventory(this)">Delete</button>';

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
            <h3 class="modalTitle">Edit Inventory Details</h3>
            <span class="close-btn" onclick="closeModal()">&times;</span>
        </div>
        <form id="inventoryForm" action="updateInventory.php" method="POST">
            <div class="form-group" id="formInventoryID">
                <label>Inventory ID</label>
                <input type="text" name="inventoryID" id="modalInventoryID" readonly>
            </div>

            <div class="form-group">
                <label>Video ID</label>
                <input type="text" name="videoID" id="modalVideoID" required>
            </div>
            <div class="form-group">
                <label>Inventory Status</label>
                <select name="inventoryStatus" id="modalStatus">
                    <option value="AVAILABLE">Available</option>
                    <option value="RENTED">Rented</option>
                    <option value="BROKEN">Broken</option>
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