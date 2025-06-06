<?php
session_start();
// include '../configuration/config.php';
// include 'functionfile.php';

require_once '../../models/employee.php';
$emp = new Employee();


$errorMsg = '';
$successMsg = '';
$employeeData = '';

if ($_SERVER['REQUEST_METHOD'] === "POST" && isset($_POST['login_emp'])) {
    $empName = trim($_POST['empName']);
    $empId = trim($_POST['empId']);
    
    $employeeData = $emp->empAuthenticate($empName, $empId);
    // echo "<pre>"; print_r($employeeData); echo "</pre>";
    if ($employeeData) {
        // Verify exact match (case-sensitive)
        if ($employeeData['msg']['employee_name'] === $empName && 
            $employeeData['msg']['employee_id'] == $empId) {
            
            $_SESSION['empId'] = $employeeData['msg']['employee_id'];
            $_SESSION['empName'] = $employeeData['msg']['employee_name'];
            $_SESSION['role_id'] = $employeeData['msg']['role_id'];
            $_SESSION['emp_logged_in'] = true;
            
            $successMsg = "Login successful!";
            header("Location: ../employee/leave_tracking_employee_page.php");
            exit;
        } else {
            $errorMsg = "Credentials do not match our records";
        }
    } 
    // else {
    //     $errorMsg = "Invalid employee ID or name";
    // }
}
?>
<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!-- <link rel="stylesheet" href="/emp_leave_app_tracking_ver1_copy/asset/css/style.css"> -->
        <link rel="stylesheet" href="../../asset/css/style.css">


        <title>login</title>
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
                        <label>Employee Name:</label>
                        <input type="text" name="empName" required>
                    </div>

                    <div class="form-group">
                        <label>Employee Id:</label>
                        <input type="text" name="empId" required>
                    </div>
                    <div class="form-group button-container">
                        <button type="submit" class="my-custom-button" name="login_emp">Login</button>
                    </div>
                </form>
            </div>

        </div>


</body>

</html>

