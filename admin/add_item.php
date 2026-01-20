<?php
session_start();
require_once '../includes/db.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Location: ../login.php'); exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = $_POST['nom'];
    $desc = $_POST['description'];
    $prix = $_POST['prix'];
    
    // Upload image simple
    $imgName = "default.png";
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $imgName = time() . "_" . $_FILES['image']['name'];
        move_uploaded_file($_FILES['image']['tmp_name'], "../assets/uploads/" . $imgName);
    }

    $stmt = $pdo->prepare("INSERT INTO items (nom, description, prix, image) VALUES (?, ?, ?, ?)");
    $stmt->execute([$nom, $desc, $prix, $imgName]);
    
    // On crée aussi le stock
    $id = $pdo->lastInsertId();
    $pdo->query("INSERT INTO stock (id_item, quantite) VALUES ($id, 100)");

    header('Location: index.php'); exit();
}

include '../includes/header.php';
?>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-primary text-white">Ajouter un produit</div>
            <div class="card-body">
                <form method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label>Nom du produit</label>
                        <input type="text" name="nom" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Description</label>
                        <textarea name="description" class="form-control" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label>Prix (€)</label>
                        <input type="number" step="0.01" name="prix" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Image</label>
                        <input type="file" name="image" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-success w-100">Enregistrer</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>