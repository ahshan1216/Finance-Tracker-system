<?php
include '../database/database.php';

if (isset($_POST['order_id'])) {
    $order_id = $_POST['order_id'];

    // Delete the order from the orders table
    $query = "DELETE FROM orders WHERE id = $order_id";
    if ($conn->query($query)) {
        // The corresponding order_items are automatically deleted due to foreign key constraint
        echo 'Order deleted successfully!';
    } else {
        echo 'Error deleting order: ' . $conn->error;
    }
} else {
    echo 'Order ID not provided!';
}

$conn->close();
?>
