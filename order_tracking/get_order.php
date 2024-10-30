<?php
include '../database/database.php';

if (isset($_POST['order_id'])) {
    $order_id = $_POST['order_id'];

    // Fetch the order and associated item details
    $query = "
        SELECT o.id, o.order_details,oi.additional_cost,oi.customer_address,oi.customer_phone,oi.customer_name,oi.additional_cost_reason, oi.product_name,oi.product_id, oi.buy_cost, oi.sell_cost, oi.quantity,oi.total, oi.category_id,oi.order_id
        FROM orders o
        JOIN order_items oi ON o.id = oi.order_id
        WHERE o.id = $order_id
    ";

    $result = $conn->query($query);
    if ($result->num_rows > 0) {
        echo json_encode($result->fetch_assoc()); // Return order data as JSON
    } else {
        echo json_encode(['error' => 'Order not found!']);
    }
} else {
    echo json_encode(['error' => 'Order ID not provided!']);
}

$conn->close();
?>
