<?php
session_start();
require_once 'includes/db.php';

// Sécurité
if (!isset($_SESSION['user']) || empty($_SESSION['cart'])) {
    header('Location: cart.php'); exit();
}

$id_user = $_SESSION['user']['id'];

// 1. Création de la commande
$pdo->query("INSERT INTO orders (id_user, date_commande) VALUES ($id_user, NOW())");
$id_order = $pdo->lastInsertId();

// 2. Insertion des lignes (SANS LA TAILLE)
foreach ($_SESSION['cart'] as $item) {
    $stmt = $pdo->prepare("INSERT INTO order_items (id_order, id_item, quantite, personnalisation) VALUES (?, ?, ?, ?)");
    $stmt->execute([$id_order, $item['id_item'], $item['quantite'], $item['personnalisation']]);
    
    // On décrémente le stock
    $pdo->query("UPDATE stock SET quantite = quantite - {$item['quantite']} WHERE id_item = {$item['id_item']}");
}

// 3. On vide le panier
unset($_SESSION['cart']);

include 'includes/header.php';
?>

<div class="container text-center mt-5">
    <div class="alert alert-success py-5">
        <h1 class="display-4">Merci !</h1>
        <p class="lead">Votre commande #<?= $id_order ?> a été validée avec succès.</p>
        <hr>
        <p>Vous recevrez bientôt vos affiches.</p>
        <a href="index.php" class="btn btn-primary mt-3">Retour à l'accueil</a>
    </div>
</div>

<?php include 'includes/footer.php'; ?>