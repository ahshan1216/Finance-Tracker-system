<?php
include '../database/database.php';

if (isset($_POST['category_name'])) {
    $category_name = trim($_POST['category_name']);

    // Check if category already exists
    $query_check = "SELECT * FROM category WHERE c_name = '$category_name'";
    $result_check = mysqli_query($conn, $query_check);

    if (mysqli_num_rows($result_check) > 0) {
        // Category already exists
        echo "Category already exists";
    } else {
        // Insert new category
        $query = "INSERT INTO category (c_name) VALUES ('$category_name')";
        if (mysqli_query($conn, $query)) {
            echo "Category added successfully!";
        } else {
            echo "Error adding category: " . mysqli_error($conn);
        }
    }
}
?>
