<?php
session_start();
require_once '../includes/db.php';

// Sécurité admin
if (isset($_SESSION['user']) && $_SESSION['user']['role'] == 'admin' && isset($_GET['id'])) {
    
    $id_a_supprimer = $_GET['id'];

    // Je supprime d'abord le stock lié à ce produit (sinon erreur SQL Foreign Key)
    $pdo->query("DELETE FROM stock WHERE id_item = $id_a_supprimer");

    // Ensuite je supprime le produit
    $pdo->query("DELETE FROM items WHERE id = $id_a_supprimer");
}

// Je reviens à la liste
header('Location: index.php');
exit();
?>