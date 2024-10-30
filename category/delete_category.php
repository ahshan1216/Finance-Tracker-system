<?php
include '../database/database.php';

if (isset($_POST['id'])) {
    $id = $_POST['id'];
    $query = "DELETE FROM category WHERE id = $id";

    if (mysqli_query($conn, $query)) {
        echo "Category deleted successfully!";
    } else {
        echo "Error deleting category: " . mysqli_error($conn);
    }
}
?>
