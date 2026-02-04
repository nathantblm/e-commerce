<?php
session_start();
require_once '../includes/db.php';
if ($_SESSION['user']['role'] === 'admin' && isset($_GET['id'])) {
    $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
    $stmt->execute([$_GET['id']]);
}
header('Location: index.php');
?>