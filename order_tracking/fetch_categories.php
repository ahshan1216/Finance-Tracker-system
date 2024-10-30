<?php
include '../database/database.php';

// Fetch all categories
$query = "SELECT * FROM category ORDER BY c_name ASC";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    $categories = array();
    while ($row = $result->fetch_assoc()) {
        $categories[] = $row;
    }
    echo json_encode($categories);  // Return categories as JSON
} else {
    echo 'No categories found';
}

$conn->close();
?>
