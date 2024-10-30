<?php
include '../database/database.php';

if (isset($_POST['product_id'])) {
    $product_id = $_POST['product_id'];

    // Delete product from database
    $query = "DELETE FROM products WHERE id = '$product_id'";
    if (mysqli_query($conn, $query)) {
        echo "Product deleted successfully!";
    } else {
        echo "Error deleting product: " . mysqli_error($conn);
    }
}
?>
