<?php
include '../database/database.php';

if (isset($_POST['order_id']) && isset($_POST['order_details']) && isset($_POST['category_id']) && isset($_POST['product_id']) && isset($_POST['quantity']) && isset($_POST['buy_cost']) && isset($_POST['sell_cost']) && isset($_POST['total_cost'])&& isset($_POST['additional_cost_reason'])&& isset($_POST['additional_cost'])&& isset($_POST['customer_name']) && isset($_POST['customer_phone']) &&  isset($_POST['customer_address']))  {
    
    $order_id = $_POST['order_id'];
    $order_details = $conn->real_escape_string($_POST['order_details']);
    $category_id = $_POST['category_id'];
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];
    $buy_cost = $_POST['buy_cost'];
    $sell_cost = $_POST['sell_cost'];
    $total_cost = $_POST['total_cost'];
    $additional_cost = $_POST['additional_cost'];
    $additional_cost_reason = $_POST['additional_cost_reason'];
    $customer_name = $_POST['customer_name'];        
    $customer_phone = $_POST['customer_phone'];      
    $customer_address = $_POST['customer_address']; 

    // Update orders table
    $query = "UPDATE orders SET order_details = '$order_details', updated_at = NOW() WHERE id = $order_id";
    if ($conn->query($query)) {
        
        // Update order_items table
        $query = "UPDATE order_items SET product_name = (SELECT name FROM products WHERE id = $product_id), buy_cost = $buy_cost, sell_cost = $sell_cost, category_id = $category_id, additional_cost = $additional_cost, additional_cost_reason = '$additional_cost_reason' , quantity = $quantity, total = $total_cost ,customer_name='$customer_name',customer_phone='$customer_phone',customer_address='$customer_address' WHERE order_id = $order_id";
        if ($conn->query($query)) {
            echo 'Order updated successfully!';
        } else {
            echo 'Error updating order item: ' . $conn->error;
        }
    } else {
        echo 'Error updating order: ' . $conn->error;
    }
} else {
    echo 'All fields are required!';
}

$conn->close();
?>
