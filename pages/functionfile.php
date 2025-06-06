<?php

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
