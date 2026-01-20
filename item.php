<?php 
require_once 'includes/db.php';
include 'includes/header.php'; 

if (isset($_GET['id'])) {
    $stmt = $pdo->prepare("SELECT * FROM items WHERE id = ?");
    $stmt->execute([$_GET['id']]);
    $item = $stmt->fetch(PDO::FETCH_ASSOC);
}
if (!$item) die("Produit introuvable");
?>

<div class="card shadow-sm mt-4">
    <div class="row g-0">
        <div class="col-md-5 bg-light d-flex align-items-center justify-content-center">
             <img src="assets/uploads/<?= htmlspecialchars($item['image']) ?>" class="img-fluid rounded-start" alt="...">
        </div>
        <div class="col-md-7">
            <div class="card-body p-5">
                <h2 class="card-title mb-3"><?= htmlspecialchars($item['nom']) ?></h2>
                <h4 class="text-primary mb-4"><?= number_format($item['prix'], 2) ?> €</h4>
                <p class="card-text mb-4"><?= nl2br(htmlspecialchars($item['description'])) ?></p>
                
                <form action="cart.php" method="POST">
                    <input type="hidden" name="id_item" value="<?= $item['id'] ?>">
                    
                    <div class="mb-3 w-50">
                        <label class="form-label">Quantité</label>
                        <input type="number" name="quantite" class="form-control" value="1" min="1">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Personnalisation (Optionnel)</label>
                        <textarea name="custom_text" class="form-control" rows="2"></textarea>
                    </div>

                    <button type="submit" name="add_to_cart" class="btn btn-success btn-lg w-100">
                        Ajouter au panier
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>