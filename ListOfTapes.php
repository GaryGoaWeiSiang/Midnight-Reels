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

    $orderByClause = "videoID ASC";
    if(!empty($sort)){
        switch ($sort) {
        case 'videoID':
            $orderByClause = "videoID ASC";
            break;
        case 'videoName':
            $orderByClause = "videoName ASC";
            break;
        case 'videoGenre':
            $orderByClause = "videoGenre ASC";
            break;
        case 'videoDuration':
            $orderByClause = "videoDuration ASC";
            break;    
        case 'videoReleaseDate':
            $orderByClause = "videoReleaseDate ASC";
            break;
        case 'videoRentalPrice':
            $orderByClause = "videoRentalPrice ASC";
            break;
        case 'videoStatus':
            $orderByClause = "videoStatus ASC";
            break;
    }
    }
?>

<!DOCTYPE html>
<html lang = "en">
<head>
    <meta charset = "UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name = "description" content = "This is the page of list of video tape in the Midnight Reels video rental management system.">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Midnight Reels - List of Video Tape</title>
    <link rel="stylesheet" href="website.css">
    <link rel = "stylesheet" href = "ListOfTapes.css"> 
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
                <button type="button" class="toggle-btn active" id="tapesBtn">
                Tapes
                </button>
                <button type="button" class="toggle-btn inactive" id="rentalBtn" onclick="window.location.href='ListOfRental.php'" alt="Rental List Button" title="Go to List of Rental">
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
            <h2>Video Tape</h2>
            <button class="add-btn" id="addTapeButton"
                    onclick="addTape()" alt="Add Button" title="Add">+</button>
        </div>
        <div class="table-wrapper">
        <div class="data-table">
            <div class="table-header">
                <span>ID<img alt="Sort Button" title="Sort" onclick="sort('videoID')" src="./img/arrow.png" style="width: 20px; height: 20px; margin-left: 5px; position: relative; bottom: 2px;"></span>
                <span>Name<img alt="Sort Button" title="Sort" onclick="sort('videoName')" src="./img/arrow.png" style="width: 20px; height: 20px; margin-left: 5px; position: relative; bottom: 2px;"></span>
                <span>Description</span>
                <span>Genre<img alt="Sort Button" title="Sort" onclick="sort('videoGenre')" src="./img/arrow.png" style="width: 20px; height: 20px; margin-left: 5px; position: relative; bottom: 2px;"></span>
                <span>Duration<img alt="Sort Button" title="Sort" onclick="sort('videoDuration')" src="./img/arrow.png" style="width: 20px; height: 20px; margin-left: 5px; position: relative; bottom: 2px;"></span>
                <span>Release Date<img alt="Sort Button" title="Sort" onclick="sort('videoReleaseDate')" src="./img/arrow.png" style="width: 20px; height: 20px; margin-left: 5px; position: relative; bottom: 2px;"></span>
                <span>Price<img alt="Sort Button" title="Sort" onclick="sort('videoRentalPrice')" src="./img/arrow.png" style="width: 20px; height: 20px; margin-left: 5px; position: relative; bottom: 2px;"></span>
                <span>Image</span>
                <span>Status<img alt="Sort Button" title="Sort" onclick="sort('videoStatus')" src="./img/arrow.png" style="width: 20px; height: 20px; margin-left: 5px; position: relative; bottom: 2px;"></span>
                <span></span>
            </div>

            <?php
            require_once ("config.php");
            $sql = "SELECT videoID, videoName, videoDescription, videoGenre, videoDuration, videoReleaseDate, videoRentalPrice, videoImage, videoStatus FROM VideoTape ORDER BY ".$orderByClause;
            $result = mysqli_query($conn, $sql);
            if ($result && $result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    $cleanDesc = str_replace(array("\r", "\n"), ' ', $row["videoDescription"]);
                    echo '<div class="table-row">';
                    echo '  <span>' . $row["videoID"] . '</span>';
                    echo '  <span>' . htmlspecialchars($row["videoName"], ENT_QUOTES, 'UTF-8') . '</span>';
                    echo '  <span>' . htmlspecialchars($cleanDesc, ENT_QUOTES, 'UTF-8') . '</span>';
                    echo '  <span>' . htmlspecialchars($row["videoGenre"], ENT_QUOTES, 'UTF-8') . '</span>';
                    echo '  <span>' . htmlspecialchars($row["videoDuration"], ENT_QUOTES, 'UTF-8') . '</span>';
                    echo '  <span>' . htmlspecialchars($row["videoReleaseDate"], ENT_QUOTES, 'UTF-8') . '</span>';
                    echo '  <span>RM ' . htmlspecialchars($row["videoRentalPrice"], ENT_QUOTES, 'UTF-8') . '</span>';
                    echo '  <span class="tape-img-cell"><img src="' . htmlspecialchars($row["videoImage"], ENT_QUOTES, 'UTF-8') . '" alt="'.htmlspecialchars($row["videoName"], ENT_QUOTES, 'UTF-8').'" title="'.htmlspecialchars($row["videoName"], ENT_QUOTES, 'UTF-8').'"></span>';
                    echo '  <span>' . htmlspecialchars($row["videoStatus"], ENT_QUOTES, 'UTF-8') . '</span>';

                    // echo '  <div class="row-actions">';
                    echo '  <span class="action-card">';
                    echo "  <button class='action-btn edit-btn' 
                            data-id='" . $row["videoID"] . "' 
                            data-name='" . htmlspecialchars($row["videoName"], ENT_QUOTES, 'UTF-8') . "' 
                            data-desc='" . htmlspecialchars($cleanDesc, ENT_QUOTES, 'UTF-8') . "' 
                            data-genre='" . htmlspecialchars($row["videoGenre"], ENT_QUOTES, 'UTF-8') . "' 
                            data-duration='" . htmlspecialchars($row["videoDuration"], ENT_QUOTES, 'UTF-8') . "'
                            data-releaseDate='" . htmlspecialchars($row["videoReleaseDate"], ENT_QUOTES, 'UTF-8') . "'
                            data-price='" . htmlspecialchars($row["videoRentalPrice"], ENT_QUOTES, 'UTF-8') . "'
                            data-image='" . htmlspecialchars($row["videoImage"], ENT_QUOTES, 'UTF-8') . " '
                            data-status='" . htmlspecialchars($row["videoStatus"], ENT_QUOTES, 'UTF-8') . "' 
                            onclick='editTape(this)'>Edit</button>";
                    echo '  <button class="action-btn delete-btn"
                             data-id="' . $row["videoID"] . '" 
                             onclick="deleteTape(this)">Delete</button>';
                    echo '  </span>';
                    //echo '  </div>';

                    echo '</div>';
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
            <h3 >Edit Tape Details</h3>
            <span class="close-btn" onclick="closeModal()">&times;</span>
        </div>
        <form id="tapeForm" action="updateTape.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="videoID" id="modalVideoID">
            
            <div class="form-group">
                <label>Name</label>
                <input type="text" name="videoName" id="modalName" required>
            </div>
            <div class="form-group">
                <label>Description</label>
                <input type="text" name="videoDescription" id="modalDescription" required>
            </div>
            <div class="form-group">
                <label>Genre</label>
                <select name="videoGenre" id="modalGenre">
                    <option value="ACTION">Action</option>
                    <option value="COMEDY">Comedy</option>
                    <option value="SCI-FI">Sci-Fi</option>
                    <option value="HORROR">Horror</option>
                    <option value="ROMANCE">Romance</option>
                </select>
            </div>
            <div class="form-group">
                <label>Duration</label>
                <input type="text" name="videoDuration" id="modalDuration" required>
            </div>
            <div class="form-group">
                <label>Release Date</label>
                <input type="date" name="videoReleaseDate" id="modalReleaseDate" required>
            </div>
            <div class="form-group">
                <label>Rental Price</label>
                <input type="text" name="videoRentalPrice" id="modalPrice" required>
            </div>
            <div class="form-group">
                <label>Image Path</label>
                    <input type="text" name="imagePath" id="modalImage">
            </div>
            <div class="form-group">
                <label>Video Tape Status</label>
                <select name="videoStatus" id="modalStatus">
                    <option value="AVAILABLE">Available</option>
                    <option value="DELETED">Deleted</option>
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