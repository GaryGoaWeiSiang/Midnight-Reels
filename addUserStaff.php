<?php
session_start();
require_once("config.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = isset($_POST['username']) ? trim($_POST['username']) : '';
    $email = isset($_POST['emailAddress']) ? trim($_POST['emailAddress']) : '';
    $phone = isset($_POST['phoneNumber']) ? trim($_POST['phoneNumber']) : '';
    $role = isset($_POST['role']) ? trim($_POST['role']) : '';
    $status = isset($_POST['userStatus']) ? trim($_POST['userStatus']) : 'ACTIVE';
    $password = isset($_POST['password']) ? trim($_POST['password']) :'';
    
    $address = isset($_POST['address']) ? trim($_POST['address']) : '';
    $staffID = isset($_POST['staffID']) ? trim($_POST['staffID']) : '';

    $password = password_hash($password, PASSWORD_DEFAULT);

    if (empty($username) || empty($email) || empty($phone) || empty($role) || empty($password)) {
        $_SESSION["error"] = "All core fields (Email, Phone, Role) are required.";
        header("Location: ListOfUser.php");
        exit();
    }

    try {
        mysqli_begin_transaction($conn);
        
        $sqlUser = "INSERT INTO Users (username, emailAddress, password, phoneNumber, role, userStatus) VALUES (?, ?, ?, ?, ?, ?)";
        $stmtUser = mysqli_prepare($conn, $sqlUser);
        mysqli_stmt_bind_param($stmtUser, "ssssss", $username, $email, $password, $phone, $role, $status);
        
        if (!mysqli_stmt_execute($stmtUser)) {
            throw new Exception("Unable to register user profile -> " . mysqli_error($conn));
        }

        $newUserID = mysqli_insert_id($conn);
        $normalizedRole = strtoupper($role);

        if ($normalizedRole === 'CUSTOMER') {
            if (empty($address)) {
                $address = "";
            }

            $sqlCustomer = "INSERT INTO Customer (userID, address) VALUES (?, ?)";
            $stmtCustomer = mysqli_prepare($conn, $sqlCustomer);
            mysqli_stmt_bind_param($stmtCustomer, "ss", $newUserID, $address);
            
            if (!mysqli_stmt_execute($stmtCustomer)) {
                throw new Exception("Unable to save Customer profile details -> " . mysqli_error($conn));
            }

        } else if ($normalizedRole === 'STORE MANAGER' || $normalizedRole === 'ADMIN') {
            if (empty($staffID)) {
                throw new Exception("Staff ID is required for administrative/staff roles.");
            }

            $sqlCheck = "SELECT userID FROM Staff WHERE staffID = ?";
            $stmtCheck = mysqli_prepare($conn, $sqlCheck);
            mysqli_stmt_bind_param($stmtCheck, "s", $staffID);
            mysqli_stmt_execute($stmtCheck);
            mysqli_stmt_store_result($stmtCheck);
            
            if (mysqli_stmt_num_rows($stmtCheck) > 0) {
                mysqli_stmt_close($stmtCheck);
                throw new Exception("The Staff Operational ID '$staffID' is already assigned to another user.");
            }
            mysqli_stmt_close($stmtCheck);

            $sqlStaff = "INSERT INTO Staff (userID, staffID) VALUES (?, ?)";
            $stmtStaff = mysqli_prepare($conn, $sqlStaff);
            mysqli_stmt_bind_param($stmtStaff, "ss", $newUserID, $staffID);
            
            if (!mysqli_stmt_execute($stmtStaff)) {
                throw new Exception("Unable to save Staff operational details -> " . mysqli_error($conn));
            }
        }

        mysqli_commit($conn);
        $_SESSION["success"] = "User registered successfully!";
        header("Location: ListOfUser.php");
        exit();

    } catch (Exception $e) {
        mysqli_rollback($conn);
        $_SESSION["error"] = "Registration Error: " . $e->getMessage();
        header("Location: ListOfUser.php");
        exit();
    }
} else {
    header("Location: ListOfUser.php");
    exit();
}
?>