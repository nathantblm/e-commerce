<?php 
require_once 'includes/db.php';
include 'includes/header.php'; 

if (isset($_GET['id'])) {
    $stmt = $pdo->prepare("SELECT * FROM items WHERE id = ?");
    $stmt->execute([$_GET['id']]);
    $item = $stmt->fetch(PDO::FETCH_ASSOC);
}

if (!$item) {
    echo "<div class='container mt-5'><div class='alert alert-danger'>Produit introuvable</div></div>";
    include 'includes/footer.php';
    exit();
}
?>

<div class="row mt-5 align-items-center">
    <div class="col-md-6 text-center">
        <img src="/e-commerce/assets/uploads/<?= htmlspecialchars($item['image']) ?>" 
             class="img-fluid rounded shadow-sm" 
             style="max-height: 500px;">
    </div>
    
    <div class="col-md-6">
        <h2 class="fw-bold"><?= htmlspecialchars($item['nom']) ?></h2>
        <h3 class="text-primary my-3"><?= number_format($item['prix'], 2) ?> €</h3>
        <p class="text-muted"><?= nl2br(htmlspecialchars($item['description'])) ?></p>
        
        <hr>

        <form action="cart.php" method="POST" class="p-4 bg-white border rounded shadow-sm">
            <input type="hidden" name="id_item" value="<?= $item['id'] ?>">
            
            <div class="mb-3">
                <label class="form-label fw-bold">Quantité</label>
                <input type="number" name="quantite" value="1" min="1" max="10" class="form-control">
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Personnalisation (Texte)</label>
                <textarea name="custom_text" class="form-control" rows="2" placeholder="Ajouter un petit mot..."></textarea>
            </div>

            <button type="submit" name="add_to_cart" class="btn btn-primary w-100 btn-lg">
                Ajouter au panier
            </button>
        </form>
    </div>
</div>

<?php include 'includes/footer.php'; ?>