<?php
session_start();
require_once("config.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $rentalID         = isset($_POST['rentalID']) ? intval($_POST['rentalID']) : 0;
    $rentalItemID     = isset($_POST['rentalItemID']) ? intval($_POST['rentalItemID']) : 0;
    $beginDate        = trim($_POST['rentalBeginDate']);
    $userID           = trim($_POST['userID']);
    $videoID          = trim($_POST['videoID']);
    $status           = trim($_POST['paymentStatus']);
    $paymentMethod    = trim($_POST['paymentMethod']);
    $duration         = intval($_POST['rentalDuration']); 
    $actualReturnDate = trim($_POST['actualReturnDate']);

    if (empty($actualReturnDate)) {
        $actualReturnDate = null;
    }

    if ($rentalID <= 0 || $rentalItemID <= 0 || empty($videoID) || $duration <= 0) {
        $_SESSION["error"] = "Error: Provided data insuficient for process.";
        header("Location: ListOfRental.php");
        exit();
    }

    mysqli_begin_transaction($conn);

    try {
        $sql1 = "UPDATE Rental SET rentalBeginDate = ?, userID = ? WHERE rentalID = ?";
        $stmt1 = mysqli_prepare($conn, $sql1);
        mysqli_stmt_bind_param($stmt1, "ssi", $beginDate, $userID, $rentalID);
        if (!mysqli_stmt_execute($stmt1)) {
            throw new Exception("Rental table update failed.");
        }

        $sql_current = "SELECT inv.videoID, ri.inventoryID 
                        FROM RentalItem ri
                        JOIN Inventory inv ON ri.inventoryID = inv.inventoryID 
                        WHERE ri.rentalItemID = ?";
        $stmt_current = mysqli_prepare($conn, $sql_current);
        mysqli_stmt_bind_param($stmt_current, "i", $rentalItemID);
        mysqli_stmt_execute($stmt_current);
        $result_current = mysqli_stmt_get_result($stmt_current);
        
        if (!$row_current = mysqli_fetch_assoc($result_current)) {
            throw new Exception("Rental item #$rentalItemID could not be found.");
        }

        $currentVideoID     = $row_current['videoID'];
        $currentInventoryID = $row_current['inventoryID'];

        $cleanVideoInput = str_replace(' ', '', $videoID);
        $inputArray = explode(',', $cleanVideoInput);

        if (!in_array($currentVideoID, $inputArray)) {
            
            $targetNewVideoID = $inputArray[0];

            $sql_inv = "SELECT inventoryID FROM Inventory WHERE videoID = ? AND inventoryStatus = 'AVAILABLE' LIMIT 1";
            $stmt_inv = mysqli_prepare($conn, $sql_inv);
            mysqli_stmt_bind_param($stmt_inv, "s", $targetNewVideoID);
            mysqli_stmt_execute($stmt_inv);
            $result_inv = mysqli_stmt_get_result($stmt_inv);
            
            if ($row_inv = mysqli_fetch_assoc($result_inv)) {
                $newInventoryID = $row_inv['inventoryID'];

                $sql_update_item = "UPDATE RentalItem SET inventoryID = ? WHERE rentalItemID = ?";
                $stmt_item = mysqli_prepare($conn, $sql_update_item);
                mysqli_stmt_bind_param($stmt_item, "ii", $newInventoryID, $rentalItemID);
                mysqli_stmt_execute($stmt_item);

                $sql_free_old = "UPDATE Inventory SET inventoryStatus = 'AVAILABLE' WHERE inventoryID = ?";
                $stmt_free = mysqli_prepare($conn, $sql_free_old);
                mysqli_stmt_bind_param($stmt_free, "i", $currentInventoryID);
                mysqli_stmt_execute($stmt_free);

                $sql_lock_new = "UPDATE Inventory SET inventoryStatus = 'RENTED' WHERE inventoryID = ?";
                $stmt_lock = mysqli_prepare($conn, $sql_lock_new);
                mysqli_stmt_bind_param($stmt_lock, "i", $newInventoryID);
                mysqli_stmt_execute($stmt_lock);

                $currentInventoryID = $newInventoryID;

            } else {
                throw new Exception("All physical system copies for Video ID '$targetNewVideoID' are currently out of stock.");
            }
        }

        $sql2 = "UPDATE RentalItem SET actualReturnDate = ?, rentalDuration = ? WHERE rentalItemID = ?";
        $stmt2 = mysqli_prepare($conn, $sql2);
        mysqli_stmt_bind_param($stmt2, "sii", $actualReturnDate, $duration, $rentalItemID);
        if (!mysqli_stmt_execute($stmt2)) {
            throw new Exception("Failed updating duration/return tracking rows.");
        }

        if ($actualReturnDate !== null) {
            $sql_release = "UPDATE Inventory SET inventoryStatus = 'AVAILABLE' WHERE inventoryID = ?";
            $stmt_release = mysqli_prepare($conn, $sql_release);
            mysqli_stmt_bind_param($stmt_release, "i", $currentInventoryID);
            mysqli_stmt_execute($stmt_release);
        } else {
            $sql_retain = "UPDATE Inventory SET inventoryStatus = 'RENTED' WHERE inventoryID = ?";
            $stmt_retain = mysqli_prepare($conn, $sql_retain);
            mysqli_stmt_bind_param($stmt_retain, "i", $currentInventoryID);
            mysqli_stmt_execute($stmt_retain);
        }

        $sql_calc = "SELECT SUM(ri.rentalDuration * vt.videoRentalPrice) AS totalCost
                     FROM RentalItem ri
                     JOIN Inventory inv ON ri.inventoryID = inv.inventoryID
                     JOIN VideoTape vt ON inv.videoID = vt.videoID
                     WHERE ri.rentalID = ?";
                     
        $stmt_calc = mysqli_prepare($conn, $sql_calc);
        mysqli_stmt_bind_param($stmt_calc, "i", $rentalID);
        mysqli_stmt_execute($stmt_calc);
        $result_calc = mysqli_stmt_get_result($stmt_calc);
        $row_calc = mysqli_fetch_assoc($result_calc);
        
        $paymentSum = isset($row_calc['totalCost']) ? floatval($row_calc['totalCost']) : 0.00;

        $sql3 = "UPDATE Payment SET paymentAmount = ?, paymentStatus = ?, paymentMethod = ? WHERE rentalID = ?";
        $stmt3 = mysqli_prepare($conn, $sql3);
        mysqli_stmt_bind_param($stmt3, "dssi", $paymentSum, $status, $paymentMethod, $rentalID);
        if (!mysqli_stmt_execute($stmt3)) {
            throw new Exception("Accounting invoice updates failed to commit cleanly.");
        }

        mysqli_commit($conn);
        $_SESSION["success"] = "Changes saved and Rental updated successfully!";
        header("Location: ListOfRental.php");
        exit();

    } catch (Exception $e) {
        mysqli_rollback($conn);
        $_SESSION["error"] = "Error in updating: " . $e->getMessage();
        header("Location: ListOfRental.php");
        exit();
    }
} else {
    header("Location: ListOfRental.php");
    exit();
}
?>
