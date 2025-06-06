<?php
// include '../configuration/config.php';
// include 'functionfile.php';

require_once '../../models/admin.php';
$admin = new Admin();

// calling class
$role = $admin->SelectRoleName();
    // echo "<pre>"; print_r($role); echo "</pre>";

//    $S = $admin->summa();

if ($_SERVER['REQUEST_METHOD'] === "POST" && isset($_POST['register_employee_details'])) {

    $empName = $empEmail = $empGender = $empDateOfJoin = $empRoleId = '';
    // Get form data directly from $_POST
    $empName = $_POST['employee_name'];
    $empEmail = $_POST['emp_email_id'];
    $empGender = $_POST['gender'];
    $empDateOfJoin = $_POST['date_of_joining'];
    $empRoleId = $_POST['role_id'];

   $insert = $admin->InsertEmployeeData($empName,$empEmail,$empGender,$empDateOfJoin,$empRoleId);
    // echo "<pre>"; print_r($insert); echo "</pre>";
    if(isset($insert['status']) && $insert['status'] === 'success') {
        $successMsg = "Data inserted successfully";
    } else {
        $errorMsg = $insert['msg'] ?? "Failed to insert data";
    }



}



?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../asset/css/style.css">

    <title>Register Employee Details</title>
</head>

<body>
    <div class="page-wrapper">
        <div class="insertEmployeeDetailContainer">
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
                        <label>employee Name:</label>
                        <input type="text" name="employee_name" required>
                    </div>
                    <div class="form-group">
                        <label>employee Email:</label>
                        <input type="email" name="emp_email_id" required>
                    </div>
                    <div class="form-group">
                        <div class="radio-group">
                            <label class="radio-option">Gender:
                                <input type="radio" name="gender" value="male" required>
                                <span class="radio-label">Male</span>
                            </label>
                            <label class="radio-option">
                                <input type="radio" name="gender" value="female">
                                <span class="radio-label">Female</span>
                            </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Date of Joining:</label>
                        <input type="date" name="date_of_joining" required>
                    </div>
                    <div class="form-group">
                        <label for="role_name">Role:</label>
                        <select name="role_id" id="role_name" required>
                            <option value="">Select Role Name</option>
                            <?php if (!empty($role)): ?>
                                <?php foreach ($role as $key => $value): ?>
                                    <option value="<?= $key ?>"><?= htmlspecialchars($value) ?></option>
                                <?php endforeach; ?>

                            <?php endif; ?>


                        </select>
                    </div>

                    <div class="form-group button-container">
                        <button type="submit" class="my-custom-button" name="register_employee_details"
                            onclick="return confirm('Are you sure you want to register this employee Detailss?');">Register</button>

                            <a href="../admin_page.php" class="my-custom-button" style="text-decoration:none;">Back</a>
                    </div>


                </form>
            </div>
        </div>
</body>

</html>