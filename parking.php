<?php
/*
 * Parking Permit Module
 * Handles permit applications and pricing via browser forms.
 */

session_start();
require_once "functions.php";

// Initialize session storage for permits
if (!isset($_SESSION['permits'])) {
    $_SESSION['permits'] = [];
}

$error   = "";
$success = "";

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $age  = trim($_POST['age']  ?? '');
    $type = trim($_POST['type'] ?? '');

    $error = validatePermitApplication($name, $age, $type, count($_SESSION['permits']));

    if ($error === "") {
        $price = getPermitPrice($type);
        $_SESSION['permits'][] = [
            "name"  => htmlspecialchars($name),
            "age"   => (int) $age,
            "type"  => $type,
            "price" => $price
        ];
        $success = "Permit issued successfully! Price: R$price";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Parking Permit Module</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f0f2f5; margin: 0; padding: 40px; }
        h1, h2 { color: #2c3e50; }
        a { color: #2c3e50; }
        form { background: white; padding: 25px; border-radius: 10px; max-width: 400px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); margin-bottom: 30px; }
        label { display: block; margin-top: 12px; font-weight: bold; }
        input, select { width: 100%; padding: 8px; margin-top: 5px; border: 1px solid #ccc; border-radius: 5px; box-sizing: border-box; }
        button { margin-top: 15px; padding: 10px 20px; background: #2c3e50; color: white; border: none; border-radius: 5px; cursor: pointer; }
        button:hover { background: #1a252f; }
        .error   { color: red;   margin-bottom: 10px; }
        .success { color: green; margin-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; background: white; border-radius: 10px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
        th { background: #2c3e50; color: white; padding: 10px; }
        td { padding: 10px; border-bottom: 1px solid #eee; text-align: center; }
        tr:last-child td { border-bottom: none; }
        .back { display: inline-block; margin-bottom: 20px; }
    </style>
</head>
<body>
    <a class="back" href="index.php">← Back to Main Menu</a>
    <h1>🚗 Parking Permit Module</h1>

    <h2>Apply for a Permit</h2>
    <form method="POST">
        <?php if ($error):   ?><p class="error"><?= $error ?></p><?php endif; ?>
        <?php if ($success): ?><p class="success"><?= $success ?></p><?php endif; ?>

        <label>Full Name</label>
        <input type="text" name="name" required>

        <label>Age</label>
        <input type="number" name="age" min="18" required>

        <label>Permit Type</label>
        <select name="type">
            <option value="student">Student (R<?= PERMIT_STUDENT_PRICE ?>)</option>
            <option value="staff">Staff (R<?= PERMIT_STAFF_PRICE ?>)</option>
            <option value="visitor">Visitor (R<?= PERMIT_VISITOR_PRICE ?>)</option>
        </select>

        <button type="submit">Apply</button>
    </form>

    <h2>All Permits (<?= count($_SESSION['permits']) ?> / <?= MAX_PARKING_CAPACITY ?>)</h2>

    <?php if (empty($_SESSION['permits'])): ?>
        <p>No permits issued yet.</p>
    <?php else: ?>
        <table>
            <tr><th>#</th><th>Name</th><th>Age</th><th>Type</th><th>Price</th></tr>
            <?php foreach ($_SESSION['permits'] as $i => $p): ?>
            <tr>
                <td><?= $i + 1 ?></td>
                <td><?= $p['name'] ?></td>
                <td><?= $p['age'] ?></td>
                <td><?= ucfirst($p['type']) ?></td>
                <td>R<?= $p['price'] ?></td>
            </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>
</body>
</html>
