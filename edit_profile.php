<?php
session_start();

if (!isset($_SESSION['userID'])) {
    header("Location: login.php");
    exit();
}

$userID = $_SESSION['userID'];
$role = $_SESSION['role'];
if($role == "STAFF"){
$staffRole = $_SESSION['staffRole'];
}
$user = null;
$customer = null;
$staff = null;


include("config.php");

$error = '';
if (isset($_GET['error'])) {
    if ($_GET['error'] === 'password_mismatch') {
        $error = 'New password and confirm password do not match!';
    } else if ($_GET['error'] === 'same_password') {
        $error = 'New password cannot be the same as the old password!';
    }
}

$sql = "SELECT * FROM Users WHERE userID = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt,"i", $userID);
mysqli_stmt_execute($stmt);
$user = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

if ($role === 'CUSTOMER') {
    $sql2 = "SELECT * FROM Customer WHERE userID = ?";
    $stmt2 = mysqli_prepare($conn, $sql2);
    mysqli_stmt_bind_param($stmt2, "i", $userID);
    mysqli_stmt_execute($stmt2);
    $customer = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt2));
}

if ($role === 'STAFF' &&($staffRole === 'STORE MANAGER' || $staffRole === 'ADMIN')) {
    $sql3  = "SELECT * FROM Staff WHERE userID = ?";
    $stmt3 = mysqli_prepare($conn, $sql3);
    mysqli_stmt_bind_param($stmt3, "i", $userID);
    mysqli_stmt_execute($stmt3);
    $staff = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt3));
}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Edit Profile Page</title>
        <link rel="stylesheet" href="website.css">
        <link rel="stylesheet" href="profile.css">
        <link rel='stylesheet' href='edit_profile.css'/>
    </head>
    <body>
        
        <header>
            <div id="titleImg">
                <img src="img/WebsiteLogo.png" alt="Midnight Reels">
            </div>
            <nav class="navigation">
                <button onclick="location.replace('index.php')" class="navButton" alt="Home Button" title="Go to Home">Home</button>
                <?php if($role == "CUSTOMER"):?>
                <button onclick="location.href ='rental.php'" id="rentalButton" class="navButton" alt="Rental Button" title="Go to Rental Page">Rental</button>
                <button onclick="location.href ='rentalStatus.php'" id="rentalStatusButton" class="navButton" alt="Rental Status Button" title="Go to Rental Status Page">Rental Status</button>
                <?php endif;?>
                <a href="profile.php" class="profile_button" aria-label="Profile">
                    <img src="img/HomeProfile.png" alt="Profile">
                </a>
            </nav>
        </header>

        <main class="page-wrapper">
            <article class="profile-card">

                <button class="close-btn" onclick="history.back()">&#10005;</button>

                <section class="edit-body">
                    <aside class="edit-avatar-side">
                        <figure class="profile-avatar">
                            <img src="img/HomeProfile.png" alt="Profile avatar">
                        </figure>
                        <span class="role-badge <?php echo strtolower($role); ?>">
                            <?php echo ucfirst(strtolower($role)); ?> 
                            <?php if(!empty($staffRole)) echo ":<br>". ucwords(strtolower($staffRole)); ?>
                        </span>
                    </aside>

                    <section class="edit-form-side">
                        <form action="update_profile.php" method="POST">
                            <div class="edit-field">
                                <label for="username">Username</label>
                                <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>">
                            </div>

                            <div class="edit-field">
                                <label for="email">Email address</label>
                                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['emailAddress']); ?>">
                            </div>

                            <?php if ($role === 'CUSTOMER'): ?>
                            <div class="edit-field">
                                <label for="phone">Phone number</label>
                                <input type="text" id="phone" name="phone" value="<?php echo htmlspecialchars($user['phoneNumber']); ?>">
                            </div>
                            <?php endif; ?>

                            <?php if ($role === 'STAFF' || $role === 'ADMIN'): ?>
                            <div class="edit-field">
                                <label for="staffid">Staff ID</label>
                                <input type="text" id="staffid" value="<?php echo htmlspecialchars($staff['staffID']); ?>" disabled>
                            </div>
                            <?php endif; ?>

                            <?php if ($role === 'CUSTOMER'): ?>
                            <div class="edit-field" id="field-address">
                                <label for="address">Address</label>
                                <input type="text" id="address" name="address" value="<?php echo htmlspecialchars($customer['address']); ?>">
                            </div>
                            <?php endif; ?>

                            <div class="edit-field password-toggle">
                                <label>Password</label>
                                <button type="button" class="change-pw-btn" onclick="togglePassword()">
                                Change Password
                                </button>
                            </div>

                            <div class="edit-field password-fields" id="password-fields" style="display:none">
                                <div class="edit-field">
                                    <label for="new-pw">New Password</label>
                                    <input type="password" id="new-pw" name="new_password" placeholder="Enter new password">
                                </div>
                                <div class="edit-field">
                                    <label for="confirm-pw">Confirm New Password</label>
                                    <input type="password" id="confirm-pw" name="confirm_password" placeholder="Confirm new password">
                                </div>
                            </div>

                            <?php if ($error): ?>
                                <p class="error-msg"><?php echo $error; ?></p>
                            <?php endif; ?>
                            <button type="submit" class="save-btn">Save</button>
                        </form>
                        
                    </section>
                </section>
            </article>
        </main>

        <div id="edit-overlay" class="edit-overlay">
            <div class="edit-error-box">
                <p id="edit-error-text"></p>
                <button onclick="closeEditError()">OK</button>
            </div>
        </div>

        <script src="edit_profile.js"></script>
        <?php if ($error): ?>
            <script>
                window.addEventListener('DOMContentLoaded', function() {
                    showEditError("<?php echo $error; ?>");
                });
            </script>
        <?php endif; ?>
    </body>
</html>