<?php
session_start();
// include 'functionfile.php';
include '../navbar.php';
require_once '../../models/employee.php';
require_once '../../models/leaveTypes.php';
$emp = new Employee();
$leaveTypesobj = new LeaveType();


$errorMsg = '';
$successMsg = '';
$userData = [];

if (isset($_SESSION['emp_logged_in']) || $_SESSION['emp_logged_in']  == true) {

    $empName = ucfirst($_SESSION['empName']);
    $empId = $_SESSION['empId'];
    // print_r($empId);


    //check for role_id using employee_details
    $SelectRoleId = $emp->SelectRoleId($empId);
    $roleId = $SelectRoleId['role_id'];
    // print_r($roleId);


    //now using role_id fetch the role_name 
    $SelectRoleName = $emp->fetchRoleName($roleId);
    $roleName = $SelectRoleName['role_name'];


    // fetching the leavetypes
    $leaveType = $leaveTypesobj->getAllLeaveTypes();
    // echo "<pre>"; print_r($leaveType);echo "</pre>"; 


    $fetchLeaveTaken =  $leaveTypesobj->fetchLeaveTaken($empId);
    // echo "<pre>"; print_r($fetchLeaveTaken);echo "</pre>";


    $userData = [
        'empId' => $empId,
        'empName' => $empName,
        'roleId' => $roleId,
        'roleName' => $roleName,
        'total_leave' => $fetchLeaveTaken['totalCount'],
        'leaveType' => $leaveType['leaveIdName'],
        'fetchLeaveTaken' => $fetchLeaveTaken['leaveDetails']

    ];

    //mapping  'leaveType'  and 'fetchLeaveTaken'
    foreach ($userData['fetchLeaveTaken'] as $key => $value) {
        $userData['fetchLeaveTaken'][$key]['leave_type'] = $userData['leaveType'][$value['leave_type_id']] ?? null;
    }

    // foreach ($userData['fetchLeaveTaken'] as $key => $value) {
    //     //       0th 1st array nu solder   na kudukura name->already iruka array la oru key add pandra
    //     $userData['fetchLeaveTaken'][$key]['leave_type'] = $userData['leaveType'][$value['leave_type_id']] ?? null;
    //     //                             leaveType la iurka key(apo adhoda value)  = typename                                        
    // }

    // echo "<pre>"; print_r($userData);echo "</pre>";

}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="../../asset/css/style.css">

    <title>leave tracking</title>
</head>

<body>
    <div class="container">
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
        <h1>leave tracking page</h1>
        <h3><span> Name : </span><?= $userData['empName'] ?></h3>
        <h3><span> Role : </span><?= $userData['roleName'] ?></h3>


        <div class="leave-panels">
            <?php foreach ($userData['fetchLeaveTaken'] as $leave): ?>
                <div class="leave-panel">
                    <div class="leave-name"><?php echo $leave['leave_type']; ?></div>
                    <div class="leave-taken">Taken: <?php echo $leave['leave_taken'] ?> days</div>
                </div>
            <?php endforeach; ?>
        </div>
        <h3> <span>Total Leave : </span><?php echo $userData['total_leave'] ?></h3>




    </div>

</body>

</html>