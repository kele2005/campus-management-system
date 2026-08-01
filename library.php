<?php
/*
 * Library Module
 * Handles book borrowing and fine calculation via browser forms.
 */

session_start();
require_once "functions.php";

// Initialize session storage for borrowed books
if (!isset($_SESSION['libraryBooks'])) {
    $_SESSION['libraryBooks'] = [];
}

$success = "";

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $book = trim($_POST['book'] ?? '');
    $days = (int) ($_POST['days'] ?? 0);

    if (!empty($book) && $days > 0) {
        $fine = ($days > 7) ? ($days - 7) * 5 : 0;

        $_SESSION['libraryBooks'][] = [
            "book" => htmlspecialchars($book),
            "days" => $days,
            "fine" => $fine
        ];

        $success = "Book \"" . htmlspecialchars($book) . "\" borrowed successfully." . ($fine > 0 ? " Overdue fine: R$fine" : " No fine.");
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Library Module</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f0f2f5; margin: 0; padding: 40px; }
        h1, h2 { color: #2c3e50; }
        a { color: #2c3e50; }
        form { background: white; padding: 25px; border-radius: 10px; max-width: 400px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); margin-bottom: 30px; }
        label { display: block; margin-top: 12px; font-weight: bold; }
        input { width: 100%; padding: 8px; margin-top: 5px; border: 1px solid #ccc; border-radius: 5px; box-sizing: border-box; }
        button { margin-top: 15px; padding: 10px 20px; background: #2c3e50; color: white; border: none; border-radius: 5px; cursor: pointer; }
        button:hover { background: #1a252f; }
        .success { color: green; margin-bottom: 10px; }
        .fine     { color: red; font-weight: bold; }
        table { width: 100%; border-collapse: collapse; background: white; border-radius: 10px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
        th { background: #2c3e50; color: white; padding: 10px; }
        td { padding: 10px; border-bottom: 1px solid #eee; text-align: center; }
        tr:last-child td { border-bottom: none; }
        .back { display: inline-block; margin-bottom: 20px; }
    </style>
</head>
<body>
    <a class="back" href="index.php">← Back to Main Menu</a>
    <h1>📚 Library Module</h1>

    <h2>Borrow a Book</h2>
    <form method="POST">
        <?php if ($success): ?><p class="success"><?= $success ?></p><?php endif; ?>

        <label>Book Name</label>
        <input type="text" name="book" required>

        <label>Days Borrowed</label>
        <input type="number" name="days" min="1" required>
        <small>Fine of R5/day applies after 7 days.</small>

        <button type="submit">Borrow</button>
    </form>

    <h2>Borrowed Books</h2>

    <?php if (empty($_SESSION['libraryBooks'])): ?>
        <p>No books borrowed yet.</p>
    <?php else: ?>
        <table>
            <tr><th>#</th><th>Book</th><th>Days</th><th>Fine</th></tr>
            <?php foreach ($_SESSION['libraryBooks'] as $i => $b): ?>
            <tr>
                <td><?= $i + 1 ?></td>
                <td><?= $b['book'] ?></td>
                <td><?= $b['days'] ?></td>
                <td class="<?= $b['fine'] > 0 ? 'fine' : '' ?>">
                    <?= $b['fine'] > 0 ? "R{$b['fine']}" : "None" ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>
</body>
</html>
