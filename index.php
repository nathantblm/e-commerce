<?php 
require_once 'includes/db.php';
include 'includes/header.php'; 

// Récupération des 3 produits "En Vedette"
$stmt = $pdo->query("SELECT * FROM items ORDER BY id ASC LIMIT 3");
$featured_items = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="row align-items-center mb-5 hero-section">
    <div class="col-12 text-center">
        <h1 class="display-3 fw-bold mb-4 text-uppercase hero-title">
            Créez votre<br>Décoration Unique
        </h1>
        
        <p class="lead mb-5 d-inline-block" style="max-width: 800px;">
            Des affiches personnalisées en noir & blanc, inspirées par vos souvenirs.<br>
            <span class="fs-6 text-muted">
                (Les articles du catalogue sont des exemples de réalisations existantes, mais tout est possible : nous pouvons créer un design totalement inédit pour vous !)
            </span>
            <br><br>
            <strong>Nathan code, Sam dessine, vous décorez.</strong>
        </p>
        <br>
        
        <a href="articles.php" 
           class="btn bg-white text-dark btn-lg rounded-0 fw-bold border-3 border-dark text-uppercase px-5 py-3 hard-shadow">
            Voir tout le catalogue
        </a>
    </div>
</div>

<div class="py-5 border-top border-3 border-dark">
    <div class="text-center mb-5">
        <h2 class="fw-bold text-uppercase display-5">À la une</h2>
        <p class="lead">Nos formats les plus populaires</p>
    </div>

    <div class="row g-4">
        <?php foreach ($featured_items as $article): ?>
            <div class="col-md-4">
                <div class="card h-100 rounded-0 thick-border hard-shadow border-0">
                    <img src="assets/uploads/<?= htmlspecialchars($article['image']) ?>" 
                         class="card-img-top rounded-0 border-bottom border-3 border-dark p-3 bg-white img-card-sm" 
                         alt="<?= htmlspecialchars($article['nom']) ?>">
                    
                    <div class="card-body text-center bg-white">
                        <h5 class="fw-bold text-uppercase"><?= htmlspecialchars($article['nom']) ?></h5>
                        <p class="fw-bold fs-4">À partir de 45,00 €</p>
                        <a href="item.php?id=<?= $article['id'] ?>" class="btn btn-dark rounded-0 w-100 fw-bold text-uppercase border-2 border-dark">
                            Personnaliser
                        </a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<div class="row mt-5 pt-5 border-top border-3 border-dark text-center">
    <div class="col-md-4 mb-4">
        <h3 class="fw-bold text-uppercase">🎨 Fait main</h3>
        <p>Illustrations uniques par Sam</p>
    </div>
    <div class="col-md-4 mb-4">
        <h3 class="fw-bold text-uppercase">🇫🇷 Made in France</h3>
        <p>Imprimé sur papier d'art</p>
    </div>
    <div class="col-md-4 mb-4">
        <h3 class="fw-bold text-uppercase">🚀 Livraison Rapide</h3>
        <p>Expédition sous 48h</p>
    </div>
</div>

<?php include 'includes/footer.php'; ?>