<?php
/*
 * Author: Kelebogile Rangata
 * Student Number: 402417475
 * Date: 19 March 2026
 * Description: Main entry point for the Campus Management System (console-based).
 */

require_once "functions.php";

// Initialize all data arrays so data persists across module visits
$permits      = [];
$libraryBooks = [];
$students     = [];

$running = true;

while ($running) {
    echo "\n============================\n";
    echo "  Campus Management System\n";
    echo "============================\n";
    echo "1) Parking Module\n";
    echo "2) Library Module\n";
    echo "3) Student Performance Module\n";
    echo "4) Exit\n";

    $choice = readline("Choose: ");

    switch ($choice) {

        case 1:
            // --- Parking Module ---
            $parkingRunning = true;
            while ($parkingRunning) {
                echo "\n============================\n";
                echo "Parking Module\n";
                echo "============================\n";
                echo "1) Issue Permit\n";
                echo "2) View Permits\n";
                echo "3) Back\n";

                $c = readline("Choose: ");

                if ($c == 3) { $parkingRunning = false; break; }

                switch ($c) {
                    case 1:
                        $name = readline("Name: ");
                        $age  = readline("Age: ");
                        echo "1) Student 2) Staff 3) Visitor\n";
                        $typeChoice = readline("Type: ");

                        $typeMap = ["1" => "student", "2" => "staff", "3" => "visitor"];
                        $type    = $typeMap[$typeChoice] ?? "";

                        $error = validatePermitApplication($name, $age, $type, count($permits));

                        if ($error !== "") {
                            echo "Error: $error\n";
                        } else {
                            $price     = getPermitPrice($type);
                            $permits[] = ["name" => $name, "age" => $age, "type" => $type, "price" => $price];
                            echo "Permit Issued! R" . number_format($price, 2) . "\n";
                        }
                        break;

                    case 2:
                        echo "\n--- Permits ---\n";
                        if (empty($permits)) {
                            echo "No permits issued.\n";
                        } else {
                            foreach ($permits as $i => $p) {
                                $typeLabel = ucfirst($p['type']);
                                echo ($i + 1) . ") {$p['name']} | Age: {$p['age']} | $typeLabel | R" . number_format($p['price'], 2) . "\n";
                            }
                        }
                        break;

                    default:
                        echo "Invalid option.\n";
                }
            }
            break;

        case 2:
            // --- Library Module ---
            $libraryRunning = true;
            while ($libraryRunning) {
                echo "\n============================\n";
                echo "Library Module\n";
                echo "============================\n";
                echo "1) Borrow Book\n";
                echo "2) View Borrowed Books\n";
                echo "3) Back\n";

                $c = readline("Choose: ");

                if ($c == 3) { $libraryRunning = false; break; }

                switch ($c) {
                    case 1:
                        $book = readline("Book Title: ");
                        $days = (int) readline("Days Borrowed: ");
                        $fine = ($days > 7) ? ($days - 7) * 5 : 0;

                        $libraryBooks[] = ["book" => $book, "days" => $days, "fine" => $fine];
                        echo "Book borrowed successfully.\n";
                        if ($fine > 0) {
                            echo "Overdue fine: R" . number_format($fine, 2) . "\n";
                        }
                        break;

                    case 2:
                        echo "\n--- Borrowed Books ---\n";
                        if (empty($libraryBooks)) {
                            echo "No books borrowed.\n";
                        } else {
                            foreach ($libraryBooks as $i => $b) {
                                $fineStr = $b['fine'] > 0 ? "Fine: R" . number_format($b['fine'], 2) : "No fine";
                                echo ($i + 1) . ") {$b['book']} | {$b['days']} days | $fineStr\n";
                            }
                        }
                        break;

                    default:
                        echo "Invalid option.\n";
                }
            }
            break;

        case 3:
            // --- Student Performance Module ---
            $perfRunning = true;
            while ($perfRunning) {
                echo "\n============================\n";
                echo "Student Performance Module\n";
                echo "============================\n";
                echo "1) Add Student\n";
                echo "2) View Students\n";
                echo "3) Back\n";

                $c = readline("Choose: ");

                if ($c == 3) { $perfRunning = false; break; }

                switch ($c) {
                    case 1:
                        $name       = readline("Student Name: ");
                        $marksInput = readline("Enter Marks (comma separated): ");
                        $marks      = array_map('intval', array_map('trim', explode(',', $marksInput)));

                        if (!validateMarks($marks)) {
                            echo "Error: Marks must be between 0 and 100.\n";
                            break;
                        }

                        $avg        = calculateAverage($marks);
                        $result     = getResult($avg);
                        $students[] = ["name" => $name, "marks" => $marks, "average" => round($avg, 2), "result" => $result];

                        echo "Student added. Average: " . round($avg, 2) . " | Result: $result\n";
                        break;

                    case 2:
                        echo "\n--- Students ---\n";
                        if (empty($students)) {
                            echo "No students added.\n";
                        } else {
                            foreach ($students as $i => $s) {
                                $marksStr = implode(', ', $s['marks']);
                                echo ($i + 1) . ") {$s['name']} | Marks: $marksStr | Average: {$s['average']} | {$s['result']}\n";
                            }
                        }
                        break;

                    default:
                        echo "Invalid option.\n";
                }
            }
            break;

        case 4:
            echo "Goodbye!\n";
            $running = false;
            break;

        default:
            echo "Invalid option. Please choose 1-4.\n";
    }
}
