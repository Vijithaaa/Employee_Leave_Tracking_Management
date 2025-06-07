<?php
// echo "functionfile";

// Function to format date as "22nd May 2025"
function formatDate($dateString)
{
    $date = new DateTime($dateString);
    $day = $date->format('jS M y');
    return $day;
}

// Function to calculate days between two dates
function calculateLeaveDays($startDate, $endDate)
{
    $start = new DateTime($startDate);
    $end = new DateTime($endDate);
    $interval = $start->diff($end);
    return $interval->days + 1; // +1 to include both start and end days
}

//fuction for employeeProfile
function getEmployeeProfileHtml($empImagePath = null, $baseImagePath = '/new/leaveTracking_oop/asset/images/employees/') {
    $html = '<div class="employee-profile">';
    
    // Handle image display
    if (!empty($empImagePath)) {
        $imageFile = basename($empImagePath);
        $fullImagePath = $baseImagePath . $imageFile;
        $html .= '<img src="' . htmlspecialchars($fullImagePath) . '" alt="Employee Photo" class="profile-photo">';
    }

    $html .= '</div>';
    
    return $html;
}