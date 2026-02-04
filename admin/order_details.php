<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Location: ../login.php');
    exit();
}
require_once '../includes/db.php';
include '../includes/header.php'; 

if (!isset($_GET['id'])) {
    die("Erreur : ID manquant");
}

$id_commande = $_GET['id'];

// 1. Infos générales
$sql = "SELECT o.*, u.nom, u.email, i.adresse_facturation, i.ville, i.code_postal, i.montant 
        FROM orders o
        JOIN users u ON o.id_user = u.id
        LEFT JOIN invoice i ON i.id_user = u.id AND DATE(i.date_transaction) = DATE(o.date_commande)
        WHERE o.id = ?";
$req = $pdo->prepare($sql);
$req->execute(array($id_commande));
$commande = $req->fetch(PDO::FETCH_ASSOC);

// 2. Les articles
$sqlItems = "SELECT oi.*, i.nom, i.image 
             FROM order_items oi
             JOIN items i ON oi.id_item = i.id
             WHERE oi.id_order = ?";
$reqItems = $pdo->prepare($sqlItems);
$reqItems->execute(array($id_commande));
$liste_articles = $reqItems->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container">
    <a href="index.php" class="btn btn-outline-dark rounded-0 mb-4">← Retour</a>

    <div class="card p-5 rounded-0 thick-border hard-shadow bg-white">
        <div class="d-flex justify-content-between align-items-center mb-4 border-bottom border-2 border-dark pb-3">
            <h1 class="fw-bold text-uppercase m-0">Commande #<?= $commande['id'] ?></h1>
            <span class="fs-5 text-muted"><?= date('d/m/Y H:i', strtotime($commande['date_commande'])) ?></span>
        </div>

        <div class="row mb-5">
            <div class="col-md-6">
                <h4 class="fw-bold text-uppercase">Client</h4>
                <p>
                    <strong>Nom :</strong> <?= htmlspecialchars($commande['nom']) ?><br>
                    <strong>Email :</strong> <?= htmlspecialchars($commande['email']) ?>
                </p>
            </div>
            <div class="col-md-6">
                <h4 class="fw-bold text-uppercase">Livraison</h4>
                <p>
                    <?= nl2br(htmlspecialchars($commande['adresse_facturation'])) ?><br>
                    <?= htmlspecialchars($commande['code_postal']) ?> <?= htmlspecialchars($commande['ville']) ?>
                </p>
            </div>
        </div>

        <h4 class="fw-bold text-uppercase mb-3">Articles</h4>
        <table class="table table-bordered border-dark">
            <thead class="table-dark">
                <tr>
                    <th>Produit</th>
                    <th>Infos</th>
                    <th>Qté</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($liste_articles as $art) { ?>
                <tr>
                    <td><?= htmlspecialchars($art['nom']) ?></td>
                    <td><em><?= nl2br(htmlspecialchars($art['personnalisation'])) ?></em></td>
                    <td class="fw-bold text-center"><?= $art['quantite'] ?></td>
                </tr>
                <?php } ?>
            </tbody>
        </table>

        <div class="text-end mt-4">
            <h3 class="fw-bold">TOTAL : <?= number_format($commande['montant'], 2) ?> €</h3>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>