<?php
include '../database/database.php';

$query = "SELECT id, c_name FROM category";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) > 0) {
    $i = 1;
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>
                <td>{$i}</td>
                <td>{$row['c_name']}</td>
                <td align='center'>
                    <button class='btn btn-flat btn-primary btn-sm' onclick='editCategory({$row['id']}, \"{$row['c_name']}\")'>
                        <span class='fa fa-edit'></span> Edit
                    </button>
                    <button class='btn btn-flat btn-danger btn-sm' onclick='deleteCategory({$row['id']})'>
                        <span class='fa fa-trash'></span> Delete
                    </button>
                </td>
              </tr>";
        $i++;
    }
} else {
    echo "<tr><td colspan='3'>No categories found.</td></tr>";
}
?>
