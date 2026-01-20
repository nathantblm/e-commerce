<?php 
require_once 'includes/db.php';
include 'includes/header.php'; 

$stmt = $pdo->query("SELECT * FROM items");
$articles = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h2 class="mb-4 pb-2 border-bottom">Tous nos produits</h2>

<div class="row row-cols-1 row-cols-md-3 g-4">
    <?php foreach ($articles as $article): ?>
        <div class="col">
            <div class="card h-100 shadow-sm">
                <div style="height: 200px; background-color: #eee; display:flex; align-items:center; justify-content:center; overflow:hidden;">
                    <img src="assets/uploads/<?= htmlspecialchars($article['image']) ?>" class="card-img-top" alt="..." style="width:100%;">
                </div>
                <div class="card-body">
                    <h5 class="card-title"><?= htmlspecialchars($article['nom']) ?></h5>
                    <p class="card-text text-muted"><?= substr($article['description'], 0, 50) ?>...</p>
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <span class="h5 mb-0"><?= number_format($article['prix'], 2) ?> €</span>
                        <a href="item.php?id=<?= $article['id'] ?>" class="btn btn-primary">Détails</a>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<?php include 'includes/footer.php'; ?>