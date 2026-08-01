<?php
/*
 * Author: Kelebogile Rangata
 * Student Number: 402417475
 * Date: 19 March 2026
 * Description: Shared functions and constants used across all modules.
 */

// ================= CONSTANTS =================

define('PERMIT_STUDENT_PRICE', 450);
define('PERMIT_STAFF_PRICE', 750);
define('PERMIT_VISITOR_PRICE', 100);
define('MAX_PARKING_CAPACITY', 50);

// ================= PARKING FUNCTIONS =================

function validatePermitApplication($name, $age, $type, $currentCount) {
    if (empty($name)) return "Name is required.";
    if (!is_numeric($age) || $age < 18) return "Applicant must be 18 or older.";
    $validTypes = ["student", "staff", "visitor"];
    if (!in_array($type, $validTypes)) return "Invalid permit type.";
    if ($currentCount >= MAX_PARKING_CAPACITY) return "Parking is full.";
    return "";
}

function getPermitPrice($type) {
    if ($type == "student") return PERMIT_STUDENT_PRICE;
    if ($type == "staff")   return PERMIT_STAFF_PRICE;
    if ($type == "visitor") return PERMIT_VISITOR_PRICE;
    return 0;
}

// ================= PERFORMANCE FUNCTIONS =================

function calculateAverage($marks) {
    return array_sum($marks) / count($marks);
}

function getResult($avg) {
    if ($avg >= 75) return "Distinction";
    elseif ($avg >= 50) return "Pass";
    else return "Fail";
}

function validateMarks($marks) {
    foreach ($marks as $mark) {
        if ($mark < 0 || $mark > 100) return false;
    }
    return true;
}
