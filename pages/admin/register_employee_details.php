<?php

require_once '../../models/admin.php';
$admin = new Admin();

$uploadFolder = "../../asset/images/employees/";
$allowedFileTypes = ['image/jpeg', 'image/png', 'image/gif'];
$maxFileSize = 2 * 1024 * 1024; // 2MB



// calling class
$role = $admin->SelectRoleName();
// echo "<pre>"; print_r($role); echo "</pre>";


if ($_SERVER['REQUEST_METHOD'] === "POST" && isset($_POST['register_employee_details'])) {

    $empName = $empEmail = $empGender = $empDateOfJoin = $empRoleId = $photoPath = '';
    // Get form data directly from $_POST
    $empName = $_POST['employee_name'];
    $empEmail = $_POST['emp_email_id'];
    $empGender = $_POST['gender'];
    $empDateOfJoin = $_POST['date_of_joining'];
    $empRoleId = $_POST['role_id'];

    // Handle photo upload
    if (!empty($_FILES['employee_photo']['tmp_name'])) {

        $file = $_FILES['employee_photo'];
        // Check file type
        if (!in_array($file['type'], $allowedFileTypes)) {
            $error = "Only JPG, PNG, or GIF images allowed.";
        }
        // Check file size
        elseif ($file['size'] > $maxFileSize) {
            $error = "Image too large (max 2MB).";
        } else {
            // Save the file
            // $newFilename = 'emp_' . uniqid() . '.' . pathinfo($file['name'], PATHINFO_EXTENSION);

            $newFilename = $file['name'];
            if (move_uploaded_file($file['tmp_name'], $uploadFolder . $newFilename)) {
                $photoPath = 'asset/images/employees/' . $newFilename;
            } else {
                $error = "Upload failed.";
            }
        }
    } else {
        $error = "Please choose a photo.";
    }







    $insert = $admin->InsertEmployeeData($empName, $empEmail, $empGender, $empDateOfJoin, $empRoleId, $photoPath);
    // echo "<pre>"; print_r($insert); echo "</pre>";

    if (isset($insert['status']) && $insert['status'] === 'success') {
        $successMsg = "Data inserted successfully";


        // Write to CSV file
        $csvFilePath = "employee-data.csv";

        // Create the file with header if it doesn't exist
        if (!file_exists($csvFilePath)) {
            $file = fopen($csvFilePath, 'w');
            fputcsv($file, ['employee_id','employee_name','emp_email_id','gender','date_of_joining','status','role_id','employee_image']);
        } else {
            $file = fopen($csvFilePath, 'a'); // append mode
        }


        if ($file) {
            // 🆔 Use DB ID or generate temp ID (if you don’t have it yet)
            $empId = $insert['msg']['employee_id']; // assumes you return ID from DB
            $status='active'; //default

            fputcsv($file, [$empId,$empName,$empEmail,$empGender,$empDateOfJoin,$status,$empRoleId,$photoPath]);

            fclose($file);
        }

    }  //insert status = success

    else {
        $errorMsg = $insert['msg'] ?? "Failed to insert data";
        if ($photoPath && file_exists($uploadFolder . basename($photoPath))) {
            unlink($uploadFolder . basename($photoPath));
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
                <form method="post" enctype="multipart/form-data">
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
                    <div class="form-group">
                        <label for="emp_image">Employee Image : </label>
                        <input type="file" id="emp_image" name="employee_photo" accept="image/png, image/jpeg ,image/gif" required>


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