<?php
include '../database/database.php';

if (isset($_POST['product_id']) && isset($_POST['product_name'])) {
    $product_id = $_POST['product_id'];
    $product_name = trim($_POST['product_name']);
    $product_stock = $_POST['product_stock'];
    $buy_rate = $_POST['buy_rate'];
    $sell_rate = $_POST['sell_rate'];
    $category_id = $_POST['category_id']; // New category_id input
    $total_cost = $_POST['total_cost'];
    $add_cost = $_POST['add_cost'];
    $total_cost_re = $_POST['total_cost_re'];

    // Check for duplicate product name (excluding the current product)
    $check_query = "SELECT * FROM products WHERE name = '$product_name' AND id != '$product_id'";
    $check_result = mysqli_query($conn, $check_query);

    if (mysqli_num_rows($check_result) > 0) {
        // Duplicate product found
        echo "Product already exists with this name.";
    } else {
        // No duplicate, proceed to update the product information
        $update_query = "UPDATE products SET name = '$product_name', stock = '$product_stock', buy_rate = '$buy_rate', sell_rate = '$sell_rate', category_id = '$category_id',additional_cost='$add_cost',additional_cost_reason='$total_cost_re',total='$total_cost' WHERE id = '$product_id'";
        if (mysqli_query($conn, $update_query)) {
            echo "Product updated successfully!";
        } else {
            echo "Error updating product: " . mysqli_error($conn);
        }
    }
}
?>
