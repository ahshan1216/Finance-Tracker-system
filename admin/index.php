<?php
include '../database/database.php';
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
  header('Location: ../auth/'); // Redirect to the admin dashboard or another page
  exit(); // Ensure no further code is executed
}
// Fetch categories and product counts from the database
$query = "
SELECT c.id, c.c_name, COUNT(p.id) AS product_count
FROM category c
LEFT JOIN products p ON c.id = p.category_id
GROUP BY c.id, c.c_name
";
$result = $conn->query($query);

$totalSpendQuery = "SELECT SUM(total) as total_spend FROM products";
$result1 = $conn->query($totalSpendQuery);

if ($result1) {
    $row1 = $result1->fetch_assoc();
    $totalSpend = $row1['total_spend'];
} else {
    $totalSpend = 0; // Set to 0 if there's an error
}


$totalsellQuery = "SELECT SUM(total) as total_rev FROM order_items";
$result12 = $conn->query($totalsellQuery);

if ($result12) {
    $row12 = $result12->fetch_assoc();
    $totalsell = $row12['total_rev'];
} else {
  $totalsell = 0; // Set to 0 if there's an error
}

$revinue =  $totalsell- $totalSpend;
if($revinue)
{
  $revinue1= $revinue ;
}
else{
  $revinue1= 0;
}

?>

<!DOCTYPE html>
<html lang="en" class="" style="height: auto;">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Finance Tracker system</title>
  <link rel="icon" href="http://localhost/expense_budget/uploads/1627606920_modeylogo.jpg" />

  <link rel="stylesheet" href="css/fontawesome-free/css/all.min.css">






  <!-- Theme style -->
  <link rel="stylesheet" href="css/dist/css/adminlte.css">

  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="css/dist/css/OverlayScrollbars.min.css">


  <!-- jQuery -->
  <script src="css/jquery/jquery.min.js"></script>
  <!-- jQuery UI 1.11.4 -->
  <script src="css/jquery-ui/jquery-ui.min.js"></script>


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
          <a href="https://esquireelectronicsltd.online/admin/" class="nav-link">Finance Tracker system - Admin</a>
        </li>
      </ul>
      <!-- Right navbar links -->
      <ul class="navbar-nav ml-auto">

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
              <a class="dropdown-item" href="http://localhost/expense_budget//classes/Login.php?f=logout"><span
                  class="fas fa-sign-out-alt"></span> Logout</a>
            </div>
          </div>
        </li>
        <li class="nav-item">

        </li>
        <!--  <li class="nav-item">
            <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#" role="button">
            <i class="fas fa-th-large"></i>
            </a>
          </li> -->
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
    <div class="content-wrapper pt-3" style="min-height: 567.854px;">

      <!-- Main content -->
      <section class="content  text-dark">
        <div class="container-fluid">
          <style>
            .info-tooltip,
            .info-tooltip:focus,
            .info-tooltip:hover {
              background: unset;
              border: unset;
              padding: unset;
            }
          </style>
          <h1>Welcome to Finance Tracker system</h1>
          <hr>
          <div class="row">
            <div class="col-12 col-sm-6 col-md-3">
              <div class="info-box">
                <span class="info-box-icon bg-primary elevation-1"><i class="fas fa-money-bill-alt"></i></span>

                <div class="info-box-content">
    <span class="info-box-text">Total Spend</span>
    <span class="info-box-number text-right">
        <?php echo number_format($totalSpend); // Format the number with commas ?></span>
</div>

                <!-- /.info-box-content -->
              </div>

              <!-- /.info-box -->
            </div>
            <!-- /.col -->
            <div class="col-12 col-sm-6 col-md-3">
              <div class="info-box mb-3">
                <span class="info-box-icon bg-info elevation-1"><i class="fas fa-calendar-day"></i></span>

                <div class="info-box-content">
                  <span class="info-box-text">Total Sell</span>
                  <span class="info-box-number text-right">
                  <?php echo number_format($totalsell); // Format the number with commas ?></span>
                </div>

              </div>

            </div>
            <!-- /.col -->

            <!-- fix for small devices only -->
            <div class="clearfix hidden-md-up"></div>

            <div class="col-12 col-sm-6 col-md-3">
              <div class="info-box mb-3">
                <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-calendar-day"></i></span>

                <div class="info-box-content">
                  <span class="info-box-text">Total Revinue</span>
                  <span class="info-box-number text-right">
                  <?php echo number_format($revinue1); // Format the number with commas ?></span>
                </div>
                <!-- /.info-box-content -->
              </div>
              <!-- /.info-box -->
            </div>
          </div>
          <div class="row">
            <div class="col-lg-12">
              <h4>Total Sales Categories</h4>
              <hr>
            </div>
          </div>



<div class="row row-cols-4 row-cols-sm-1 row-cols-md-4 row-cols-lg-4">
    <?php while ($row = $result->fetch_assoc()): ?>
        <div class="col p-2 cat-items">
            <div class="callout callout-info">
                <span class="float-right ml-1">
                    <button type="button" class="btn btn-secondary info-tooltip" data-toggle="tooltip" data-html="true"
                        title='<p><span style="color: rgb(0, 0, 0); font-family: &quot;Open Sans&quot;, Arial, sans-serif; font-size: 14px; text-align: justify;">Details about the category go here.</span><br></p>'>
                        <span class="fa fa-info-circle text-info"></span>
                    </button>
                </span>
                <h5 class="mr-4"><b><?php echo $row['c_name']; ?></b></h5>
                <div class="d-flex justify-content-end">
                    <b>Total Products: <?php echo $row['product_count']; ?></b>
                </div>
            </div>
        </div>
    <?php endwhile; ?>
</div>

          <div class="col-md-12">
            <h3 class="text-center" id="noData" style="display:none">No Data to display.</h3>
          </div>
          <script>
            function check_cats() {
              if ($('.cat-items:visible').length > 0) {
                $('#noData').hide('slow')
              } else {
                $('#noData').show('slow')
              }
            }
            $(function () {
              $('[data-toggle="tooltip"]').tooltip({
                html: true
              })
              check_cats()
              $('#search').on('input', function () {
                var _f = $(this).val().toLowerCase()
                $('.cat-items').each(function () {
                  var _c = $(this).text().toLowerCase()
                  if (_c.includes(_f) == true)
                    $(this).toggle(true);
                  else
                    $(this).toggle(false);
                })
                check_cats()
              })
            })
          </script>
        </div>
      </section>
      <!-- /.content -->
      <div class="modal fade" id="confirm_modal" role='dialog'>
        <div class="modal-dialog modal-md modal-dialog-centered" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Confirmation</h5>
            </div>
            <div class="modal-body">
              <div id="delete_content"></div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-primary" id='confirm' onclick="">Continue</button>
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
          </div>
        </div>
      </div>
      <div class="modal fade" id="uni_modal" role='dialog'>
        <div class="modal-dialog modal-md modal-dialog-centered" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title"></h5>
            </div>
            <div class="modal-body">
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-primary" id='submit'
                onclick="$('#uni_modal form').submit()">Save</button>
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
            </div>
          </div>
        </div>
      </div>
      <div class="modal fade" id="uni_modal_right" role='dialog'>
        <div class="modal-dialog modal-full-height  modal-md" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title"></h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span class="fa fa-arrow-right"></span>
              </button>
            </div>
            <div class="modal-body">
            </div>
          </div>
        </div>
      </div>
      <div class="modal fade" id="viewer_modal" role='dialog'>
        <div class="modal-dialog modal-md" role="document">
          <div class="modal-content">
            <button type="button" class="btn-close" data-dismiss="modal"><span class="fa fa-times"></span></button>
            <img src="" alt="">
          </div>
        </div>
      </div>
    </div>
    <!-- /.content-wrapper -->

    <!-- <footer class="main-footer text-sm">
        <strong>Copyright © 2024. 
         <a href=""></a> -->
    <!-- </strong>
        All rights reserved.
        <div class="float-right d-none d-sm-inline-block">
          <b> </b> v1.0
        </div>
      </footer>  -->
  </div>
  <!-- ./wrapper -->





  <script src="css/dist/js/adminlte.js"></script>

</body>

</html>