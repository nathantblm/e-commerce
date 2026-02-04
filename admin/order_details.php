<?php
session_start();
require_once '../includes/db.php';

// Redirection si pas admin ou pas d'ID
if (($_SESSION['user']['role'] ?? '') !== 'admin' || !isset($_GET['id'])) {
    header('Location: index.php'); exit();
}

// 1. Récupération tout-en-un (Commande + Client + Facture)
$stmt = $pdo->prepare("SELECT o.*, u.nom, u.email, i.adresse_facturation, i.ville, i.code_postal, i.montant 
    FROM orders o JOIN users u ON o.id_user = u.id 
    LEFT JOIN invoice i ON i.id_user = u.id AND DATE(i.date_transaction) = DATE(o.date_commande)
    WHERE o.id = ?");
$stmt->execute([$_GET['id']]);
$order = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$order) { die("Commande introuvable."); }

// 2. Récupération des articles
$stmt = $pdo->prepare("SELECT oi.*, i.nom, i.image, i.description, i.prix as prix_base 
    FROM order_items oi LEFT JOIN items i ON oi.id_item = i.id WHERE oi.id_order = ?");
$stmt->execute([$_GET['id']]);
$items = $stmt->fetchAll(PDO::FETCH_ASSOC);

include '../includes/header.php'; 
?>

<div class="container">
    <a href="index.php" class="btn btn-outline-dark rounded-0 mb-4">← Retour</a>

    <div class="card p-4 rounded-0 thick-border hard-shadow bg-white">
        <div class="d-flex justify-content-between border-bottom border-2 border-dark pb-3 mb-4">
            <h2 class="fw-bold m-0">COMMANDE #<?= $order['id'] ?></h2>
            <span class="text-muted"><?= date('d/m/Y \à H\hi', strtotime($order['date_commande'])) ?></span>
        </div>

        <div class="row mb-4">
            <div class="col-md-6">
                <h5 class="fw-bold text-uppercase">CLIENT</h5>
                <p class="mb-1"><strong>Nom :</strong> <?= htmlspecialchars($order['nom']) ?></p>
                <p><strong>Email :</strong> <?= htmlspecialchars($order['email']) ?></p>
            </div>
            
            <div class="col-md-6">
                <h5 class="fw-bold text-uppercase">LIVRAISON</h5>
                <p class="mb-0"><?= nl2br(htmlspecialchars($order['adresse_facturation'])) ?></p>
                <p><?= htmlspecialchars($order['code_postal']) ?> <?= htmlspecialchars($order['ville']) ?></p>
            </div>
        </div>

        <table class="table table-bordered border-dark align-middle">
            <thead class="table-dark text-uppercase">
                <tr><th>Photo</th><th>Infos</th><th>Prix U.</th><th class="text-center">Qté</th><th class="text-end">Total</th></tr>
            </thead>
            <tbody>
                <?php foreach($items as $item): 
                    // Logique de calcul conservée (celle qui marche)
                    $prix = (count($items) === 1) ? ($order['montant'] / $item['quantite']) : $item['prix_base'];
                    
                    $taille = match(true) {
                        $prix >= 119 => 'A1', $prix >= 79 => 'A2', $prix >= 59 => 'A3', default => 'A4'
                    };
                ?>
                <tr>
                    <td class="text-center" style="width: 80px;">
                        <img src="../assets/uploads/<?= htmlspecialchars($item['image'] ?? 'default.jpg') ?>" width="60" class="border border-dark">
                    </td>
                    <td>
                        <strong class="text-uppercase"><?= htmlspecialchars($item['nom']) ?></strong> 
                        <span class="badge bg-dark rounded-0"><?= $taille ?></span><br>
                        <small class="text-muted"><?= substr(htmlspecialchars($item['description'] ?? ''), 0, 50) ?>...</small>
                        <?php if(!empty($item['personnalisation'])): ?>
                            <div class="small fst-italic mt-1">"<?= htmlspecialchars($item['personnalisation']) ?>"</div>
                        <?php endif; ?>
                    </td>
                    <td class="text-nowrap"><?= number_format($prix, 2) ?> €</td>
                    <td class="text-center fw-bold fs-5"><?= $item['quantite'] ?></td>
                    <td class="text-end fw-bold"><?= number_format($prix * $item['quantite'], 2) ?> €</td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="text-end mt-3">
            <h3 class="fw-bold">TOTAL : <?= number_format($order['montant'], 2) ?> €</h3>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>