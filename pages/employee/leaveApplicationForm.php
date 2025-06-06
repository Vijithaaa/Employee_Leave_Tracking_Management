<?php
session_start();
include '../navbar.php';
require_once '../../models/leaveApplication.php';
require_once '../../models/leaveTypes.php';

$leaveTypesobj = new LeaveType();
$LeaveAppObj = new LeaveApplication();

if (!isset($_SESSION['emp_logged_in']) || $_SESSION['emp_logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

$empName = $_SESSION['empName'];
$empId = $_SESSION['empId'];

$leaveType = $leaveTypesobj->getAllLeaveTypes();

$application_id = $_POST['application_id'] ?? $_GET['application_id'] ?? null;
$leave_type_id = '';
$start_date = '';
$end_date = '';
$errorMsg = '';
$successMsg = '';

// If it's edit mode: load existing data
if ($application_id !==null) {
    $SelectLeaveFormData = $LeaveAppObj->SelectLeaveFormData($empId, $application_id);
    if ($SelectLeaveFormData) {
        $leave_type_id = $SelectLeaveFormData['leave_type_id'];
        $start_date = $SelectLeaveFormData['leave_start_date'];
        $end_date = $SelectLeaveFormData['leave_end_date'];
    }
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === 'POST' && (isset($_POST['submit_form']) || isset($_POST['edit_form']))) {
    $leave_type_id = $_POST['leave_type_id'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $application_id = $_POST['application_id'] ?? null;

    $today = date('Y-m-d');
    if ($start_date < $today) {
        $errorMsg = "Start date cannot be in the past.";
    } elseif ($end_date < $start_date) {
        $errorMsg = "End date cannot be before start date.";
    } else {
        if ($application_id) {
            // Edit
            $UpdateLeaveData = $LeaveAppObj->UpdateLeaveData($empId, $leave_type_id, $start_date, $end_date, $application_id, "UpdateLeaveData");
            if ($UpdateLeaveData) {
                header("Location: leave_history.php");
                exit;
            } else {
                $errorMsg = "Update failed!";
            }
        } else {
            // Insert
            $InsertLeaveData = $LeaveAppObj->insertApplication($empId, $leave_type_id, $start_date, $end_date);
            if (isset($InsertLeaveData['status']) && $InsertLeaveData['status'] === 'success') {
                header("Location: leave_history.php");
                exit;
            } else {
                $errorMsg = "Insert failed!";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Leave Application</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="../../asset/css/style.css">
</head>

<body>
    <div class="container">
        <?php if (!empty($errorMsg)): ?>
            <div class="error"><?= $errorMsg ?></div>
        <?php endif; ?>
        <?php if (!empty($successMsg)): ?>
            <div class="success"><?= $successMsg ?></div>
        <?php endif; ?>

        <h1>Leave Application</h1>
        <h2>Welcome <?= htmlspecialchars($empName) ?></h2>

        <div class="leaveform">
            <form method="post" onsubmit="return confirm('Are you sure you want to <?= $application_id ? 'update' : 'submit' ?> this leave application?');">
                <?php if ($application_id): ?>
                    <input type="hidden" name="application_id" value="<?= htmlspecialchars($application_id) ?>">
                <?php endif; ?>

                <div class="form-detail">
                    <label for="leave_type_id">Leave Type :</label>
                    <select name="leave_type_id" id="leave_type_id" required>
                        <option value="">Select Leave Type</option>
                        <?php foreach ($leaveType['leaveIdName'] as $key => $value): ?>
                            <option value="<?= $key ?>" <?= ($leave_type_id == $key) ? 'selected' : '' ?>>
                                <?= htmlspecialchars(str_replace(" ", "_", $value)) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-detail">
                    <label>Leave Start Date:</label>
                    <input type="date" name="start_date" value="<?= htmlspecialchars($start_date) ?>" required>
                </div>

                <div class="form-detail">
                    <label>Leave End Date:</label>
                    <input type="date" name="end_date" value="<?= htmlspecialchars($end_date) ?>" required>
                </div>

                <div class="form-group button-container">
                    <button type="submit" class="my-custom-button" name="<?= $application_id ? 'edit_form' : 'submit_form' ?>">
                        Submit
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>

</html>