<?php
session_start();
// Sécurité Admin
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Location: ../login.php');
    exit();
}
require_once '../includes/db.php';
include '../includes/header.php'; 
?>

<div class="container">
    <h1 class="fw-bold mb-5 border-bottom border-3 border-dark pb-3 text-uppercase">Tableau de bord Admin</h1>

    <div class="row">
        
        <div class="col-12 mb-5">
            <div class="card p-4 rounded-0 thick-border">
                <h3 class="fw-bold mb-3 text-uppercase">📦 Dernières Commandes</h3>
                <?php 
                $stmt = $pdo->query("SELECT o.id, o.date_commande, u.nom, u.email, i.montant 
                                     FROM orders o 
                                     JOIN users u ON o.id_user = u.id 
                                     LEFT JOIN invoice i ON i.id_user = u.id AND DATE(i.date_transaction) = DATE(o.date_commande)
                                     ORDER BY o.date_commande DESC LIMIT 10");
                ?>
                <table class="table table-bordered border-dark align-middle">
                    <thead class="table-dark"><tr><th>#</th><th>Date</th><th>Client</th><th>Montant (Est.)</th><th>Action</th></tr></thead>
                    <tbody>
                        <?php while($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                        <tr>
                            <td class="fw-bold"><?= $row['id'] ?></td>
                            <td><?= date('d/m/Y H:i', strtotime($row['date_commande'])) ?></td>
                            <td>
                                <?= htmlspecialchars($row['nom']) ?><br>
                                <small class="text-muted"><?= htmlspecialchars($row['email']) ?></small>
                            </td>
                            <td class="fw-bold"><?= $row['montant'] ? number_format($row['montant'], 2) . ' €' : '-' ?></td>
                            <td>
                                <a href="order_details.php?id=<?= $row['id'] ?>" class="btn btn-dark btn-sm rounded-0 fw-bold">Voir Détails</a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
                <?php if($stmt->rowCount() == 0) echo "<p class='text-muted'>Aucune commande pour le moment.</p>"; ?>
            </div>
        </div>

        <div class="col-md-6 mb-5">
            <div class="card p-4 rounded-0 thick-border">
                <h3 class="fw-bold mb-3 text-uppercase">👤 Utilisateurs</h3>
                <?php $stmt = $pdo->query("SELECT id, nom, email, role FROM users LIMIT 10"); ?>
                <table class="table table-sm border-dark">
                    <thead><tr><th>ID</th><th>Nom</th><th>Role</th><th>Action</th></tr></thead>
                    <tbody>
                        <?php while($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                        <tr>
                            <td><?= $row['id'] ?></td>
                            <td><?= htmlspecialchars($row['nom']) ?></td>
                            <td><span class="badge bg-dark rounded-0"><?= $row['role'] ?></span></td>
                            <td>
                                <?php if($row['role'] !== 'admin'): ?>
                                    <a href="delete_user.php?id=<?= $row['id'] ?>" onclick="return confirm('Supprimer ?')" class="text-danger fw-bold">X</a>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="col-md-6 mb-5">
            <div class="card p-4 rounded-0 thick-border">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h3 class="fw-bold text-uppercase">🖼️ Produits</h3>
                    <a href="add_item.php" class="btn btn-success btn-sm rounded-0 fw-bold border-2 border-dark">+ AJOUTER</a>
                </div>
                <?php $stmt = $pdo->query("SELECT id, nom, prix FROM items LIMIT 10"); ?>
                <table class="table table-sm border-dark">
                    <thead><tr><th>Nom</th><th>Prix Base</th><th>Action</th></tr></thead>
                    <tbody>
                        <?php while($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                        <tr>
                            <td class="text-truncate-custom"><?= htmlspecialchars($row['nom']) ?></td>
                            <td><?= $row['prix'] ?> €</td>
                            <td>
                                <a href="edit_item.php?id=<?= $row['id'] ?>" class="text-warning fw-bold me-2">Modif</a>
                                <a href="delete_item.php?id=<?= $row['id'] ?>" onclick="return confirm('Supprimer ?')" class="text-danger fw-bold">Sup</a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>

<?php include '../includes/footer.php'; ?>