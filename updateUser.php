<?php
session_start();
require_once("config.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['userID'];
    $username = $_POST['username'];
    $email = $_POST['emailAddress'];
    $password = $_POST['password'];
    $phone = $_POST['phoneNumber'];
    $role = $_POST['role'];
    $status = $_POST['userStatus'];
    $address = isset($_POST['address']) ? trim($_POST['address']) : '';
    $staffID = isset($_POST['staffID']) ? trim($_POST['staffID']) : '';
    $currentPassword = $_POST['currentPassword'];

    if($_SESSION['userID']==$id){
        $_SESSION["error"] = "Cannot update currently login account!";
        header("Location: ListOfUser.php");
        exit();
    }

    if (!empty($password)) {
        $password = password_hash($password, PASSWORD_DEFAULT);
    } else {
        $password = $currentPassword;
    }

    if (empty($username) || empty($id) || empty($email) || empty($phone)) {
        $_SESSION["error"] = "Required fields cannot be left empty.";
        header("Location: ListOfUser.php");
        exit();
    }


    try{
    mysqli_begin_transaction($conn);
    $sql = "UPDATE Users SET username = ?, emailAddress = ?, password = ?, phoneNumber = ?, role = ?, userStatus = ? WHERE userID = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "ssssssi", $username, $email, $password, $phone, $role, $status, $id);
        if(!mysqli_stmt_execute($stmt)){
            throw new Exception("Unable to update User Table->".mysqli_error($conn));
        }
    $normalizedRole = strtoupper($role);

    if ($normalizedRole === 'CUSTOMER') {
            
            $sqlDeleteStaff = "DELETE FROM Staff WHERE userID = ?";
            $stmtDeleteStaff = mysqli_prepare($conn, $sqlDeleteStaff);
            mysqli_stmt_bind_param($stmtDeleteStaff, "s", $id);
            mysqli_stmt_execute($stmtDeleteStaff);

            if (!mysqli_stmt_execute($stmtDeleteStaff)) {
                throw new Exception("Unable to delete from Staff Table: " . mysqli_error($conn));
            }
            mysqli_stmt_close($stmtDeleteStaff);

            $sqlCustomer = "INSERT INTO Customer (userID, address) VALUES (?, ?) 
                        ON DUPLICATE KEY UPDATE address = ?";
            $stmtCustomer = mysqli_prepare($conn, $sqlCustomer);
            mysqli_stmt_bind_param($stmtCustomer, "sss", $id, $address, $address);
            mysqli_stmt_execute($stmtCustomer);

        } else if ($normalizedRole === 'STORE MANAGER' || $normalizedRole === 'ADMIN') {
            
            $sqlCheck = "SELECT userID FROM Staff WHERE staffID = ? AND userID != ?";
            $stmtCheck = mysqli_prepare($conn, $sqlCheck);
            mysqli_stmt_bind_param($stmtCheck, "si", $staffID, $id);
            mysqli_stmt_execute($stmtCheck);
            mysqli_stmt_store_result($stmtCheck);
            
            if (mysqli_stmt_num_rows($stmtCheck) > 0) {
                mysqli_stmt_close($stmtCheck);
                throw new Exception("The Staff ID '$staffID' is already taken by another user.");
            }
            mysqli_stmt_close($stmtCheck);
            
            $sqlDeleteCustomer = "DELETE FROM Customer WHERE userID = ?";
            $stmtDeleteCustomer = mysqli_prepare($conn, $sqlDeleteCustomer);
            mysqli_stmt_bind_param($stmtDeleteCustomer, "s", $id);
            mysqli_stmt_execute($stmtDeleteCustomer);

            $sqlStaff = "INSERT INTO Staff (userID, staffID) VALUES (?, ?) 
                         ON DUPLICATE KEY UPDATE staffID = ?";
            $stmtStaff = mysqli_prepare($conn, $sqlStaff);
            mysqli_stmt_bind_param($stmtStaff, "sss", $id, $staffID, $staffID);
            mysqli_stmt_execute($stmtStaff);
        }
        else{
            throw new Exception("Unable to Update Staff or Customer Table->".mysqli_error($conn));
        }
    
        mysqli_commit($conn);
        $_SESSION["success"] = "Edit successfully!";
        header("Location: ListOfUser.php");
        exit();

    }catch(Exception $e){
        mysqli_rollback($conn);
        $_SESSION["error"] = "Error in updating: ". $e->getMessage();
        header("Location: ListOfUser.php");
        exit();
    }

}
?>