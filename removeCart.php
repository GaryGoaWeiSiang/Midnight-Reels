<?php
session_start();

if (isset($_POST['index'])) {

    $index = (int)$_POST['index'];

    if (isset($_SESSION['cart'][$index])) {

        unset($_SESSION['cart'][$index]);

        // Re-index the array
        $_SESSION['cart'] = array_values($_SESSION['cart']);
    }
}

header("Location: cart.php");
exit;

?>