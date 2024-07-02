<?php
session_start();
require 'db/db.php';

if (!isset($_SESSION['teacher_id'])) {
    header('Location: login.php');
    exit;
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $pdo->prepare("DELETE FROM students WHERE id = :id");
    $stmt->execute(['id' => $id]);
}

header('Location: index.php');
exit;
?>
