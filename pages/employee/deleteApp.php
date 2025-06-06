<?php
session_start();
// include 'functionfile.php';
// include '../navbar.php';
require_once '../../models/leaveApplication.php';

$LeaveAppObj = new LeaveApplication();


// Verify user is logged in
if (!isset($_SESSION['emp_logged_in']) || $_SESSION['emp_logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

// Get the application_id from POST data
$application_id = $_POST['application_id'] ?? null;
$empId = $_SESSION['empId'];

// echo $application_id.$empId;

$deleteRow = $LeaveAppObj->deleteApplication($empId, $application_id);

if ($deleteRow) {
    // echo "data deleted";
    header("Location: leave_history.php");
    exit;
}
