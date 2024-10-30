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
    <title>Finance Tracker system</title>
    <link rel="icon" href="http://localhost/expense_budget/uploads/1627606920_modeylogo.jpg" />

    <!-- Font Awesome -->
    <link rel="stylesheet" href="css/fontawesome-free/css/all.min.css">



    <!-- Theme style -->
    <link rel="stylesheet" href="css/dist/css/adminlte.css">
    <link rel="stylesheet" href="css/dist/css/custom.css">
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="css/dist/css/OverlayScrollbars.min.css">


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
                            
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item"
                                href="../logout/"><span
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
                            <h3 class="card-title">Order Tracking</h3>
                            <div class="card-tools">
                                <!-- Dropdown for category selection -->
                                <select id="category_filter" class="form-control"
                                    style="width: 200px; display: inline-block;">
                                    <option value="">All Categories</option>
                                    <?php
                                    while ($row = $result->fetch_assoc()) {
                                        echo '<option value="' . $row['id'] . '">' . $row['c_name'] . '</option>';
                                    }
                                    ?>
                                </select>

                                <!-- Dropdown for order details filter -->
                                <select id="order_details_filter" class="form-control"
                                    style="width: 200px; display: inline-block;">
                                    <option value="">All Order Details</option>
                                    <option value="Credit">Credit</option>
                                    <option value="Credit Pending">Credit Pending</option>
                                    <option value="Refund">Refund</option>
                                </select>

                                <!-- Search field for phone number -->
                                <input type="text" id="phone_search" class="form-control"
                                    placeholder="Search by Phone Number" style="width: 200px; display: inline-block;">

                                <!-- Add Product Button -->
                                <a href="javascript:void(0)" id="manage_product" class="btn btn-flat btn-sm btn-primary"
                                    data-toggle="modal" data-target="#addOrderModal">
                                    <span class="fas fa-plus"></span> Add Product
                                </a>
                            </div>
                        </div>

                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="table-responsive">
                                        <div class="card-body">
                                            <table class="table table-bordered table-stripped">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Order Details</th>
                                                        <th>Category</th>
                                                        <th>Product Name</th>
                                                        <th>Qty</th> <!-- New column for Quantity -->
                                                        <th>Buying Cost</th>
                                                        <th>Total Buying Cost</th>
                                                        <!-- New column for total cost (Buying Cost * Quantity) -->
                                                        <th>Selling Cost</th>
                                                        <th>Extra Cost</th>
                                                        <th>More Info</th>

                                                        <th>Total Selling Cost</th>
                                                        <!-- Optional: Add total selling cost if you want to calculate it -->
                                                        <th>Action</th>
                                                    </tr>

                                                </thead>
                                                <tbody id="order_list">
                                                    <!-- Categories will be dynamically loaded here -->
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
        <!-- Bootstrap Modal for Adding/Editing Order -->
        <div class="modal fade" id="addOrderModal" tabindex="-1" role="dialog" aria-labelledby="addOrderModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addOrderModalLabel">Create Order</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="add_order_form">
                            <input type="hidden" id="order_id" name="order_id">

                            <div class="form-group">
                                <label for="order_details" class="col-form-label">Order Details:</label>
                                <select class="form-control" id="order_details" name="order_details" required>
                                    <option value="">Select Order Detail</option>
                                    <option value="Credit">Credit</option>
                                    <option value="Credit Pending">Credit Pending</option>
                                    <option value="Refund">Refund</option>
                                </select>
                            </div>
                            <!-- New Fields for Customer Information -->
                            <div class="form-group">
                                <label for="customer_name" class="col-form-label">Customer Name:</label>
                                <input type="text" class="form-control" id="customer_name" name="customer_name"
                                    required>
                            </div>

                            <div class="form-group">
                                <label for="customer_phone" class="col-form-label">Customer Phone Number:</label>
                                <input type="text" class="form-control" id="customer_phone" name="customer_phone"
                                    required>
                            </div>

                            <div class="form-group">
                                <label for="customer_address" class="col-form-label">Customer Address:</label>
                                <input type="text" class="form-control" id="customer_address" name="customer_address"
                                    required>
                            </div>
                            <div class="form-group">
                                <label for="category_id" class="col-form-label">Category:</label>
                                <select class="form-control" id="category_id" name="category_id" required>
                                    <option value="">Select a Category</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="product_id" class="col-form-label">Product Name:</label>
                                <select class="form-control" id="product_id" name="product_id" required>
                                    <option value="">Select a Product</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="quantity" class="col-form-label">Quantity:</label>
                                <input type="number" class="form-control" id="quantity" name="quantity" min="1"
                                    required>
                            </div>

                            <div class="form-group">
                                <label for="buy_cost" class="col-form-label">Buying Cost:</label>
                                <input type="number" step="0.01" class="form-control" id="buy_cost" name="buy_cost"
                                    required readonly>
                            </div>

                            <div class="form-group">
                                <label for="sell_cost" class="col-form-label">Selling Cost:</label>
                                <input type="number" step="0.01" class="form-control" id="sell_cost" name="sell_cost"
                                    required readonly>
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

                            <span id="order_error_msg" class="text-danger"></span>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" id="save_order">Save Order</button>
                    </div>
                </div>
            </div>
        </div>


        <!-- Bootstrap Modal -->
        <div class="modal fade" id="customerInfoModal" tabindex="-1" role="dialog"
            aria-labelledby="customerInfoModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="customerInfoModalLabel">Customer Details</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p><strong>Customer Name:</strong> <span id="customerName"></span></p>
                        <p><strong>Customer Phone Number:</strong> <span id="customerPhone"></span></p>
                        <p><strong>Additional Cost Reason:</strong> <span id="additionalCostReason"></span></p>
                        <p><strong>Order Created Date:</strong> <span id="orderdate"></span></p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <script>
            // jQuery to populate modal with customer details
            $('#customerInfoModal').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget); // Button that triggered the modal
                var customerName = button.data('name'); // Extract info from data-* attributes
                var customerPhone = button.data('phone');
                var additionalCostReason = button.data('reason');
                var order_date = button.data('orderdate');

                // Update the modal's content
                var modal = $(this);
                modal.find('#customerName').text(customerName);
                modal.find('#customerPhone').text(customerPhone);
                modal.find('#additionalCostReason').text(additionalCostReason);
                modal.find('#orderdate').text(order_date);
            });

        </script>



        
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
        // Function to load categories into the dropdown
        function loadCategories() {
            $.ajax({
                url: 'fetch_categories.php',
                method: 'GET',
                success: function (response) {
                    var categories = JSON.parse(response);
                    $('#category_id').empty().append('<option value="">Select a Category</option>');
                    $.each(categories, function (index, category) {
                        $('#category_id').append('<option value="' + category.id + '">' + category.c_name + '</option>');
                    });
                }
            });
        }

        // Load products when a category is selected
        $('#category_id').on('change', function () {
            var category_id = $(this).val();

            if (category_id) {
                $.ajax({
                    url: 'fetch_products_by_category.php',
                    method: 'GET',
                    data: { category_id: category_id },
                    success: function (response) {
                        var products = JSON.parse(response);
                        $('#product_id').empty().append('<option value="">Select a Product</option>');
                        $.each(products, function (index, product) {
                            $('#product_id').append('<option value="' + product.id + '" data-buy_rate="' + product.buy_rate + '" data-sell_rate="' + product.sell_rate + '">' + product.name + '</option>');
                        });
                    }
                });
            } else {
                $('#product_id').empty().append('<option value="">Select a Product</option>');
            }
        });

        // Update Buy Rate, Sell Rate, and Total Cost when a product is selected
        $('#product_id').on('change', function () {
            var selectedOption = $(this).find(':selected');
            var buyRate = selectedOption.data('buy_rate');
            var sellRate = selectedOption.data('sell_rate');
            var quantity = $('#quantity').val() || 1;  // Default to 1 if no quantity is selected

            $('#buy_cost').val(buyRate);
            $('#sell_cost').val(sellRate);
            calculateTotalCost();
        });

        // Calculate total cost when quantity or product changes
        $('#quantity').on('input', function () {
            calculateTotalCost();
        });
        $('#additional_cost').on('input', function () {
            calculateTotalCost();
        });

        function calculateTotalCost() {
            var buyRate = $('#sell_cost').val();
            var quantity = $('#quantity').val();
            var additionalCost = $('#additional_cost').val() || 0; // Default to 0 if no additional cost is provided

            if (buyRate && quantity) {
                var totalCost = (parseFloat(buyRate) * parseInt(quantity)) + parseFloat(additionalCost);
                $('#total_cost').val(totalCost.toFixed(2));
            } else {
                $('#total_cost').val('');
            }
        }

        // Fetch and display orders
        function loadOrders(category = '', orderDetails = '', phoneNumber = '') {
            $.ajax({
                url: 'fetch_orders.php',
                method: 'GET',
                data: {
                    category: category,
                    order_details: orderDetails,
                    phone_number: phoneNumber
                },
                success: function (data) {
                    $('#order_list').html(data);  // Populate table with order data

                    // Attach event listeners for edit and delete buttons
                    $('.edit-btn').on('click', function () {
                        var order_id = $(this).data('id');
                        editOrder(order_id);
                    });

                    $('.delete-btn').on('click', function () {
                        var order_id = $(this).data('id');
                        deleteOrder(order_id);
                    });
                }
            });
        }

        // Function to handle saving the order (Add/Edit)
        $('#save_order').on('click', function () {
            var order_id = $('#order_id').val();
            var order_details = $('#order_details').val();
            var category_id = $('#category_id').val();
            var product_id = $('#product_id').val();
            var quantity = $('#quantity').val();
            var buy_cost = $('#buy_cost').val();
            var sell_cost = $('#sell_cost').val();
            var total_cost = $('#total_cost').val();
            var additional_cost_reason = $('#additional_cost_reason').val();
            var additional_cost = $('#additional_cost').val();
            // Get values from the input fields
            var customer_name = $('#customer_name').val();
            var customer_phone = $('#customer_phone').val();
            var customer_address = $('#customer_address').val();


            // Clear any previous error message
            $('#order_error_msg').text('');

            // Perform basic validation
            if (!order_details || !category_id || !product_id || !quantity || !buy_cost || !sell_cost) {
                $('#order_error_msg').text('All fields are required!');
                return;
            }

            // Submit the data via AJAX
            $.ajax({
                url: order_id ? 'edit_order.php' : 'add_order.php',  // Use edit_order.php for editing, add_order.php for adding
                method: 'POST',
                data: {
                    order_id: order_id,
                    order_details: order_details,
                    category_id: category_id,
                    product_id: product_id,
                    quantity: quantity,
                    buy_cost: buy_cost,
                    sell_cost: sell_cost,
                    total_cost: total_cost,
                    additional_cost_reason: additional_cost_reason,
                    additional_cost: additional_cost,
                    customer_address: customer_address,
                    customer_phone: customer_phone,
                    customer_name: customer_name

                },
                success: function (response) {
                    if (response === 'Order created successfully!' || response === 'Order updated successfully!') {
                        alert(response);
                        $('#addOrderModal').modal('hide');  // Close the modal

                        loadOrders();  // Reload order list
                    } else {
                        $('#order_error_msg').text(response);  // Display error message
                    }
                },
                error: function () {
                    $('#order_error_msg').text('An error occurred while saving the order.');
                }
            });
        });


        // Function to edit order
        function editOrder(order_id) {
            $.ajax({
                url: 'get_order.php',
                method: 'POST',
                data: { order_id: order_id },
                success: function (response) {
                    var order = JSON.parse(response);
                    console.log(order);

                    // Set order details
                    $('#order_id').val(order.id);
                    $('#order_details').val(order.order_details);
                    $('#quantity').val(order.quantity);
                    $('#buy_cost').val(order.buy_cost);
                    $('#sell_cost').val(order.sell_cost);
                    $('#total_cost').val(order.total);
                    $('#additional_cost').val(order.additional_cost);
                    $('#additional_cost_reason').val(order.additional_cost_reason);
                    $('#customer_name').val(order.customer_name);
                    $('#customer_phone').val(order.customer_phone);
                    $('#customer_address').val(order.customer_address);

                    // Set category first
                    $('#category_id').val(order.category_id);

                    // Load products for the selected category
                    $.ajax({
                        url: 'fetch_products_by_category.php',
                        method: 'GET',
                        data: { category_id: order.category_id },
                        success: function (response) {
                            var products = JSON.parse(response);
                            $('#product_id').empty().append('<option value="">Select a Product</option>');

                            $.each(products, function (index, product) {
                                $('#product_id').append('<option value="' + product.id + '" data-buy_rate="' + product.buy_rate + '" data-sell_rate="' + product.sell_rate + '">' + product.name + '</option>');
                            });
                            console.log(order);
                            // Set the selected product after loading the products
                            $('#product_id').val(order.product_id);
                        }
                    });

                    $('#addOrderModalLabel').text('Edit Order');
                    $('#addOrderModal').modal('show');
                }
            });
            loadOrders();  // Reload order list
        }
        // Function to delete order
        function deleteOrder(order_id) {
            if (confirm('Are you sure you want to delete this order?')) {
                $.ajax({
                    url: 'delete_order.php',
                    method: 'POST',
                    data: { order_id: order_id },
                    success: function (response) {
                        alert(response);
                        loadOrders();  // Reload order list
                    }
                });
            }
        }

        // Load orders on page load
        $(document).ready(function () {
            loadOrders();
            loadCategories();  // Load categories into the dropdown
            $('#manage_order').on('click', function () {
                $('#add_order_form')[0].reset();  // Clear form
                $('#order_id').val('');  // Clear order ID
                $('#addOrderModalLabel').text('Create Order');  // Set modal title
                $('#addOrderModal').modal('show');

            });
            // Category filter
            $('#category_filter').on('change', function () {
                var category = $(this).val();
                var orderDetails = $('#order_details_filter').val();
                var phoneNumber = $('#phone_search').val();
                loadOrders(category, orderDetails, phoneNumber);
            });

            // Order details filter
            $('#order_details_filter').on('change', function () {
                var category = $('#category_filter').val();
                var orderDetails = $(this).val();
                var phoneNumber = $('#phone_search').val();
                loadOrders(category, orderDetails, phoneNumber);
            });

            // Phone number search
            $('#phone_search').on('input', function () {
                var category = $('#category_filter').val();
                var orderDetails = $('#order_details_filter').val();
                var phoneNumber = $(this).val();
                loadOrders(category, orderDetails, phoneNumber);
            });
        });

    </script>

    <script src="css/dist/js/adminlte.js"></script>


</body>

</html>