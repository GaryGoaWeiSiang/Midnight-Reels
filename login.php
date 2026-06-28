<?php
require_once("config.php");
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
    <title>Login - Midnight Reels</title>

    <link rel="stylesheet" href="website.css">
    <link rel="stylesheet" href="login.css">
    <script src="website.js" defer></script>
</head>

<body>

<header>
    <div id="titleImg">
        <img src="./img/WebsiteLogo.png" class="logo" alt="Midnight Reels Logo">
    </div>
</header>

<main>
<section class="login-layout">

    <div class="left-section">
        <div class="login-container">

            <h1 class="login-title">Log In</h1>

            <div class="toggle-container">
                <button type="button" class="toggle-btn active" id="customerBtn" alt="Customer Button" title="Customer">
                    Customer
                </button>

                <button type="button" class="toggle-btn inactive" id="staffBtn" alt="Staff Button" title="Staff">
                    Staff
                </button>
            </div>

            <form class="form-box" method="POST" action="checkLogin.php">

                <input type="hidden" name="role" id="roleInput" value="CUSTOMER">

                <div class="input-group">
                    <label for="identifier" id="identifierLabel">Email</label>
                    <input type="email" id="identifier" name="identifier"
                           placeholder="Enter email" required>
                </div>

                <div class="input-group">
                    <label for="password">Password</label>
                    <input type="password" name="password" required>
                </div>

                <button type="submit" class="login-submit-btn" alt="Login Button" title="Login">
                    Login
                </button>

                <div class="register-hint">
                    Not registered? <a href="Register.php" alt="Register link" title="Go to Register">Click here</a>
                </div>

            </form>

        </div>
    </div>

    <div class="right-section">
        <div class="tapes">
            <img src="img/Knows.png" class="tapes-img" alt="Introduction Tape">
            <img src="img/TheHeartbeat.png" class="tapes-img" alt="Introduction Tape">
            <img src="img/ThisIsHowIAm.png" class="tapes-img" alt="Introduction Tape">
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
        <br>Manus Quarter
        <br>New Eridu
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

<script>
document.addEventListener("DOMContentLoaded", function () {

    const customerBtn = document.getElementById("customerBtn");
    const staffBtn = document.getElementById("staffBtn");
    const roleInput = document.getElementById("roleInput");

    const identifierLabel = document.getElementById("identifierLabel");
    const identifierInput = document.getElementById("identifier");

    function setRole(role) {

        roleInput.value = role;

        if (role === "CUSTOMER") {

            customerBtn.classList.add("active");
            staffBtn.classList.remove("active");

            customerBtn.classList.remove("inactive");
            staffBtn.classList.add("inactive");

            identifierLabel.textContent = "Email";
            identifierInput.type = "email";
            identifierInput.placeholder = "Enter email";
        }

        if (role === "STAFF") {

            staffBtn.classList.add("active");
            customerBtn.classList.remove("active");

            staffBtn.classList.remove("inactive");
            customerBtn.classList.add("inactive");

            identifierLabel.textContent = "Staff ID";
            identifierInput.type = "text";
            identifierInput.placeholder = "Enter staff ID";
        }

        identifierInput.value = "";
    }

    customerBtn.addEventListener("click", () => setRole("CUSTOMER"));
    staffBtn.addEventListener("click", () => setRole("STAFF"));

});
</script>

</body>
        <script>
            document.addEventListener("DOMContentLoaded", function () {
                reloadOnBack();
            });
        </script>
</html>

<?php
if(isset($_SESSION["error"])){
        ?>
        <script>
            document.addEventListener("DOMContentLoaded", function () {
                showError("<?php echo addslashes($_SESSION["error"]); ?>","error",null,"","Try Again");
            });
        </script>
    <?php
        unset($_SESSION["error"]);    
    }
?>