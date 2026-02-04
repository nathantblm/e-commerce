<?php
session_start();
require_once 'includes/db.php';

// Sécurité
if (!isset($_SESSION['user']) || empty($_SESSION['cart'])) {
    header('Location: cart.php');
    exit();
}

// Je récupère les infos
$id_client = $_SESSION['user']['id'];
$adresse = htmlspecialchars($_POST['adresse']);
$ville = htmlspecialchars($_POST['ville']);
$cp = htmlspecialchars($_POST['code_postal']);
$total = floatval($_POST['total_amount']);

// 1. Créer la commande
$req = $pdo->prepare("INSERT INTO orders (id_user, date_commande) VALUES (?, NOW())");
$req->execute(array($id_client));
$id_commande = $pdo->lastInsertId();

// 2. Créer la facture
$reqFacture = $pdo->prepare("INSERT INTO invoice (id_user, date_transaction, montant, adresse_facturation, ville, code_postal) VALUES (?, NOW(), ?, ?, ?, ?)");
$reqFacture->execute(array($id_client, $total, $adresse, $ville, $cp));

// 3. Insérer les articles et baisser le stock
foreach ($_SESSION['cart'] as $article) {
    // J'ajoute la ligne
    $sql = "INSERT INTO order_items (id_order, id_item, quantite, personnalisation) VALUES (?, ?, ?, ?)";
    $reqLigne = $pdo->prepare($sql);
    $reqLigne->execute(array($id_commande, $article['id_item'], $article['quantite'], $article['personnalisation']));

    // Je mets à jour le stock
    $reqStock = $pdo->prepare("UPDATE stock SET quantite = quantite - ? WHERE id_item = ?");
    $reqStock->execute(array($article['quantite'], $article['id_item']));
}

// Je vide le panier
unset($_SESSION['cart']);

include 'includes/header.php';
?>

<div class="text-center mt-5">
    <h1 class="display-1 text-success">✅</h1>
    <h2 class="fw-bold">MERCI POUR VOTRE COMMANDE !</h2>
    <p class="lead">Votre commande #<?= $id_commande ?> a bien été enregistrée.</p>
    <a href="index.php" class="btn btn-dark mt-4 rounded-0 border-2 border-dark">RETOUR À L'ACCUEIL</a>
</div>

<?php include 'includes/footer.php'; ?>