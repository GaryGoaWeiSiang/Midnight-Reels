<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();

if(!isset($_SESSION['userID'])) {
    header("Location: login.php");
    exit();
}

$userID = $_SESSION['userID'];
$role = $_SESSION['role'];
$user = null;
$customer = null;
$staff = null;
$nowRentals = null;
$recentRentals = null;

require_once("config.php");

$sql = "SELECT * FROM Users WHERE userID = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt,"i", $userID);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);

if ($role === 'CUSTOMER') {
    $sql2 = "SELECT * FROM Customer WHERE userID = ?";
    $stmt2 = mysqli_prepare($conn, $sql2);
    mysqli_stmt_bind_param($stmt2,"i", $userID);
    mysqli_stmt_execute($stmt2);
    $result2 = mysqli_stmt_get_result($stmt2);
    $customer = mysqli_fetch_assoc($result2);

    $sqlNow = "SELECT vt.videoID, vt.videoName, vt.videoImage, vt.videoRentalPrice,
                      COUNT(ri.rentalItemID) AS quantity,
                      MIN(r.rentalBeginDate) AS rentalBeginDate,
                      MIN(ri.dueRentalDate) AS dueRentalDate,
                      ri.rentalDuration,
                      DATEDIFF(ri.dueRentalDate,CURDATE()) AS daysTillDue
               FROM RentalItem ri
               JOIN Inventory inv ON ri.inventoryID = inv.inventoryID
               JOIN VideoTape vt ON inv.videoID = vt.videoID
               JOIN Rental r ON ri.rentalID = r.rentalID
               WHERE r.userID = ? AND ri.actualReturnDate IS NULL
               GROUP BY vt.videoID, r.rentalID, ri.rentalDuration";
    $stmtNow = mysqli_prepare($conn, $sqlNow);
    mysqli_stmt_bind_param($stmtNow,"i", $userID);
    mysqli_stmt_execute($stmtNow);
    $nowRentals = mysqli_stmt_get_result($stmtNow);

    $sqlRecent = "SELECT vt.videoID, vt.videoName, vt.videoImage, vt.videoRentalPrice,
                        COUNT(ri.rentalItemID) AS quantity,
                        MIN(r.rentalBeginDate) AS rentalBeginDate,
                        MAX(ri.actualReturnDate) AS actualReturnDate,
                        ri.rentalDuration
                  FROM RentalItem ri
                  JOIN Inventory inv ON ri.inventoryID = inv.inventoryID
                  JOIN VideoTape vt ON inv.videoID = vt.videoID
                  JOIN Rental r ON ri.rentalID = r.rentalID
                  WHERE r.userID = ? AND ri.actualReturnDate IS NOT NULL
                  GROUP BY vt.videoID, r.rentalID, ri.rentalDuration
                  ORDER BY actualReturnDate DESC";
    $stmtRecent = mysqli_prepare($conn, $sqlRecent);
    mysqli_stmt_bind_param($stmtRecent,"i", $userID);
    mysqli_stmt_execute($stmtRecent);
    $recentRentals = mysqli_stmt_get_result($stmtRecent);
}

if ($role === 'ADMIN' || $role === 'STORE MANAGER' || $role === 'STAFF') {
    $sql3 = "SELECT * FROM Staff WHERE userID = ?";
    $stmt3 = mysqli_prepare($conn, $sql3);
    mysqli_stmt_bind_param($stmt3,"i", $userID);
    mysqli_stmt_execute($stmt3);
    $result3 = mysqli_stmt_get_result($stmt3);
    $staff = mysqli_fetch_assoc($result3);
    $role = "STAFF";
    $staffRole = $user["role"];
}

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Profile Page</title>

        <!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css"> -->
        <link rel='stylesheet' href='website.css'/>
        <link rel='stylesheet' href='profile.css'/>
        <script src="website.js" defer></script>
        
    </head>
    <body>
        <header>
            <div id="titleImg">
                <img class="logo" src="img/WebsiteLogo.png" alt="Midnight Reels Logo">
            </div>

            <nav class="navigation">
                <button onclick="location.href ='index.php'" class="navButton" alt="Home Button" title="Go to Home">Home</button>
                <?php if($role == "CUSTOMER"):?>
                <button onclick="location.href ='rental.php'" id="rentalButton" class="navButton" alt="Rental Button" title="Go to Rental Page">Rental</button>
                <button onclick="location.href ='rentalStatus.php'" id="rentalStatusButton" class="navButton">Rental Status</button>
                <?php endif;?>
                <a href="profile.php" class="profile_button" aria-label="Profile">
                    <img src="img/HomeProfile.png" alt="Profile">
                </a>
            </nav>

        </header>

        <main class="page-wrapper">
            <article class="profile-card">

                <button class="close-btn" onclick="history.back()">&#10005;</button>

                <section class="profile-header">

                    <figure class="profile-avatar">
                        <img src="img/HomeProfile.png" alt="Profile avatar">
                    </figure>

                    <section class="profile-info">
                        <h1 class="profile-username"><?php echo htmlspecialchars($user['username']); ?></h1>
                        <span class="role-badge <?php echo strtolower($role); ?>">
                            <?php if($role=="CUSTOMER") {echo ucfirst (strtolower($role));} 
                                    else {echo ucfirst (strtolower($role)) .": ".ucwords (strtolower($staffRole));}
                            ?>
                        </span>

                        <ul class="info-fields">
                            <li class="info-field"><b>Email address:</b> <?php echo htmlspecialchars($user['emailAddress']); ?></li>
                            <li class="info-field"><b>Phone number:</b> <?php echo htmlspecialchars($user['phoneNumber']); ?></li>

                            <?php if ($role === 'CUSTOMER'): ?>
                            <li class="info-field" id="field-address">
                                <b>Address:</b> <?php echo htmlspecialchars($customer['address']); ?>
                            </li>
                            <?php endif; ?>

                            <?php if ($role === 'STAFF' || $role === 'ADMIN'): ?>
                            <li class="info-field" id="field-staffid">
                                <b>Staff ID:</b> <?php echo htmlspecialchars($staff['staffID']); ?>
                            </li>
                            <?php endif; ?>
                        </ul>

                        <button class="edit-btn" onclick="location.href='edit_profile.php'" alt="Edit Profile Button" title="Edit Profile">Edit profile</button>
                        <button class="logout-btn" onclick="showError('Confirm Log Out?','confirm',()=>location.href='logout.php','Log Out')" alt="Log Out Button" title="Log Out">Log Out</button>
                    </section>

                </section>

                <?php if ($role === 'CUSTOMER'): ?>
                <section class="rental-section" id="rental-section">

                    <nav class="tab-row">
                        <button class="tab-btn active" onclick="switchTab('now', this)" alt="Now Rental Button" title="Now Rental">Now Rental</button>
                        <button class="tab-btn" onclick="switchTab('recent', this)" alt="Recently Rental Button" title="Reently Rental">Recently Rental</button>
                    </nav>

                    <div id="tab-now" class="tab-panel active">
                        <div class="tape-grid">
                            <?php if (mysqli_num_rows($nowRentals) > 0): ?>
                                <?php while ($tape = mysqli_fetch_assoc($nowRentals)) :
                                    $totalPrice = $tape['videoRentalPrice'] * $tape['quantity'] * $tape['rentalDuration'];
                                    $due = $tape['dueRentalDate'];
                                    $daysLeft = (int)$tape['daysTillDue'];
                                    $isOverdue = ($daysLeft < 0) ? true : false;
                                    $statusText = $isOverdue
                                        ? $daysLeft . ' day(s) overdue — please return as soon as possible!'
                                        : $daysLeft . ' day(s) left';
                                    $detailHtml = "<img src='" . htmlspecialchars($tape['videoImage']) . "' style='width:120px; border-radius:10px; margin-bottom:10px;'><br>"
                                        . "<b>" . htmlspecialchars($tape['videoName']) . "</b><br>"
                                        . "Quantity: " . $tape['quantity'] . "<br>"
                                        . "Rented on: " . $tape['rentalBeginDate'] . "<br>"
                                        . "Total price: RM " . number_format($totalPrice, 2) . "<br>"
                                        . "Due date: " . $tape['dueRentalDate'] . "<br>"
                                        . ($isOverdue ? "<span style='color:#f09090'>" . $statusText . "</span>" : $statusText);
                                ?>
                            <article class="tape-card" onclick="showError('<?php echo addslashes($detailHtml); ?>','confirm',()=>location.href = 'rentalStatus.php', 'Go to Rental Status', 'Close')">
                                        <figure class="tape-cover">
                                            <img src="<?php echo htmlspecialchars($tape['videoImage']); ?>" alt="<?php echo htmlspecialchars($tape['videoName']); ?>">
                                        </figure>
                                        <p class="tape-title"><?php echo htmlspecialchars($tape['videoName']); ?></p>
                                    </article>
                                <?php endwhile; ?>
                            <?php else : ?>
                                <p class="no-rental-msg">No tapes currently rented.</p>
                            <?php endif; ?>

                        </div>
                    </div>

                    <div id="tab-recent" class="tab-panel">
                        <div class="tape-grid">
                            <?php if (mysqli_num_rows($recentRentals) > 0): ?>
                                <?php while ($tape = mysqli_fetch_assoc($recentRentals)) :
                                    $totalPrice = $tape['videoRentalPrice'] * $tape['quantity'] * $tape['rentalDuration'];
                                    $detailHtml = "<img src='" . htmlspecialchars($tape['videoImage']) . "' style='width:120px; border-radius:10px; margin-bottom:10px;'><br>"
                                        . "<b>" . htmlspecialchars($tape['videoName']) . "</b><br>"
                                        . "Quantity: " . $tape['quantity'] . "<br>"
                                        . "Rented on: " . $tape['rentalBeginDate'] . "<br>"
                                        . "Total price: RM " . number_format($totalPrice, 2) . "<br>"
                                        . "Returned on: " . $tape['actualReturnDate'];
                                ?>
                                    <article class="tape-card" onclick="showError('<?php echo addslashes($detailHtml); ?>', 'confirm', () => location.href='videoTape.php?id=<?php echo $tape['videoID']; ?>', 'Rent Again', 'Close')">
                                        <figure class="tape-cover">
                                            <img src="<?php echo htmlspecialchars($tape['videoImage']); ?>" alt="<?php echo htmlspecialchars($tape['videoName']); ?>">
                                        </figure>
                                        <p class="tape-title"><?php echo htmlspecialchars($tape['videoName']); ?></p>
                                    </article>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <p class="no-rental-msg">No rental history yet.</p>
                            <?php endif; ?>
                        </div>
                    </div>

                </section>
                <?php endif; ?>

            </article>
        </main>
        <div id="overlay">
            <div id="errorBox" class="errorBox">
                <div id="errorText"></div>
                <div class="errorBoxButton">
                    <button id="closeButton" onclick="closeError()" alt="Close Button" title="Close">Cancel</button>
                    <button id="confirmButton" onclick="confirmAction(true,'logout.php')" alt="Confirm Button" title="Confirm">Confirm</button>
                </div>
            </div>
        </div> 

        <script src ='profile.js'></script>
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
                showError("<?php echo addslashes($_SESSION["success"]); ?>","error","","Close");
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