<?php
    session_start();
    include("config.php");
    mysqli_begin_transaction($conn);
    $sql=null;
    try{
    $username = $_POST["username"] ?? "";
    $email = $_POST["email"] ?? "";
    $checkEmail = mysqli_query($conn,
    "SELECT userID FROM Users WHERE emailAddress = '$email'");
    if(mysqli_num_rows($checkEmail) > 0){
        throw new Exception("This email address is already registered with an account!");
    }
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);
    $role = strtoupper($_POST["role"] ?? "CUSTOMER");
    $phone = $_POST["phone"] ?? NULL;
    $staffRole = NULL;

    if($role == "STAFF"){
        $staffID = $_POST["staffID"] ?? "";
        $checkStaff = mysqli_query($conn,
            "SELECT staffID FROM Staff WHERE staffID = '$staffID'");

            if(mysqli_num_rows($checkStaff) > 0){
                throw new Exception("This Staff ID is already registered with an account!");
            }
        if(str_contains($staffID, "ADM")){
            $staffRole = "ADMIN";
        }else if(str_contains($staffID, "STM")){
            $staffRole = "STORE MANAGER";
        }else{
            throw new Exception("Invalid Staff ID!");
        }
    }

    if($role == "CUSTOMER"){
        $sql = "INSERT INTO Users
        (username,emailAddress,password,role,phoneNumber,accountCreationDate)
        VALUES
        ('$username','$email','$password','$role','$phone',NOW())";
    }else if($role == "STAFF"){
        $sql = "INSERT INTO Users
        (username,emailAddress,password,role,phoneNumber,accountCreationDate)
        VALUES
        ('$username','$email','$password','$staffRole','$phone',NOW())";
    }

    if(!mysqli_query($conn,$sql))
    {
        throw new Exception(mysqli_error($conn));
    }
    
    $userID = mysqli_insert_id($conn);

    if($role == "CUSTOMER")
    {
        $address = $_POST["address"] ?? "";

        mysqli_query($conn,"
            INSERT INTO Customer(userID,address)
            VALUES('$userID','$address')
        ");
    }
    else if($role == "STAFF")
    { 
        mysqli_query($conn,"
            INSERT INTO Staff(userID,staffID)
            VALUES('$userID','$staffID')
        ");
    }

    mysqli_commit($conn);

    $_SESSION["userID"] = $userID;
    $_SESSION["role"] = $role;
    $_SESSION["username"] = $username;
    if($role == "STAFF"){
        $_SESSION["staffRole"] = $staffRole;
    }
    $_SESSION["success"] = "Account registerd successfully!";
    header("Location: profile.php");
    exit();
    }
    catch(Exception $e)
    {
        mysqli_rollback($conn);
        $_SESSION["error"] = "ERROR: " . $e->getMessage();
        header("Location: register.php");
        exit();
    }
?>