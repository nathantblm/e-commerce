<?php
session_start();
require_once '../includes/db.php';

// Vérif admin
if ($_SESSION['user']['role'] !== 'admin') {
    header('Location: ../login.php');
}

$id = $_GET['id'];

// Si le formulaire est posté
if (isset($_POST['btn_update'])) {
    $nom = htmlspecialchars($_POST['nom']);
    $desc = htmlspecialchars($_POST['description']);
    $prix = floatval($_POST['prix']);
    $stock = intval($_POST['stock']);

    // Je mets à jour le produit
    $req = $pdo->prepare("UPDATE items SET nom=?, description=?, prix=? WHERE id=?");
    $req->execute(array($nom, $desc, $prix, $id));

    // Je mets à jour le stock
    $reqStock = $pdo->prepare("UPDATE stock SET quantite=? WHERE id_item=?");
    $reqStock->execute(array($stock, $id));

    header('Location: index.php');
    exit();
}

// Je récupère les infos du produit pour remplir le formulaire
$req = $pdo->prepare("SELECT i.*, s.quantite FROM items i LEFT JOIN stock s ON i.id = s.id_item WHERE i.id = ?");
$req->execute(array($id));
$produit = $req->fetch();

include '../includes/header.php';
?>

<div class="container w-50">
    <h2 class="fw-bold mb-4">MODIFIER LE PRODUIT</h2>
    <form method="POST" class="card p-4 rounded-0 thick-border hard-shadow">
        
        <div class="mb-3">
            <label>Nom</label>
            <input type="text" name="nom" class="form-control rounded-0 border-dark" value="<?= $produit['nom'] ?>" required>
        </div>
        
        <div class="mb-3">
            <label>Description</label>
            <textarea name="description" class="form-control rounded-0 border-dark"><?= $produit['description'] ?></textarea>
        </div>
        
        <div class="mb-3">
            <label>Prix (€)</label>
            <input type="number" step="0.01" name="prix" class="form-control rounded-0 border-dark" value="<?= $produit['prix'] ?>" required>
        </div>
        
        <div class="mb-3">
            <label>Stock</label>
            <input type="number" name="stock" class="form-control rounded-0 border-dark" value="<?= $produit['quantite'] ?>" required>
        </div>
        
        <button type="submit" name="btn_update" class="btn btn-warning w-100 rounded-0 fw-bold border-2 border-dark">METTRE À JOUR</button>
    </form>
</div>
<?php include '../includes/footer.php'; ?>