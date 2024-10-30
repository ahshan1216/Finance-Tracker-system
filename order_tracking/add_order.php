<?php
include '../database/database.php';

if (isset($_POST['order_details']) && 
    isset($_POST['category_id']) && 
    isset($_POST['product_id']) && 
    isset($_POST['quantity']) && 
    isset($_POST['buy_cost']) && 
    isset($_POST['sell_cost']) && 
    isset($_POST['total_cost']) && 
    isset($_POST['additional_cost_reason']) && 
    isset($_POST['additional_cost']) && 
    isset($_POST['customer_name']) &&           
    isset($_POST['customer_phone']) &&          
    isset($_POST['customer_address'])) {        
    
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

    // Insert into orders table
    $query = "INSERT INTO orders (order_details) VALUES ('$order_details')";
    if ($conn->query($query)) {
        $order_id = $conn->insert_id;  // Get the last inserted order ID

        // Insert into order_items table with total cost
        $query = "INSERT INTO order_items (order_id, product_name, buy_cost, sell_cost, category_id, quantity, total, product_id, additional_cost, additional_cost_reason, customer_name, customer_phone, customer_address) 
        VALUES ($order_id, 
                (SELECT name FROM products WHERE id = $product_id), 
                $buy_cost, 
                $sell_cost, 
                $category_id, 
                $quantity, 
                $total_cost, 
                $product_id, 
                '$additional_cost', 
                '$additional_cost_reason', 
                '$customer_name', 
                '$customer_phone', 
                '$customer_address')";

        if ($conn->query($query)) {
            echo 'Order created successfully!';
        } else {
            echo 'Error adding order item: ' . $conn->error;
        }
    } else {
        echo 'Error creating order: ' . $conn->error;
    }
} else {
    echo 'All fields are required!1';
}

$conn->close();
?>
