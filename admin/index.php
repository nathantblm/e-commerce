<?php
session_start();
// Sécurité : On vérifie si c'est un admin (Vérifie bien que ton user dans la BDD a le role 'admin')
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Location: ../login.php');
    exit();
}
require_once '../includes/db.php';
// On inclut le header en ajustant le chemin (../)
include '../includes/header.php'; 
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Tableau de bord Administrateur</h1>
    <a href="add_item.php" class="btn btn-success">+ Ajouter un produit</a>
</div>

<div class="card shadow-sm">
    <div class="card-header bg-dark text-white">
        Gestion des produits
    </div>
    <div class="card-body">
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Nom</th>
                    <th>Prix</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $stmt = $pdo->query("SELECT * FROM items");
                while($row = $stmt->fetch(PDO::FETCH_ASSOC)): 
                ?>
                <tr>
                    <td>
                        <img src="../assets/uploads/<?= htmlspecialchars($row['image']) ?>" width="50" height="50" style="object-fit:cover;">
                    </td>
                    <td class="align-middle fw-bold"><?= htmlspecialchars($row['nom']) ?></td>
                    <td class="align-middle"><?= $row['prix'] ?> €</td>
                    <td class="align-middle">
                        <a href="#" class="btn btn-sm btn-primary">Modifier</a>
                        <a href="delete_item.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Supprimer ce produit ?')">Supprimer</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include '../includes/footer.php'; ?>