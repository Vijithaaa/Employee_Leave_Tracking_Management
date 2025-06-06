<?php
session_start();
// include 'functionfile.php';
require_once '../../models/admin.php';
$admin = new Admin();

$adminData = '';

if ($_SERVER['REQUEST_METHOD'] === "POST" && isset($_POST['admin_login'])) {
    $adminName = $_POST['adminName'];
    $adminPass = $_POST['adminPass'];

    $adminData = $admin->adminAuth($adminName, $adminPass);
    // echo "<pre>";print_r($adminData);echo "</pre>";

    if ($adminData) {
        if ($adminData['msg']['name'] === $adminName && $adminData['msg']['pass'] == $adminPass) {
            $successMsg = "Login successful!";
            header("Location: ../admin_page.php");
            exit;
        }
        else {
            $errorMsg = "Credentials do not match our records";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../asset/css/style.css">

    <title>admin login form</title>
</head>

<body>
    <div class="page-wrapper">
        <div class="loginpage-container">
            <?php if (!empty($errorMsg)): ?>
                <div class="error">
                    <?= $errorMsg ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($successMsg)): ?>
                <div class="success">
                    <?= $successMsg ?>
                </div>
            <?php endif; ?>
            <div class="loginform">
                <form method="post">
                    <div class="form-group">
                        <label>Username:</label>
                        <input type="text" name="adminName" required>
                    </div>

                    <div class="form-group">
                        <label>Password:</label>
                        <input type="text" name="adminPass" required>
                    </div>
                    <div class="form-group button-container">
                        <button type="submit" class="my-custom-button" name="admin_login">Login</button>
                    </div>
                </form>
            </div>

        </div>

</body>

</html>