<?php
include '../database/database.php';

if (isset($_POST['id']) && isset($_POST['category_name'])) {
    $id = $_POST['id'];
    $category_name = trim($_POST['category_name']);

    // Check for duplicate category name (excluding the current category)
    $check_query = "SELECT * FROM category WHERE c_name = '$category_name' AND id != $id";
    $check_result = mysqli_query($conn, $check_query);

    if (mysqli_num_rows($check_result) > 0) {
        // Duplicate category found
        echo "Category already exists with this name.";
    } else {
        // No duplicate, proceed to update the category information
        $update_query = "UPDATE category SET c_name = '$category_name' WHERE id = $id";
        if (mysqli_query($conn, $update_query)) {
            echo "Category updated successfully!";
        } else {
            echo "Error updating category: " . mysqli_error($conn);
        }
    }
}
?>
