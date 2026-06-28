<?php
    session_start();
    if(isset($_SESSION["userID"])&&!empty( $_SESSION["userID"])){
        header("Location: profile.php");
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Midnight Reels</title>

    <link rel="stylesheet" href="register.css">
    <link rel="stylesheet" href="website.css">
    <script src="website.js" defer></script>
    <script src="register.js" defer></script>
    
</head>
<body>

<header>
    <div id="titleImg">
        <img src="./img/WebsiteLogo.png" class="logo" alt="Midnight Reels Logo">
    </div>
</header>

<main>
    <section class="register-layout">

        <div class="left-section">
            <div class="register-container">
                <h1 class="register-title">Register</h1>

                <div class="toggle-container">
                    <button type="button" class="toggle-btn active" id="customerBtn" alt="Customer Button" title="Customer"> 
                        Customer 
                    </button>

                    <button type="button" class="toggle-btn inactive" id="staffBtn" alt="Staff Button" title="Staff">
                        Staff
                    </button>
                </div>

                <form class="form-box" method="POST" action="addUser.php">

                    <div class="input-group">
                        <label for="username">Username</label>
                        <input type="text" id="username" name="username" required>
                    </div>

                    <div class="input-group">
                        <label for="email">Email Address</label>
                        <input type="email" id="email" name="email" required>
                    </div>

                    <div class="input-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" required>
                    </div>

                    <div class="input-group">
                        <label for="phone">Phone Number</label>
                        <input type="tel" id="phone" name="phone">
                    </div>

                    <!-- Customer-->
                    <input type="hidden" id="role" name="role" value="Customer">

                    <div class="input-group" id="customerField">
                        <label for="address">Address</label>
                        <input type="text" id="address" name="address">
                    </div>


                    <!-- Staff-->
                    <div class="input-group" id="staffField" style="display:none;">
                        <label for="staffID">Staff ID</label>
                        <input type="text" id="staffID" name="staffID">
                    </div>

                    <button type="submit"
                            class="Register-submit-btn" alt="Register Button" title="Rgister">
                        Register
                    </button>

                    <div class="login-hint">
                        Already have an account? <a href="Login.php" alt="Login link" title="Go to Login">Login</a>
                    </div>
                </form>
            </div>
        </div>

        <div class="right-section">
            <div class="tapes">
                <img src="./img/Knows.png" class="tapes-img" alt="Knows">
                <img src="./img/TheHeartbeat.png" class="tapes-img" alt="The Heartbeat">
                <img src="./img/ThisIsHowIAm.png" class="tapes-img" alt="This Is How I Am">

            </div>
        </div>

    </section>
</main>

<footer class="footer">

    <div class="footer-item footer-one">
        Contact Us!
        <br>067-6767676
        <br>midnightreels@gmail.com
    </div>

    <div class="footer-item footer-two"></div>

    <div class="footer-item footer-three">
        7th Street, 9/5
        <br>Manus Quarter,
        <br>New Eridu.
    </div>

</footer>

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