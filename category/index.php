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
                    <h3 class="card-title">Budget Management</h3>
                    <div class="card-tools">
                    <a href="javascript:void(0)" id="manage_budget" class="btn btn-flat btn-sm btn-primary" data-toggle="modal" data-target="#addNewModal">
                            <span class="fas fa-plus"></span> Add New
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-stripped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Category</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="category_list">
                            <!-- Categories will be dynamically loaded here -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>
<!-- Bootstrap Modal for Adding New Category -->
<div class="modal fade" id="addNewModal" tabindex="-1" role="dialog" aria-labelledby="addNewModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addNewModalLabel">Add New Category</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="add_new_form">
                    <div class="form-group">
                        <label for="new_category_name" class="col-form-label">Category Name:</label>
                        <input type="text" class="form-control" id="new_category_name" name="category_name" required>
                        <span id="add_error_msg" class="text-danger"></span>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="save_new_category">Save Category</button>
            </div>
        </div>
    </div>
</div>

<script>
    // Add new category
    $('#save_new_category').on('click', function () {
        var category_name = $('#new_category_name').val();

        // Clear any previous error message
        $('#add_error_msg').text('');

        $.ajax({
            url: 'add_category.php',
            method: 'POST',
            data: { category_name: category_name },
            success: function (response) {
                if (response === 'Category already exists') {
                    $('#add_error_msg').text(response);
                } else {
                    alert(response);
                    $('#addNewModal').modal('hide');
                    loadCategories();  // Refresh category list
                }
            }
        });
    });
</script>
<!-- Bootstrap Modal for Editing -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit Category</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="edit_form">
                    <input type="hidden" id="edit_id" name="id">
                    <div class="form-group">
                        <label for="category_name" class="col-form-label">Category Name:</label>
                        <input type="text" class="form-control" id="category_name" name="category_name" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="save_changes">Save Changes</button>
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

    <script>
// Fetch and display categories from the database
function loadCategories() {
    $.ajax({
        url: 'fetch_categories.php',
        method: 'GET',
        success: function (data) {
            $('#category_list').html(data);
        }
    });
}

// Delete category
function deleteCategory(id) {
    if (confirm('Are you sure you want to delete this category?')) {
        $.ajax({
            url: 'delete_category.php',
            method: 'POST',
            data: { id: id },
            success: function (response) {
                alert(response);
                loadCategories();  // Refresh category list
            }
        });
    }
}

// Show edit modal with category data
function editCategory(id, name) {
    $('#edit_id').val(id);
    $('#category_name').val(name);
    $('#editModal').modal('show');
}

// Save edited category
$('#save_changes').on('click', function () {
    var id = $('#edit_id').val();
    var category_name = $('#category_name').val();

    $.ajax({
        url: 'edit_category.php',
        method: 'POST',
        data: { id: id, category_name: category_name },
        success: function (response) {
            alert(response);
            $('#editModal').modal('hide');
            loadCategories();  // Refresh category list
        }
    });
});

// Load categories when the page is ready
$(document).ready(function () {
    loadCategories();
});
</script>

<script src="css/dist/js/adminlte.js"></script>


</body>

</html>