
<?php
session_start();
require_once("config.php");

if (!isset($_SESSION["role"]) || $_SESSION["role"] !== "STAFF") {
    $_SESSION["error"] = "You are not a staff!.";
    header("Location: index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $rentalID        = trim($_POST['rentalID']);
    $userID          = trim($_POST['userID']);
    $videoID         = trim($_POST['videoID']);
    $rentalBeginDate = $_POST['rentalBeginDate'];
    $rentalDuration  = intval($_POST['rentalDuration']);
    $paymentMethod   = $_POST['paymentMethod'];
    $paymentStatus   = $_POST['paymentStatus'];

    if ((empty($rentalID) && empty($userID)) || empty($videoID) || $rentalDuration <= 0) {
        $_SESSION["error"] = "Video ID and valid duration are required. For new rentals, User ID is also required.";
        header("Location: ListOfRental.php");
        exit();
    }

    mysqli_begin_transaction($conn);

    try {
        if (!empty($rentalID)) {
            $sql_check_rent = "SELECT userID, rentalBeginDate FROM Rental WHERE rentalID = ?";
            $stmt_rent = mysqli_prepare($conn, $sql_check_rent);
            mysqli_stmt_bind_param($stmt_rent, "i", $rentalID);
            mysqli_stmt_execute($stmt_rent);
            $res_rent = mysqli_stmt_get_result($stmt_rent);
            
            if ($row_rent = mysqli_fetch_assoc($res_rent)) {
                $userID = $row_rent['userID']; 
                $targetRentalID = $rentalID;
            } else {
                throw new Exception("Rental ID #$rentalID was not found in the system.");
            }
        } else {
            if (empty($userID)) {
                throw new Exception("User ID is required to launch a new rental order.");
            }

            $sql_user = "SELECT userID FROM Users WHERE userID = ?";
            $stmt_user = mysqli_prepare($conn, $sql_user);
            mysqli_stmt_bind_param($stmt_user, "s", $userID);
            mysqli_stmt_execute($stmt_user);
            if (mysqli_num_rows(mysqli_stmt_get_result($stmt_user)) === 0) {
                throw new Exception("Customer User ID '$userID' does not exist.");
            }

            $sql_new_rent = "INSERT INTO Rental (rentalBeginDate, userID) VALUES (?, ?)";
            $stmt_new_rent = mysqli_prepare($conn, $sql_new_rent);
            mysqli_stmt_bind_param($stmt_new_rent, "ss", $rentalBeginDate, $userID);
            mysqli_stmt_execute($stmt_new_rent);
            $targetRentalID = mysqli_insert_id($conn);
        }

        $sql_stock = "SELECT inv.inventoryID, vt.videoRentalPrice 
                      FROM Inventory inv
                      JOIN VideoTape vt ON inv.videoID = vt.videoID
                      WHERE inv.videoID = ? AND inv.inventoryStatus = 'AVAILABLE' 
                      LIMIT 1";
        $stmt_stock = mysqli_prepare($conn, $sql_stock);
        mysqli_stmt_bind_param($stmt_stock, "s", $videoID);
        mysqli_stmt_execute($stmt_stock);
        $res_stock = mysqli_stmt_get_result($stmt_stock);

        if (!$row_stock = mysqli_fetch_assoc($res_stock)) {
            throw new Exception("No physical tape copies available for Video ID '$videoID' at this moment.");
        }

        $inventoryID = $row_stock['inventoryID'];

        $sql_item = "INSERT INTO RentalItem (rentalID, inventoryID, rentalDuration, actualReturnDate) VALUES (?, ?, ?, NULL)";
        $stmt_item = mysqli_prepare($conn, $sql_item);
        mysqli_stmt_bind_param($stmt_item, "iii", $targetRentalID, $inventoryID, $rentalDuration);
        mysqli_stmt_execute($stmt_item);

        $sql_update_inv = "UPDATE Inventory SET inventoryStatus = 'RENTED' WHERE inventoryID = ?";
        $stmt_inv = mysqli_prepare($conn, $sql_update_inv);
        mysqli_stmt_bind_param($stmt_inv, "i", $inventoryID);
        mysqli_stmt_execute($stmt_inv);

        $sql_calc_total = "SELECT SUM(ri.rentalDuration * vt.videoRentalPrice) AS currentGrandTotal
                           FROM RentalItem ri
                           JOIN Inventory inv ON ri.inventoryID = inv.inventoryID
                           JOIN VideoTape vt ON inv.videoID = vt.videoID
                           WHERE ri.rentalID = ?";
        $stmt_calc = mysqli_prepare($conn, $sql_calc_total);
        mysqli_stmt_bind_param($stmt_calc, "i", $targetRentalID);
        mysqli_stmt_execute($stmt_calc);
        $res_calc = mysqli_stmt_get_result($stmt_calc);
        $row_calc = mysqli_fetch_assoc($res_calc);
        $absoluteTotalCost = floatval($row_calc['currentGrandTotal']);

        $sql_pay_check = "SELECT paymentID FROM Payment WHERE rentalID = ?";
        $stmt_p_check = mysqli_prepare($conn, $sql_pay_check);
        mysqli_stmt_bind_param($stmt_p_check, "i", $targetRentalID);
        mysqli_stmt_execute($stmt_p_check);
        $res_p_check = mysqli_stmt_get_result($stmt_p_check);

        if (mysqli_fetch_assoc($res_p_check)) {
            $sql_update_pay = "UPDATE Payment SET paymentAmount = ?, paymentMethod = ?, paymentStatus = ? WHERE rentalID = ?";
            $stmt_up_pay = mysqli_prepare($conn, $sql_update_pay);
            mysqli_stmt_bind_param($stmt_up_pay, "dssi", $absoluteTotalCost, $paymentMethod, $paymentStatus, $targetRentalID);
            mysqli_stmt_execute($stmt_up_pay);
        } else {
            $sql_ins_pay = "INSERT INTO Payment (rentalID, paymentAmount, paymentMethod, paymentStatus) VALUES (?, ?, ?, ?)";
            $stmt_ins_pay = mysqli_prepare($conn, $sql_ins_pay);
            mysqli_stmt_bind_param($stmt_ins_pay, "idss", $targetRentalID, $absoluteTotalCost, $paymentMethod, $paymentStatus);
            mysqli_stmt_execute($stmt_ins_pay);
        }

        mysqli_commit($conn);

        $_SESSION["success"] = "Successfully processed rental entry changes!";
        header("Location: ListOfRental.php");
        exit();

    } catch (Exception $e) {
        mysqli_rollback($conn);
        
        $_SESSION["error"] = "Failed to process rental: " . $e->getMessage();
        header("Location: ListOfRental.php?v=" . time());
        exit();
    }
} else {
    header("Location: ListOfRental.php");
    exit();
}
?>