<?php
session_start();
require_once '../includes/db.php';

if (isset($_GET['id']) && isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin') {
    $id = intval($_GET['id']);
    // On supprime d'abord le stock lié, puis l'item
    $pdo->query("DELETE FROM stock WHERE id_item = $id");
    $pdo->query("DELETE FROM items WHERE id = $id");
}
header('Location: index.php');
exit();
?>