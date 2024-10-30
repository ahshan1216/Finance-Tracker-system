<?php
include '../database/database.php';

// Check if category_id is set
$category_id = isset($_GET['category_id']) ? $_GET['category_id'] : '';

$query = "SELECT p.id, p.name, p.stock, p.buy_rate,p.total, p.sell_rate,p.additional_cost ,c.c_name AS category_name 
          FROM products p 
          JOIN category c ON p.category_id = c.id"; // Using JOIN for clarity and performance

// Add category filter to the query
if (!empty($category_id)) {
    $query .= " WHERE p.category_id = " . intval($category_id); // Use prepared statements in production
}

$result = $conn->query($query);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $productId = htmlspecialchars($row['id']);
        $productName = htmlspecialchars($row['name']);
        $productStock = htmlspecialchars($row['stock']);
        $productBuyRate = htmlspecialchars($row['buy_rate']);
        $productSellRate = htmlspecialchars($row['sell_rate']);
        $categoryName = htmlspecialchars($row['category_name']); // Correctly fetch category name
        $totalBuyRate = htmlspecialchars($row['total']);
        $addcost = htmlspecialchars($row['additional_cost']);

        echo '<tr>
                <td>' . $productId . '</td>
                <td>' . $productName . '</td>
                <td>' . $productStock . '</td>
                <td>' . $productBuyRate . '</td>
                <td>' . $productSellRate . '</td>
                <td>' . $addcost . '</td>
                <td>' . $totalBuyRate . '</td>
                <td>' . $categoryName . '</td> <!-- Correct variable usage -->
                <td align="center"> <!-- Correct quotes for align attribute -->
                    <button class="btn btn-flat btn-primary btn-sm" onclick="editProduct(' . $productId . ', \'' . $productName . '\', ' . $productStock . ', ' . $productBuyRate . ', ' . $productSellRate . ')">
                        <span class="fa fa-edit"></span> Edit
                    </button>
                    <button class="btn btn-flat btn-danger btn-sm" onclick="deleteProduct(' . $productId . ')">
                        <span class="fa fa-trash"></span> Delete
                    </button>
                </td>
              </tr>';
    }
} else {
    echo '<tr><td colspan="7" class="text-center">No products found</td></tr>';
}
?>
