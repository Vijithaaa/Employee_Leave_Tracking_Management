<?php
session_start();
require_once '../functionfile.php';
require_once '../../models/employee.php';
require_once '../../models/leaveTypes.php';


$emp = new Employee();
$leaveTypesobj = new LeaveType();

$errorMsg = '';
$successMsg = '';
$userData = [];

if (isset($_SESSION['emp_logged_in']) && $_SESSION['emp_logged_in'] == true) {
    // Employee data
    $empName = ucfirst($_SESSION['empName']);
    $empId = $_SESSION['empId'];
    $empImage = $_SESSION['empImage'];

    // Get role information
    $SelectRoleId = $emp->SelectRoleId($empId);
    $roleId = $SelectRoleId['role_id'];
    $SelectRoleName = $emp->fetchRoleName($roleId);
    $roleName = ucfirst($SelectRoleName['role_name']);

    // Get leave information
    $leaveType = $leaveTypesobj->getAllLeaveTypes();
    $fetchLeaveTaken = $leaveTypesobj->fetchLeaveTaken($empId);

    $userData = [
        'empId' => $empId,
        'empName' => $empName,
        'roleId' => $roleId,
        'roleName' => $roleName,
        'total_leave' => $fetchLeaveTaken['totalCount'],
        'leaveType' => $leaveType['leaveIdName'],
        'fetchLeaveTaken' => $fetchLeaveTaken['leaveDetails']
    ];

    // Map leave types
    foreach ($userData['fetchLeaveTaken'] as $key => $value) {
        $userData['fetchLeaveTaken'][$key]['leave_type'] = $userData['leaveType'][$value['leave_type_id']] ?? null;
    }

    // Add extra content for navbar
$navbarExtraContent = "<span class='me-3 text-primary'>" . htmlspecialchars($userData['roleName']) . "</span>";
}
include '../navbar.php';

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leave Tracking</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="../../asset/css/leavetrack.css">
    <link rel="stylesheet" href="../../asset/css/employee-navbar.css">

       <style>
        .blank {
            background-color: transparent;
            height: 15vh;
        }



    </style>
</head>

<body>
    <div class="blank"></div>
    <div class="container">
        <?php if (!empty($errorMsg)): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($errorMsg) ?></div>
        <?php endif; ?>

        <?php if (!empty($successMsg)): ?>
            <div class="alert alert-success"><?= htmlspecialchars($successMsg) ?></div>
        <?php endif; ?>
        <h3><span>Total Leave:</span> <?= $userData['total_leave'] ?></h3>

        <div class="leave-panels">
            <?php foreach ($userData['fetchLeaveTaken'] as $leave): ?>
                <div class="leave-panel">
                    <div class="leave-name"><?= htmlspecialchars(ucwords(str_replace('_',' ',$leave['leave_type']))) ?></div>
                    <div class="leave-taken">Taken: <?= $leave['leave_taken'] ?> days</div>
                </div>
            <?php endforeach; ?>
        </div>

    </div>

</body>

</html>