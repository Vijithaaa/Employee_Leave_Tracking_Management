<?php
session_start();

// Determine login type (admin or employee)
$login_type = isset($_GET['type']) ? $_GET['type'] : 'employee';
$page_title = ucfirst($login_type) . " Login";

// Common variables
$errorMsg = '';
$successMsg = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === "POST" && isset($_POST['login'])) {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if ($login_type === 'admin') {
        require_once '../../models/admin.php';
        $auth = new Admin();
        $userData = $auth->adminAuth($username, $password);

        if ($userData && $userData['msg']['name'] === $username && $userData['msg']['pass'] == $password) {
            $_SESSION['admin_logged_in'] = true;
            header("Location: ../admin_page.php");
            exit;
        } else {
            $errorMsg = "Invalid admin credentials";
        }
    } else {
        require_once '../../models/employee.php';
        $auth = new Employee();
        $userData = $auth->empAuthenticate($username, $password);
        // echo "<br>"; print_r($userData); echo "<br>";
        // echo "<br>"; print_r($userData['msg']['employee_image']); echo "<br>";

        if ($userData && $userData['msg']['employee_name'] === $username && $userData['msg']['employee_id'] == $password) {
            $_SESSION['empId'] = $userData['msg']['employee_id'];
            $_SESSION['empName'] = ucfirst($userData['msg']['employee_name']);
            $_SESSION['role_id'] = $userData['msg']['role_id'];
            $_SESSION['emp_logged_in'] = true;

            // Store the photo path if it exists
            if (!empty($userData['msg']['employee_image'])) {
                $_SESSION['empImage'] = $userData['msg']['employee_image'];
            }
            header("Location: ../employee/leave_tracking_employee_page.php");
            exit;
        } else {
            $errorMsg = "Invalid employee credentials";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <link rel="stylesheet" href="../../asset/css/style.css">
    <link rel="stylesheet" href="../../asset/css/login.css">

    <title><?= $page_title ?></title>
</head>

<body>
    <div class="page-wrapper">
        <div class="loginpage-container">
            <?php if (!empty($errorMsg)): ?>
                <div class="error"><?= $errorMsg ?></div>
            <?php endif; ?>

            <?php if (!empty($successMsg)): ?>
                <div class="success"><?= $successMsg ?></div>
            <?php endif; ?>

            <h2 class="login-title"><?= $page_title ?></h2>

            <div class="loginform">
                <form method="post">
                    <div class="form-group">
                        <label>Username:</label>
                        <input type="text" name="username" required>
                    </div>

                    <div class="form-group-pass">
                        <label><?= $login_type === 'admin' ? 'Password' : 'Employee ID' ?>:</label>
                        <input type="password" name="password" required>
                    </div>

                    <div class="form-group button-container">
                        <button type="submit" class="my-custom-button" name="login">Login</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>