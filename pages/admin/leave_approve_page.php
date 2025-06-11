<?php
session_start();
include '../functionfile.php';


require_once '../../models/leaveApplication.php';
require_once '../../models/leaveTypes.php';
require_once '../../models/employee.php';

$employee = new Employee();
$types = new LeaveType();
$LeaveAppObj = new LeaveApplication();





// //get the application_id from  form in same page
if (isset($_POST['actions']) && isset($_POST['application_id'])) {
    $application_id = ($_POST['application_id']);  //hidden input
    $status = ($_POST['actions']); //hidden input

    $updateLeaveApp = $LeaveAppObj->updateLeaveApp($status, $application_id);


      if ($updateLeaveApp) {
        $successMsg = "status updated";
    }



    //     //select leave application if status == 'approved'  for updating the leave_tracking page to show no of leave count for  particular employeee
    if ($status == 'approved') {

        $Selecting_appIds = $LeaveAppObj->Selecting_appIds($application_id);
        $emp_id = $Selecting_appIds['employee_id'];
        $leave_id = $Selecting_appIds['leave_type_id'];
        $start_date = date_create($Selecting_appIds['leave_start_date']);
        $end_date = date_create($Selecting_appIds['leave_end_date']);
        $interval = $start_date->diff($end_date);
        $total_days = $interval->days + 1;

        $Insertdata_to_LeaveTrack = $LeaveAppObj->Insertdata_to_LeaveTrack($total_days, $leave_id, $emp_id);

    } //status == approved


} // if post data








$application = [];
// $SelectAllApplication = leaveapp_crul_opration([], "SelectAllApplication");

$SelectAllApplication = $LeaveAppObj->SelectAllApplication();

if ($SelectAllApplication && $SelectAllApplication['status'] === 'success') {
    $applications = $SelectAllApplication['msg'];

    $leaveType = $types->getAllLeaveTypes();
    $leaveIdName = $leaveType['leaveIdName'];

    //         //employee name
    $selectEmployeeName = $employee->selectEmployeeName();


    $leaveIdName = [];
    $EmpIdName = [];
    foreach ($leaveType['leaveIdName'] as $id => $name) {
        $leaveIdName[$id] = $name;
    }

    foreach ($selectEmployeeName as $id => $name) {
        $EmpIdName[$id] = $name;
    }

    foreach ($applications as $app) {
        $leaveTypeId = $app['leave_type_id'];
        $leaveTypeName = $leaveIdName[$leaveTypeId] ?? 'Unknown Leave Type';
        $EmpId = $app['employee_id'];
        $EmpName = $EmpIdName[$EmpId] ?? 'Unknown Employee Name';

        $application[] = [
            'application_id' => $app['application_id'],
            'employee_id' => $EmpName,
            'leave_type_id' => $leaveTypeName,
            'leave_start_date' => $app['leave_start_date'],
            'leave_end_date' => $app['leave_end_date'],
            'status' => $app['status'],
            'reqested_date' => $app['reqested_date'],
            'response_date' => $app['response_date'],
            'days' => calculateLeaveDays($app['leave_start_date'], $app['leave_end_date']) // Calculate days here

        ];
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <link rel="stylesheet" href="../../asset/css/style.css">
    <link rel="stylesheet" href="../../asset/css/approver.css">


    <title>Leave History</title>
    <style>
        .blank {
            background-color: transparent;
            height: 20px;
        }
    </style>
</head>

<body>
    <div class="blank"></div>
    <nav class="navbar custom-navbar">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">
                <img src="../../asset/images/infiniti_logo.png" alt="Logo" style="width:80px;" class="rounded-pill me-3">
            </a>
            <span class="h3">Leave Approval</span>
            <div class="d-flex">
                <ul class="navbar-nav flex-row">
                    <li class="nav-item">
                        <a class="nav-link text-danger" href="../auth/logout.php"><i class="bi bi-box-arrow-right"></i> Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="blank"></div>


    <div class="container-approve">
        <div class="container py-4">


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

            <?php if (!empty($application)): ?>
                <table class="table table">
                    <thead class="table-info">
                        <tr>
                            <!-- <th>Application ID</th> -->
                            <th>Employee Name</th>
                            <th>Leave Type ID</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Days</th>
                            <th>Requested Date</th>
                            <!-- <th>Response Date</th> -->
                            <th>Status</th>
                            <th>Action</th>

                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($application as $app): ?>
                            <tr>

                                <td><?= htmlspecialchars(ucfirst($app['employee_id'])) ?></td>

                                <td><?= htmlspecialchars($app['leave_type_id']) ?></td>
                                <td><?= htmlspecialchars(formatDate($app['leave_start_date'])) ?></td>
                                <td><?= htmlspecialchars(formatDate($app['leave_end_date'])) ?></td>
                                <td class="days"><?= $app['days'] ?> day<?= $app['days'] != 1 ? 's' : '' ?></td>


                                <td><?= htmlspecialchars($app['reqested_date']) ?></td>

                                <td class="status-<?= $app['status'] ?>">
                                    <?= ucfirst(htmlspecialchars($app['status'])) ?>
                                </td>

                                <td class="text-center">
                                    <?php if ($app['status'] == 'pending'): ?>

                                        <form class="actions" method="post" style="display:inline;">
                                            <input type="hidden" name="application_id" value="<?= $app['application_id'] ?>">
                                            <input type="hidden" name="actions" value="approved">
                                            <button type="submit" class="text-action approve">
                                                <i class="bi bi-check2-square"></i> Approve
                                            </button>
                                        </form>
                                        <form class="actions" method="post" style="display:inline;">
                                            <input type="hidden" name="application_id" value="<?= $app['application_id'] ?>">
                                            <input type="hidden" name="actions" value="rejected">
                                            <button type="submit" class="text-action reject">
                                                <i class="bi bi-trash3"></i> Reject
                                            </button>
                                        </form>
                                    <?php else: ?>
                                        <span class="text-muted">No actions</span>
                                    <?php endif; ?>
                                </td>



                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p class="text-muted">No leave applications found.</p>
            <?php endif; ?>


        </div>
</body>

</html>