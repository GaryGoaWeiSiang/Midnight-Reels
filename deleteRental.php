<?php
session_start();
require_once("config.php");

if (isset($_POST['rentalID']) && isset($_POST['rentalID'])&&$_SESSION["role"]=="STAFF") {
    $rentalItemID = intval($_POST['rentalItemID']);
    $rentalID = intval($_POST['rentalID']);

    mysqli_begin_transaction($conn);

    try {
        $sql_find = "SELECT inventoryID FROM RentalItem WHERE rentalItemID = ?";
        $stmt_find = mysqli_prepare($conn, $sql_find);
        mysqli_stmt_bind_param($stmt_find, "i", $rentalItemID);
        mysqli_stmt_execute($stmt_find);
        $result = mysqli_stmt_get_result($stmt_find);
        
        if ($row = mysqli_fetch_assoc($result)) {
            $inventoryID = $row['inventoryID'];
            $sql_free = "UPDATE Inventory SET inventoryStatus = 'AVAILABLE' WHERE inventoryID = ?";
            $stmt_free = mysqli_prepare($conn, $sql_free);
            mysqli_stmt_bind_param($stmt_free, "i", $inventoryID);
            mysqli_stmt_execute($stmt_free);
        }

        $sql_delete = "DELETE FROM RentalItem WHERE rentalItemID = ?";
        $stmt_delete = mysqli_prepare($conn, $sql_delete);
        mysqli_stmt_bind_param($stmt_delete, "i", $rentalItemID);
        mysqli_stmt_execute($stmt_delete);

        $sql_check_empty = "SELECT COUNT(*) AS item_count FROM RentalItem WHERE rentalID = ?";
        $stmt_check = mysqli_prepare($conn, $sql_check_empty);
        mysqli_stmt_bind_param($stmt_check, "s", $rentalID);
        mysqli_stmt_execute($stmt_check);
        $result_check = mysqli_stmt_get_result($stmt_check);
        $row_check = mysqli_fetch_assoc($result_check);

        if ($row_check['item_count'] == 0) {
            $sql_del_pay = "DELETE FROM Payment WHERE rentalID = ?";
            $stmt_del_pay = mysqli_prepare($conn, $sql_del_pay);
            mysqli_stmt_bind_param($stmt_del_pay, "s", $rentalID);
            mysqli_stmt_execute($stmt_del_pay);

            $sql_del_rent = "DELETE FROM Rental WHERE rentalID = ?";
            $stmt_del_rent = mysqli_prepare($conn, $sql_del_rent);
            mysqli_stmt_bind_param($stmt_del_rent, "s", $rentalID);
            mysqli_stmt_execute($stmt_del_rent);

            $_SESSION["success"] = "The last item was removed. Entire empty rental transaction deleted.";
        }else{

        $sql_calc = "SELECT SUM(RentalItem.rentalDuration * VideoTape.videoRentalPrice) AS totalCost
                     FROM RentalItem
                     JOIN Inventory ON RentalItem.inventoryID = Inventory.inventoryID
                     JOIN VideoTape ON Inventory.videoID = VideoTape.videoID
                     WHERE RentalItem.rentalID = ?";
        $stmt_calc = mysqli_prepare($conn, $sql_calc);
        mysqli_stmt_bind_param($stmt_calc, "i", $rentalID);
        mysqli_stmt_execute($stmt_calc);
        $result_calc = mysqli_stmt_get_result($stmt_calc);
        $row_calc = mysqli_fetch_assoc($result_calc);
        
        $newPaymentSum = $row_calc['totalCost'] ?? 0.00;

        $sql_payment = "UPDATE Payment SET paymentAmount = ? WHERE rentalID = ?";
        $stmt_payment = mysqli_prepare($conn, $sql_payment);
        mysqli_stmt_bind_param($stmt_payment, "di", $newPaymentSum, $rentalID);
        mysqli_stmt_execute($stmt_payment);

        $_SESSION["success"] = "Rental item removed from rental transaction. The bill has recalculated.";
        }
        mysqli_commit($conn);
        header("Location: ListOfRental.php?v=" . time());
        exit();

    } catch (Exception $e) {
        mysqli_rollback($conn);
        $_SESSION["error"] = "Failed to remove item safely: " . $e->getMessage();
        header("Location: ListOfRental.php?v=" . time());
        exit();
    }
} else {
    $_SESSION["error"] = "You are not a staff!";
    header("Location: index.php");
    exit();
}
?>