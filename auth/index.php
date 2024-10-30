<?php
session_start();

// Database credentials
include '../database/database.php';
// Initialize an error message variable
$error_message = '';
// Check if the user is already logged in
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    header('Location: ../admin/'); // Redirect to the admin dashboard or another page
    exit(); // Ensure no further code is executed
}
// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get and trim the username and password from the form
    $username = isset($_POST['username']) ? trim($_POST['username']) : '';
    $password = isset($_POST['password']) ? trim($_POST['password']) : '';

    // Validate the input
    if (!empty($username) && !empty($password)) {
        // Query the database to fetch the user with role 'admin'
        $query = "SELECT email, password FROM users WHERE email = '$username' AND role = 'admin'";
        $result = mysqli_query($conn, $query);

        // Check if a matching user was found
        if ($result && mysqli_num_rows($result) > 0) {
            $user = mysqli_fetch_assoc($result);

            // Compare the password (plain text comparison)
            if ($password === $user['password']) {
                // Login successful
                $_SESSION['loggedin'] = true;
                $_SESSION['username'] = $username;
                header('Location: ../admin/'); // Redirect to the dashboard or another page
                exit();
            } else {
                $error_message = 'Incorrect password. Please try again.';
            }
        } else {
            $error_message = 'No user found with that username.';
        }
    } else {
        $error_message = 'Please enter both username and password.';
    }
}

// Close the database connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en" style="height: auto;">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Finance Tracker system</title>
    <link rel="icon" href="http://localhost/expense_budget/uploads/1627606920_modeylogo.jpg" />
    <link rel="stylesheet" href="css/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="css/dist/css/adminlte.css">
</head>
<body class="hold-transition login-page bg-navy">
    
    <h2 class="text-center mb-4 pb-3">Finance Tracker system</h2>
    <div class="login-box">
        <div class="card card-outline card-primary">
            <div class="card-body">
                <p class="login-box-msg text-dark">Sign in to start your session</p>

                <!-- Display error message if login fails -->
                <?php if (!empty($error_message)): ?>
                    <div class="alert alert-danger">
                        <?php echo $error_message; ?>
                    </div>
                <?php endif; ?>

                <form id="login-frm" action="" method="post">
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" name="username" placeholder="Username" autofocus>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-user"></span>
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <input type="password" class="form-control" name="password" placeholder="Password">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row justify-content-center">
                        <button type="submit" class="btn btn-primary btn-block">Sign In</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</body>
</html>
