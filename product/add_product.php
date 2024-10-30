<?php
include '../database/database.php';

if (isset($_POST['product_name'])) {
    $product_name = trim($_POST['product_name']);
    $product_stock = $_POST['product_stock'];
    $buy_rate = $_POST['buy_rate'];
    $sell_rate = $_POST['sell_rate'];
    $category_id = $_POST['category_id'];
    $total_cost = $_POST['total_cost'];
    $add_cost = $_POST['add_cost'];
    $total_cost_re = $_POST['total_cost_re'];
if($category_id)
{
    // Check if the product already exists
    $query_check = "SELECT * FROM products WHERE name = '$product_name'";
    $result_check = mysqli_query($conn, $query_check);

    if (mysqli_num_rows($result_check) > 0) {
        // Product already exists
        echo "Product already exists";
    } else {
        // Insert new product with category_id
        $query = "INSERT INTO products (name, stock, buy_rate, sell_rate, category_id,total,additional_cost,additional_cost_reason) VALUES ('$product_name', '$product_stock', '$buy_rate', '$sell_rate', '$category_id','$total_cost','$add_cost','$total_cost_re')";
        if (mysqli_query($conn, $query)) {
            echo "Product added successfully!";
        } else {
            echo "Error adding product: " . mysqli_error($conn);
        }
    }
} else {
    echo "Please Select Category";
}
}
?>
