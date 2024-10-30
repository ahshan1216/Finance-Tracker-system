<?php
include '../database/database.php';

// Initialize the SQL query
$query = "
    SELECT o.id, o.order_details, oi.additional_cost, oi.additional_cost_reason, oi.customer_address, 
           oi.customer_phone, oi.customer_name, oi.product_name, oi.buy_cost, oi.sell_cost, oi.quantity, 
           c.c_name AS category_name,o.created_at as order_date
    FROM orders o
    JOIN order_items oi ON o.id = oi.order_id
    JOIN category c ON oi.category_id = c.id
    WHERE 1 = 1
";

// Apply category filter if provided
if (isset($_GET['category']) && !empty($_GET['category'])) {
    $category = $_GET['category'];
    $query .= " AND oi.category_id = '$category'";
}

// Apply order details filter if provided
if (isset($_GET['order_details']) && !empty($_GET['order_details'])) {
    $order_details = $_GET['order_details'];
    $query .= " AND o.order_details = '$order_details'";
}

// Apply phone number search if provided
if (isset($_GET['phone_number']) && !empty($_GET['phone_number'])) {
    $phone_number = $_GET['phone_number'];
    $query .= " AND oi.customer_phone LIKE '%$phone_number%'";
}

// Order by ID in descending order
$query .= " ORDER BY o.id DESC";

// Execute the query
$result = $conn->query($query);

// Output the orders as table rows
if ($result->num_rows > 0) {
    $index = 1;
    while ($row = $result->fetch_assoc()) {
        $total_additional_cost = $row['additional_cost'];
        $total_buy_cost = $row['buy_cost'] * $row['quantity'];
        $total_sell_cost = ($row['sell_cost'] * $row['quantity']) + $total_additional_cost; 

        echo '<tr>';
        echo '<td>' . $index++ . '</td>';

        // Apply background color based on order details
        $order_details = $row['order_details'];
        $color = '';
        switch($order_details) {
            case 'Credit':
                $color = 'lightgreen';
                break;
            case 'Credit Pending':
                $color = 'lightyellow';
                break;
            case 'Refund':
                $color = 'lightcoral';
                break;
            default:
                $color = 'white'; // Default background color
        }

        echo '<td style="background-color: ' . $color . ';">' . $order_details . '</td>';
        echo '<td>' . $row['category_name'] . '</td>';
        echo '<td>' . $row['product_name'] . '</td>';
        echo '<td>' . $row['quantity'] . '</td>';
        echo '<td>' . number_format($row['buy_cost'], 2) . '</td>';
        echo '<td>' . number_format($total_buy_cost, 2) . '</td>';
        echo '<td>' . number_format($row['sell_cost'], 2) . '</td>';
        echo '<td>' . number_format($row['additional_cost'], 2) . '</td>';
        
        // Button to trigger modal with customer details
        echo '
            <td>
                <button class="btn btn-info" data-toggle="modal" data-target="#customerInfoModal" 
                data-name="' . $row['customer_name'] . '" 
                data-orderdate="' . $row['order_date'] . '"
                data-phone="' . $row['customer_phone'] . '" 
                data-reason="' . $row['additional_cost_reason'] . '">
                    View Details
                </button>
            </td>';
        
        echo '<td>' . number_format($total_sell_cost, 2) . '</td>';
        echo '<td>
                <button class="btn btn-primary edit-btn" data-id="' . $row['id'] . '">Edit</button>
                <button class="btn btn-danger delete-btn" data-id="' . $row['id'] . '">Delete</button>
              </td>';
        echo '</tr>';
    }
} else {
    echo '<tr><td colspan="12">No orders found</td></tr>';
}

$conn->close();
?>
