<?php
session_start();

// Database credentials
include '../database/database.php';

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: ../auth/'); // Redirect to the admin dashboard or another page
    exit(); // Ensure no further code is executed
}
// Fetch categories from the database
$query = "SELECT * FROM category";
$result = $conn->query($query);

?>


<!DOCTYPE html>
<html lang="en" class="" style="height: auto;">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Budget and Expense Tracker System - PHP</title>
    <link rel="icon" href="http://localhost/expense_budget/uploads/1627606920_modeylogo.jpg" />

    <!-- Font Awesome -->
    <link rel="stylesheet" href="css/fontawesome-free/css/all.min.css">



    <!-- Theme style -->
    <link rel="stylesheet" href="css/dist/css/adminlte.css">
    <link rel="stylesheet" href="css/dist/css/custom.css">
    <!-- overlayScrollbars -->
    <link rel="stylesheet"
        href="css/dist/css/OverlayScrollbars.min.css">


    <!-- jQuery -->
    <script src="css/jquery/jquery.min.js"></script>


</head>

<body class="sidebar-mini layout-fixed control-sidebar-slide-open layout-navbar-fixed sidebar-mini-md sidebar-mini-xs"
    data-new-gr-c-s-check-loaded="14.991.0" data-gr-ext-installed="" style="height: auto;">
    <div class="wrapper">
        <style>
            .user-img {
                position: absolute;
                height: 27px;
                width: 27px;
                object-fit: cover;
                left: -7%;
                top: -12%;
            }

            .btn-rounded {
                border-radius: 50px;
            }
        </style>
        <!-- Navbar -->
        <nav
            class="main-header navbar navbar-expand navbar-dark bg-navy shadow border border-light border-top-0  border-left-0 border-right-0 text-sm">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                </li>
                <li class="nav-item d-none d-sm-inline-block">
                    <a href="http://localhost/expense_budget/" class="nav-link">Finance Tracker system - Admin</a>
                </li>
            </ul>
            <!-- Right navbar links -->
            <ul class="navbar-nav ml-auto">

                <!-- Messages Dropdown Menu -->
                <li class="nav-item">
                    <div class="btn-group nav-link">
                        <button type="button" class="btn btn-rounded badge badge-light dropdown-toggle dropdown-icon"
                            data-toggle="dropdown">
                            <span><img src="http://localhost/expense_budget/uploads/1624240500_avatar.png"
                                    class="img-circle elevation-2 user-img" alt="User Image"></span>
                            <span class="ml-3">Adminstrator Admin</span>
                            <span class="sr-only">Toggle Dropdown</span>
                        </button>
                        <div class="dropdown-menu" role="menu">
                            <a class="dropdown-item" href="http://localhost/expense_budget/admin/?page=user"><span
                                    class="fa fa-user"></span> My Account</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item"
                                href="http://localhost/expense_budget//classes/Login.php?f=logout"><span
                                    class="fas fa-sign-out-alt"></span> Logout</a>
                        </div>
                    </div>
                </li>
                <li class="nav-item">

                </li>

            </ul>
        </nav>
        <!-- /.navbar -->
        <style>
            .main-sidebar a {
                color: white !important;
            }
        </style>
        <!-- Main Sidebar Container -->
              <?php include '../slidebar/slidebar.php'; ?>
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper pt-3">
    <section class="content text-dark">
        <div class="container-fluid">
            <div class="card card-outline card-primary">
            <div class="card-header">
    <h3 class="card-title">Product Management</h3>
    <div class="card-tools">
        <!-- Dropdown for category selection -->
        <select id="category_filter" class="form-control" style="width: 200px; display: inline-block;">
            <option value="">All Categories</option>
            <?php
            while ($row = $result->fetch_assoc()) {
                echo '<option value="' . $row['id'] . '">' . $row['c_name'] . '</option>';
            }
            ?>
        </select>
        <a href="javascript:void(0)" id="manage_product" class="btn btn-flat btn-sm btn-primary" data-toggle="modal" data-target="#addProductModal">
            <span class="fas fa-plus"></span> Add Product
        </a>
    </div>
</div>

                <div class="card-body">
                    <table class="table table-bordered table-stripped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Product Name</th>
                                <th>Stock</th>
                                <th>Buy_rate</th>
                                <th>Sell_rate</th>
                                <th>Additional_Cost</th>
                                <th>Total Buy Cost</th>
                                <th>Category</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="product_list">
                            <!-- Categories will be dynamically loaded here -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>
<?php
function fetchCategories($conn) {
    $categories_query = "SELECT * FROM category";
    $categories_result = $conn->query($categories_query);
    $categories_options = '';
    while ($row = $categories_result->fetch_assoc()) {
        $categories_options .= '<option value="' . $row['id'] . '">' . $row['c_name'] . '</option>';
    }
    return $categories_options;
}
?>

<!-- Bootstrap Modal for Adding/Editing Product -->
<div class="modal fade" id="addProductModal" tabindex="-1" role="dialog" aria-labelledby="addProductModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addProductModalLabel">Add Product</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="add_product_form">
                    <input type="hidden" id="product_id" name="product_id">
                    <div class="form-group">
                        <label for="product_name" class="col-form-label">Product Name:</label>
                        <input type="text" class="form-control" id="product_name" name="product_name" required>
                    </div>
                    <div class="form-group">
                        <label for="product_stock" class="col-form-label">Stock:</label>
                        <input type="number" class="form-control" id="product_stock" name="product_stock" required>
                    </div>
                    <div class="form-group">
                        <label for="buy_rate" class="col-form-label">Buy Rate:</label>
                        <input type="number" step="0.01" class="form-control" id="buy_rate" name="buy_rate" required>
                    </div>
                    <div class="form-group">
                        <label for="sell_rate" class="col-form-label">Sell Rate:</label>
                        <input type="number" step="0.01" class="form-control" id="sell_rate" name="sell_rate" required>
                    </div>
                    <div class="form-group">
                        <label for="category_id" class="col-form-label">Category:</label>
                        <select class="form-control" id="category_id" name="category_id" required>
                            <option value="">Select a Category</option>
                            <?php echo fetchCategories($conn); ?>

                        </select>
                    </div>
                    <div class="form-group">
                                <label for="additional_cost" class="col-form-label">Additional Cost:</label>
                                <input type="number" step="0.01" class="form-control" id="additional_cost"
                                    name="additional_cost" required>
                            </div>

                            <div class="form-group">
                                <label for="additional_cost_reason" class="col-form-label">Reason for Additional
                                    Cost:</label>
                                <input type="text" class="form-control" id="additional_cost_reason"
                                    name="additional_cost_reason">
                            </div>
                    <div class="form-group">
                                <label for="total_cost" class="col-form-label">Total Cost (Sell):</label>
                                <input type="number" step="0.01" class="form-control" id="total_cost" name="total_cost"
                                    readonly>
                            </div>
                    <span id="product_error_msg" class="text-danger"></span>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="save_product">Save Product</button>
            </div>
        </div>
    </div>
</div>



       
    </div>
    <!-- ./wrapper -->

    <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
    <script>
        $.widget.bridge('uibutton', $.ui.button)
    </script>
    <!-- Bootstrap 4 -->
    <script src="css/dist/js/bootstrap.bundle.min.js"></script>




<!-- JavaScript for Add/Edit/Delete Product -->
<script>

function calculateTotalCost() {
    var buyRate = parseFloat($('#buy_rate').val()) || 0; // Get the buy rate, default to 0 if empty
    var stock = parseInt($('#product_stock').val()) || 0; // Get the stock, default to 0 if empty
    var additionalCost = parseFloat($('#additional_cost').val()) || 0; // Get the additional cost, default to 0 if empty

    // Ensure all values are valid numbers
    if (!isNaN(buyRate) && !isNaN(stock) && !isNaN(additionalCost)) {
        var totalCost = (buyRate * stock) + additionalCost; // Calculate total cost
        $('#total_cost').val(totalCost.toFixed(2)); // Set the total cost in the form field
    } else {
        $('#total_cost').val(''); // Clear the total cost if any value is invalid
    }
}




    // Delete product function
function deleteProduct(product_id) {
    if (confirm('Are you sure you want to delete this product?')) {
        $.ajax({
            url: 'delete_product.php',
            method: 'POST',
            data: { product_id: product_id },
            success: function(response) {
                alert(response);
                loadProducts();  // Refresh product list after deletion
            },
            error: function(xhr, status, error) {
                console.error('Error deleting product:', error);
                alert('An error occurred while deleting the product. Please try again.');
            }
        });
    }
}

  // Function to load the products based on category (or all if no category is selected)
function loadProducts(category_id = '') {
    $.ajax({
        url: 'fatch_product.php',
        method: 'GET',
        data: { category_id: category_id }, // Send the selected category ID
        success: function(data) {
            $('#product_list').html(data);
            
            // Attach event listener to the edit buttons (after products are loaded)
            $('.edit-btn').on('click', function() {
                var product_id = $(this).data('id');
                editProduct(product_id);
            });
        }
    });
}

// Function to handle opening the modal and populating the fields for editing
function editProduct(product_id) {
    $.ajax({
        url: 'get_product.php', // Fetch product data for the specific product_id
        method: 'POST',
        data: { product_id: product_id },
        success: function(response) {
            console.log(response); // Log the response for debugging
            
            var product;
            try {
                product = JSON.parse(response);  // Try to parse the response
            } catch (error) {
                console.error("Error parsing JSON:", error);
                alert("Failed to load product data. Please try again.");
                return;
            }

            // Log the parsed product data for debugging
            console.log(product);

            // Check if product data is valid before populating the form
            if (product && product.id) {
                $('#product_id').val(product.id);
                $('#product_name').val(product.name);
                $('#product_stock').val(product.stock);
                $('#buy_rate').val(product.buy_rate);
                $('#sell_rate').val(product.sell_rate);
                $('#category_id').val(product.category_id);

                $('#additional_cost').val(product.additional_cost);
                $('#additional_cost_reason').val(product.additional_cost_reason);
                $('#total_cost').val(product.total);

                // Update modal title to "Edit Product"
                $('#addProductModalLabel').text('Edit Product');
                
                // Show the modal
                $('#addProductModal').modal('show');
            } else {
                alert('Invalid product data received.');
            }
        },
        error: function(xhr, status, error) {
            console.error('Error fetching product data:', error);
            alert('An error occurred while fetching product data. Please try again.');
        }
    });
}

$(document).ready(function() {
    // Load the product list when the page loads
    loadProducts(); // Load products initially
 // Calculate total cost when the relevant fields are changed
 $('#buy_rate, #product_stock, #additional_cost').on('input', function () {
        calculateTotalCost();
    });
    // Open "Add Product" modal and clear the form
    $('#manage_product').on('click', function() {
        $('#add_product_form')[0].reset(); // Clear the form
        $('#product_id').val(''); // Ensure product_id is cleared
        $('#addProductModalLabel').text('Add Product'); // Set modal title
        $('#product_error_msg').text(''); // Clear error messages
        $('#addProductModal').modal('show'); // Show modal
    });

    // Function to handle saving the product (Add/Edit)
    $('#save_product').on('click', function() {
        var product_id = $('#product_id').val();
        var product_name = $('#product_name').val();
        var product_stock = $('#product_stock').val();
        var buy_rate = $('#buy_rate').val();
        var sell_rate = $('#sell_rate').val();
        var category_id = $('#category_id').val();
        var total_cost=$('#total_cost').val();
        var add_cost=$('#additional_cost').val();
        var total_cost_re=$('#additional_cost_reason').val();

        // Clear any previous error message
        $('#product_error_msg').text('');

        $.ajax({
            url: product_id ? 'edit_product.php' : 'add_product.php', // Use edit_product.php for editing, add_product.php for adding
            method: 'POST',
            data: {
                product_id: product_id,
                product_name: product_name,
                product_stock: product_stock,
                buy_rate: buy_rate,
                sell_rate: sell_rate,
                category_id: category_id ,// Include category ID in the data
                total_cost:total_cost,
                add_cost:add_cost,
                total_cost_re:total_cost_re

            },
            success: function(response) {
                if (response === 'Product already exists') {
                    $('#product_error_msg').text(response);
                } else {
                    alert(response);
                    $('#addProductModal').modal('hide');
                    loadProducts();  // Refresh product list
                }
            }
        });
    });
});
// After the product list is loaded, bind the delete function to the delete buttons
$('.delete-btn').on('click', function() {
    var product_id = $(this).data('id');  // Get product ID from the data-id attribute
    deleteProduct(product_id);  // Call the delete function
});

$('#quantity').on('input', function () {
            calculateTotalCost();
        });
        $('#additional_cost').on('input', function () {
            calculateTotalCost();
        });
</script>

<script src="css/dist/js/adminlte.js"></script>


</body>

</html>