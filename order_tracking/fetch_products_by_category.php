<?php
include '../database/database.php';

if (isset($_GET['category_id'])) {
    $category_id = $_GET['category_id'];
    
    // Fetch products from the products table for the given category
    $query = "SELECT * FROM products WHERE category_id = $category_id";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $products = array();
        while ($row = $result->fetch_assoc()) {
            $products[] = $row;
        }
        echo json_encode($products);  // Return products as JSON
    } else {
        echo json_encode(array());  // No products found
    }
} else {
    echo 'Category ID not provided!';
}

$conn->close();
?>
