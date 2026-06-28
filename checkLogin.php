<?php
require_once("config.php");
session_start();

try{
    if(isset($_SESSION["userID"]))
        {throw new Exception("User already login!");}

    $identifier = $_POST["identifier"] ?? "";
    $password = $_POST["password"] ?? "";
    $role = $_POST["role"] ?? "CUSTOMER";

    if($role === "CUSTOMER") {

        $sql = "SELECT * FROM Users WHERE emailAddress = ? AND userStatus='ACTIVE'";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt,"s", $identifier);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        $customer = mysqli_fetch_array($result);

        if($customer) {
            if(password_verify($password, $customer["password"])) {

                $_SESSION["userID"] = $customer["userID"];
                $_SESSION["role"] = "CUSTOMER";
                $_SESSION["username"] = $customer["username"];
                $_SESSION["success"] = "Login successful!<br>Welcome back, ".$customer["username"].".";
                if (!isset($_SESSION['cart'])) {
                    $_SESSION['cart'] = [];
                }
                header("Location: profile.php");
                exit();
            }
            else{
                throw new Exception("Incorrect password!");
            }
        }
        else{
            throw new Exception("No account registered with the entered email address!");
        }
    }

    if($role === "STAFF") {

        $sql = "SELECT Users.*, Staff.staffID
                FROM Users
                INNER JOIN Staff ON Users.userID = Staff.userID
                WHERE Staff.staffID = ? AND Users.userStatus='ACTIVE'";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $identifier);
        $stmt->execute();
        $result = $stmt->get_result();

        $staff = $result->fetch_assoc();

        if($staff) {
            if(password_verify($password, $staff["password"])) {

                $_SESSION["userID"] = $staff["userID"];
                $_SESSION["role"] = "STAFF";
                $_SESSION["staffID"] = $staff["staffID"];
                $_SESSION["username"] = $staff["username"];
                $_SESSION["success"] = "Login successful!<br>Hello, ".$staff["username"].".";
                $_SESSION["staffRole"] = $staff["role"];

                header("Location: profile.php");
                exit();
            }
            else{
                throw new Exception("Incorrect password!");
            }
    }else{
        throw new Exception("No account registered with the entered staff ID!");
    }
    }
}catch(Exception $e) {
    // FAIL
    $_SESSION["error"] = "ERROR: " . $e->getMessage();
        header("Location: login.php");
        exit();
}
?>