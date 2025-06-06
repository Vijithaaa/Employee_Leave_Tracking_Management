<?php
session_start();
include '../functionfile.php';
include '../navbar.php';

require_once '../../models/leaveApplication.php';
require_once '../../models/leaveTypes.php';

$leaveTypesobj = new LeaveType();
$LeaveAppObj = new LeaveApplication();

if (isset($_SESSION['emp_logged_in']) || $_SESSION['emp_logged_in']  == true) {

    $empName = $_SESSION['empName'];
    $empId = $_SESSION['empId'];
    // print_r($empId);
    // print_r($empName);

    $application = [];
    // $data = ['empId' => $empId];
    $SelectApplication = $LeaveAppObj->SelectApplication($empId);

    if (isset($SelectApplication)) {
        // echo "<pre>";print_r($SelectApplication);echo "</pre>";

        $leaveType = $leaveTypesobj->getAllLeaveTypes();

        $leaveIdName = $leaveType['leaveIdName'];
        // echo "<pre>";print_r($leaveIdName);echo "</pre>";

        $leaveIdName = [];
        foreach ($leaveType['leaveIdName'] as $id => $name) {
            $leaveIdName[$id] = $name;
        }

        foreach ($SelectApplication as $app) {
            $leaveTypeId = $app['leave_type_id'];
            $leaveTypeName = $leaveIdName[$leaveTypeId] ?? 'Unknown Leave Type';

        $application[] = [
        'application_id' => $app['application_id'],
        'employee_id' => $app['employee_id'],
        'leave_type_id' => $leaveTypeName,
        'leave_start_date' => $app['leave_start_date'],
        'leave_end_date' => $app['leave_end_date'],
        'status' => $app['status'],
        'reqested_date' => $app['reqested_date'],
        'response_date' => $app['response_date'],
        'days' => calculateLeaveDays($app['leave_start_date'], $app['leave_end_date']) // Calculate days here
    
    ];
    // echo "<pre>";print_r($app);echo "</pre>";
        }

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

    <title>Leave History</title>
</head>

<body>
    <div class="container">

        <div class="container py-4">
            <h1 class="mb-4">Leave History</h1>
            <h2 class="mb-4">Welcome <?= $empName ?></h2>

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
                            <th>Leave Type</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Days</th>
                            <th>Status</th>
                            <th>Requested Date</th>
                            <th>Response Date</th>
                            <th>Action</th>

                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($application as $app): ?>
                            <tr>
                                <td><?= htmlspecialchars($app['leave_type_id']) ?></td>
                                <td><?= htmlspecialchars(formatDate($app['leave_start_date'])) ?></td>
                                <td><?= htmlspecialchars(formatDate($app['leave_end_date'])) ?></td>
                                <td class="days"><?= $app['days'] ?> day<?= $app['days'] != 1 ? 's' : '' ?></td>
                                <td class="status-<?= $app['status'] ?>">
                                    <?= ucfirst(htmlspecialchars($app['status'])) ?>
                                </td>
                                <td><?= htmlspecialchars($app['reqested_date']) ?></td>
                                <td> <?= !empty($app['response_date']) ? htmlspecialchars($app['response_date']) : 'N/A' ?> </td>
                                <td class="text-center">
                                    <?php if ($app['status'] == 'pending'): ?>

                                        <form action="leaveApplicationForm.php" method="post" style="display:inline;">
                                            <input type="hidden" name="application_id" value="<?= $app['application_id'] ?>">
                                            <button type="submit" class="btn btn-sm btn-outline-primary me-2">
                                                <i class="bi bi-pencil-square"></i> Edit
                                            </button>
                                        </form>
                                        <form action="deleteApp.php" method="post" style="display:inline;">
                                            <input type="hidden" name="application_id" value="<?= $app['application_id'] ?>">
                                            <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure you want to delete this application?');">
                                                <i class="bi bi-trash3"></i> Delete
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