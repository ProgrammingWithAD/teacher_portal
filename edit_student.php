<?php
session_start();
require 'db/db.php';

if (!isset($_SESSION['teacher_id'])) {
    header('Location: login.php');
    exit;
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $pdo->prepare("SELECT * FROM students WHERE id = :id");
    $stmt->execute(['id' => $id]);
    $student = $stmt->fetch();

    if (!$student) {
        header('Location: index.php');
        exit;
    }
} else {
    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $subject = $_POST['subject'];
    $marks = $_POST['marks'];

    $stmt = $pdo->prepare("UPDATE students SET name = :name, subject = :subject, marks = :marks WHERE id = :id");
    $stmt->execute(['name' => $name, 'subject' => $subject, 'marks' => $marks, 'id' => $id]);

    header('Location: index.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Student</title>
</head>
<body>
    <h2>Edit Student</h2>
    <form method="post">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" value="<?= htmlspecialchars($student['name']) ?>" required>
        <br>
        <label for="subject">Subject:</label>
        <input type="text" id="subject" name="subject" value="<?= htmlspecialchars($student['subject']) ?>" required>
        <br>
        <label for="marks">Marks:</label>
        <input type="number" id="marks" name="marks" value="<?= htmlspecialchars($student['marks']) ?>" required>
        <br>
        <button type="submit">Save</button>
    </form>
</body>
</html>