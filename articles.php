<?php 
require_once 'includes/db.php';
include 'includes/header.php'; 

$stmt = $pdo->query("SELECT * FROM items ORDER BY prix ASC");
$articles = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="text-center mb-5">
    <h1 class="display-4 fw-bold text-uppercase">Nos Formats</h1>
    <p class="lead">Nathan code, Sam dessine, vous décorez.</p>
</div>

<div class="row g-4">
    <?php foreach ($articles as $article): ?>
        <div class="col-md-6 col-lg-3">
            <div class="card h-100 rounded-0 thick-border hard-shadow border-0">
                <img src="assets/uploads/<?= htmlspecialchars($article['image']) ?>" 
                     class="card-img-top rounded-0 border-bottom border-3 border-dark p-4 bg-white img-card-md" 
                     alt="<?= htmlspecialchars($article['nom']) ?>">
                
                <div class="card-body d-flex flex-column text-center bg-white">
                    <h5 class="card-title fw-bold text-uppercase"><?= htmlspecialchars($article['nom']) ?></h5>
                    
                    <div class="mt-auto pt-3">
                        <h3 class="fw-bold mb-3"><?= number_format($article['prix'], 2) ?> €</h3>
                        <a href="item.php?id=<?= $article['id'] ?>" class="btn btn-dark rounded-0 w-100 fw-bold text-uppercase border-2 border-dark stretched-link">
                            Personnaliser
                        </a>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<?php include 'includes/footer.php'; ?>