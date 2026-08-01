<?php
/*
 * Student Performance Module
 * Handles marks entry, average calculation, and results via browser forms.
 */

session_start();
require_once "functions.php";

// Initialize session storage for students
if (!isset($_SESSION['students'])) {
    $_SESSION['students'] = [];
}

$error   = "";
$success = "";

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name       = trim($_POST['name']   ?? '');
    $marksInput = trim($_POST['marks']  ?? '');

    if (empty($name)) {
        $error = "Student name is required.";
    } elseif (empty($marksInput)) {
        $error = "Marks are required.";
    } else {
        $marks = array_map('intval', array_map('trim', explode(',', $marksInput)));

        if (!validateMarks($marks)) {
            $error = "All marks must be between 0 and 100.";
        } else {
            $avg    = calculateAverage($marks);
            $result = getResult($avg);

            $_SESSION['students'][] = [
                "name"    => htmlspecialchars($name),
                "marks"   => $marks,
                "average" => round($avg, 2),
                "result"  => $result
            ];

            $success = "Student \"" . htmlspecialchars($name) . "\" added. Average: " . round($avg, 2) . " — $result";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Performance Module</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f0f2f5; margin: 0; padding: 40px; }
        h1, h2 { color: #2c3e50; }
        a { color: #2c3e50; }
        form { background: white; padding: 25px; border-radius: 10px; max-width: 400px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); margin-bottom: 30px; }
        label { display: block; margin-top: 12px; font-weight: bold; }
        input { width: 100%; padding: 8px; margin-top: 5px; border: 1px solid #ccc; border-radius: 5px; box-sizing: border-box; }
        button { margin-top: 15px; padding: 10px 20px; background: #2c3e50; color: white; border: none; border-radius: 5px; cursor: pointer; }
        button:hover { background: #1a252f; }
        .error   { color: red;   margin-bottom: 10px; }
        .success { color: green; margin-bottom: 10px; }
        .distinction { color: #27ae60; font-weight: bold; }
        .pass        { color: #2980b9; font-weight: bold; }
        .fail        { color: #e74c3c; font-weight: bold; }
        table { width: 100%; border-collapse: collapse; background: white; border-radius: 10px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
        th { background: #2c3e50; color: white; padding: 10px; }
        td { padding: 10px; border-bottom: 1px solid #eee; text-align: center; }
        tr:last-child td { border-bottom: none; }
        .back { display: inline-block; margin-bottom: 20px; }
    </style>
</head>
<body>
    <a class="back" href="index.php">← Back to Main Menu</a>
    <h1>🎓 Student Performance Module</h1>

    <h2>Add Student Marks</h2>
    <form method="POST">
        <?php if ($error):   ?><p class="error"><?= $error ?></p><?php endif; ?>
        <?php if ($success): ?><p class="success"><?= $success ?></p><?php endif; ?>

        <label>Student Name</label>
        <input type="text" name="name" required>

        <label>Marks (comma separated)</label>
        <input type="text" name="marks" placeholder="e.g. 75, 80, 65" required>
        <small>Enter marks between 0 and 100, separated by commas.</small>

        <button type="submit">Add Student</button>
    </form>

    <h2>Student Results</h2>

    <?php if (empty($_SESSION['students'])): ?>
        <p>No students added yet.</p>
    <?php else: ?>
        <table>
            <tr><th>#</th><th>Name</th><th>Marks</th><th>Average</th><th>Result</th></tr>
            <?php foreach ($_SESSION['students'] as $i => $s): ?>
            <?php $class = strtolower($s['result']); ?>
            <tr>
                <td><?= $i + 1 ?></td>
                <td><?= $s['name'] ?></td>
                <td><?= implode(', ', $s['marks']) ?></td>
                <td><?= $s['average'] ?></td>
                <td class="<?= $class ?>"><?= $s['result'] ?></td>
            </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>
</body>
</html>
