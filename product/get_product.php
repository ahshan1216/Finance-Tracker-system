<?php
include '../database/database.php';

if (isset($_POST['product_id'])) {
    $product_id = $_POST['product_id'];

    $query = "SELECT p.*, c.c_name AS category_name FROM products p
              LEFT JOIN category c ON p.category_id = c.id
              WHERE p.id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $product_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();
        echo json_encode($product);
    } else {
        echo json_encode(['error' => 'Product not found']);
    }
}
?>
