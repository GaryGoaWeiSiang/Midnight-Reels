<?php
session_start();

if (!isset($_SESSION['userID'])) {
    header("Location: login.php");
    exit();
}

$userID = $_SESSION['userID'];
$role   = $_SESSION['role'];
$staffRole = $_SESSION['staffRole'];

include("config.php");

mysqli_begin_transaction($conn);
try{

        $hashedNewPw = null;
        if (!empty($_POST['new_password'])) {
            $newPw     = trim($_POST['new_password']);
            $confirmPw = trim($_POST['confirm_password']);

            // Fetch current password hash to check for duplication
            $sqlPw  = "SELECT password FROM Users WHERE userID = ?";
            $stmtPw = mysqli_prepare($conn, $sqlPw);
            mysqli_stmt_bind_param($stmtPw, "i", $userID);
            mysqli_stmt_execute($stmtPw);
            $resultPw = mysqli_stmt_get_result($stmtPw);
            $oldPwHash = mysqli_fetch_assoc($resultPw)['password'];
            mysqli_stmt_close($stmtPw);

            if ($newPw !== $confirmPw) {
                throw new Exception("Confirm Password do not match!");
            } else if (password_verify($newPw, $oldPwHash)) {
                throw new Exception("Same password entered!");
            }

            $hashedNewPw = password_hash($newPw, PASSWORD_DEFAULT);
        }

        $username = trim($_POST['username']);
        $email    = trim($_POST['email']);

        if ($role === 'CUSTOMER' && isset($_POST['phone'])) {
            $phone = trim($_POST['phone']);
            
            // Construct query dynamically based on whether password changed
            if ($hashedNewPw) {
                $sql = "UPDATE Users SET username = ?, emailAddress = ?, phoneNumber = ?, password = ? WHERE userID = ?";
                $stmt = mysqli_prepare($conn, $sql);
                mysqli_stmt_bind_param($stmt, "ssssi", $username, $email, $phone, $hashedNewPw, $userID);
            } else {
                $sql = "UPDATE Users SET username = ?, emailAddress = ?, phoneNumber = ? WHERE userID = ?";
                $stmt = mysqli_prepare($conn, $sql);
                mysqli_stmt_bind_param($stmt, "sssi", $username, $email, $phone, $userID);
            }
        } else {
            if ($hashedNewPw) {
                $sql = "UPDATE Users SET username = ?, emailAddress = ?, password = ? WHERE userID = ?";
                $stmt = mysqli_prepare($conn, $sql);
                mysqli_stmt_bind_param($stmt, "sssi", $username, $email, $hashedNewPw, $userID);
            } else {
                $sql = "UPDATE Users SET username = ?, emailAddress = ? WHERE userID = ?";
                $stmt = mysqli_prepare($conn, $sql);
                mysqli_stmt_bind_param($stmt, "ssi", $username, $email, $userID);
            }
        }

        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception("Unable to update Users profile data.");
        }
        mysqli_stmt_close($stmt);


        if (($role === 'STAFF') && isset($_POST['staffid'])) {
            $staffid = trim($_POST['staffid']);
            $sql5 = "UPDATE Staff SET staffID = ? WHERE userID = ?";
            $stmt5   = mysqli_prepare($conn, $sql5);
            mysqli_stmt_bind_param($stmt5, "si", $staffid, $userID);
            if (!mysqli_stmt_execute($stmt5)) {
                throw new Exception("Unable to update Staff identity details.");
            }
            mysqli_stmt_close($stmt5);
        }

        if ($role === 'CUSTOMER' && isset($_POST['address'])) {
            $address = trim($_POST['address']);
            $sql2    = "UPDATE Customer SET address = ? WHERE userID = ?";
            $stmt2   = mysqli_prepare($conn, $sql2);
            mysqli_stmt_bind_param($stmt2, "si", $address, $userID);
            if (!mysqli_stmt_execute($stmt2)) {
                throw new Exception("Unable to update Customer address records.");
            }
            mysqli_stmt_close($stmt2);
        }

        mysqli_commit($conn);
        $_SESSION["success"] = "Profile updated successfully!";
        header("Location: profile.php");
        exit();
}catch(Exception $e){
    mysqli_rollback($conn);
    $_SESSION["error"] = "Edit Profile Unsuccessfull: ".$e->getMessage();
    header("Location: profile.php");
    exit();
}
?>