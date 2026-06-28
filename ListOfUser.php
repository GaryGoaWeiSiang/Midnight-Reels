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

    $orderByClause = "u.userID ASC";
    if(!empty($sort)){
        switch ($sort) {
        case 'userID':
            $orderByClause = "u.userID ASC";
            break;
        case 'username':
            $orderByClause = "u.username ASC";
            break;
        case 'phoneNumber':
            $orderByClause = "u.phoneNumber ASC";
            break;
        case 'emailAddress':
            $orderByClause = "u.emailAddress ASC";
            break;    
        case 'role':
            $orderByClause = "u.role ASC";
            break;
        case 'userStatus':
            $orderByClause = "u.userStatus ASC";
            break;
    }
    }
?>

<!DOCTYPE html>
<html lang = "en">
<head>
    <meta charset = "UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name = "description" content = "This is the page of list of user in the Midnight Reels video rental management system.">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Midnight Reels - List of User</title>
    <link rel="stylesheet" href="website.css">
    <link rel = "stylesheet" href = "ListOfUser.css">
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
                <button type="button" class="toggle-btn active" id="userBtn" >
                User
                </button>
                <button type="button" class="toggle-btn inactive" id="tapesBtn" onclick="window.location.href='ListOfTapes.php'" alt="Video Tape List Button" title="Go to List of Video Tape">
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
        <h2>System Users</h2>
        <button class="add-btn" onclick="addUser()" alt="Add Button" title="Add">+</button>
        </div>
        <div class="table-wrapper">
        <div class="data-table">
            <div class="table-header">
                <span>User ID<img alt="Sort Button" title="Sort" onclick="sort('userID')" src="./img/arrow.png" style="width: 20px; height: 20px; margin-left: 10px; position: relative; top: 3px;"></span>
                <span>Username<img alt="Sort Button" title="Sort" onclick="sort('username')" src="./img/arrow.png" style="width: 20px; height: 20px; margin-left: 10px; position: relative; top: 3px;"></span>
                <span>Email<img alt="Sort Button" title="Sort" onclick="sort('emailAddress')" src="./img/arrow.png" style="width: 20px; height: 20px; margin-left: 10px; position: relative; top: 3px;"></span>
                <span>Phone Number<img alt="Sort Button" title="Sort" onclick="sort('phoneNumber')" src="./img/arrow.png" style="width: 20px; height: 20px; margin-left: 10px; position: relative; top: 3px;"></span>
                <span>Role<img alt="Sort Button" title="Sort" onclick="sort('role')" src="./img/arrow.png" style="width: 20px; height: 20px; margin-left: 10px; position: relative; top: 3px;"></span>
                <span>Status<img alt="Sort Button" title="Sort" onclick="sort('userStatus')" src="./img/arrow.png" style="width: 20px; height: 20px; margin-left: 10px; position: relative; top: 3px;"></span>
                <span></span>
            </div>

            <?php
            require_once ("config.php");
            $sql = "SELECT u.userID, u.username, u.emailAddress, u.password, u.phoneNumber, u.role, u.userStatus, 
                           c.address, s.staffID 
                    FROM Users u
                    LEFT JOIN Customer c ON u.userID = c.userID
                    LEFT JOIN Staff s ON u.userID = s.userID
                    ORDER BY ".$orderByClause;
            $result = mysqli_query($conn, $sql);
            if ($result && $result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo '<div class="table-row">';
                    echo '  <span>' . $row["userID"] . '</span>';
                    echo '  <span>' . $row["username"] . '</span>';
                    echo '  <span>' . $row["emailAddress"] . '</span>';
                    echo '  <span>' . $row["phoneNumber"] . '</span>';
                    echo '  <span>' . $row["role"] . '</span>';
                    echo '  <span>' . $row["userStatus"] . '</span>';

                    // echo '  <div class="row-actions">';
                    echo '  <span class="action-card">';
                    echo '  <button class="action-btn edit-btn" 
                            data-id="' . $row["userID"] . '" 
                            data-username="' . $row["username"] . '" 
                            data-email="'.$row["emailAddress"].'" 
                            data-password="' . $row["password"] . '" 
                            data-phone="'.$row["phoneNumber"].'" 
                            data-role="'.$row["role"].'"
                            data-status="'.$row['userStatus'].'"
                            data-address="'.($row['address'] ?? '').'"
                            data-staffid="'.($row['staffID'] ?? '').'"
                            onclick="editUser(this)">Edit</button>';
                    echo '  <button class="action-btn delete-btn"
                             data-id="' . $row["userID"] . '"                     
                             onclick="deleteUser(this)">Delete</button>';
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
            <h3>Edit User Details</h3>
            <span class="close-btn" onclick="closeModal()">&times;</span>
        </div>
        <form action="updateUser.php"  id="userForm" method="POST">
            <input type="hidden" name="currentPassword" id="modalCurrentPassword">
            <div class="form-group" id="formUserID">
                <label>User ID</label>
                <input type="text" name="userID" id="modalUserID" readOnly>
            </div>
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" id="modalUsername" required>
            </div>
            <div class="form-group">
                <label>Email Address</label>
                <input type="email" name="emailAddress" id="modalEmail" required>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="text" name="password" id="modalPassword" required>
            </div>
            <div class="form-group">
                <label>Phone Number</label>
                <input type="text" name="phoneNumber" id="modalPhone" required>
            </div>
            <div class="form-group">
                <label>Role</label>
                <select name="role" id="modalRole">
                    <option value="CUSTOMER">Customer</option>
                    <option value="ADMIN">Admin</option>
                    <option value="STORE MANAGER">Store Manager</option>
                </select>
            </div>
            <div class="form-group" id="formCustomerAddress">
                <label>Customer Address</label>
                <textarea name="address" id="modalAddress" rows="3" style="width:100%; border-radius:4px; border:1px solid #ccc; padding:6px; font-family:inherit;"></textarea>
            </div>

            <div class="form-group" id="formStaffID">
                <label>Staff ID</label>
                <input type="text" name="staffID" id="modalStaffID" required>
            </div>
            <div class="form-group">
                <label>User Status</label>
                <select name="userStatus" id="modalStatus">
                    <option value="ACTIVE">Active</option>
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