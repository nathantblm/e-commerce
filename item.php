<?php 
require_once 'includes/db.php';
include 'includes/header.php'; 

// 1. Récupération de l'article (Design)
if (isset($_GET['id'])) {
    $stmt = $pdo->prepare("SELECT * FROM items WHERE id = ?");
    $stmt->execute([$_GET['id']]);
    $item = $stmt->fetch(PDO::FETCH_ASSOC);
}

// 2. Si l'article n'existe pas
if (!$item) {
    echo "<div class='alert alert-danger rounded-0 border-2 border-dark'>Design introuvable. <a href='articles.php'>Retour au catalogue</a></div>";
    include 'includes/footer.php';
    exit();
}
?>

<div class="row align-items-center">
    <div class="col-md-6 mb-4 mb-md-0">
        <img src="assets/uploads/<?= htmlspecialchars($item['image']) ?>" 
             class="img-fluid bg-white p-3 thick-border hard-shadow w-100 img-product-lg" 
             alt="<?= htmlspecialchars($item['nom']) ?>">
    </div>
    
    <div class="col-md-6">
        <h1 class="fw-bold display-5 text-uppercase"><?= htmlspecialchars($item['nom']) ?></h1>
        <p class="lead text-muted"><?= nl2br(htmlspecialchars($item['description'])) ?></p>
        
        <h2 class="fw-bold my-3 text-success display-4" id="displayPrice">45,00 €</h2>
        
        <hr class="border-dark border-2 opacity-100 my-4">
        
        <form action="cart.php" method="POST" class="p-4 bg-light thick-border">
            <input type="hidden" name="id_item" value="<?= $item['id'] ?>">
            
            <div class="mb-3">
                <label for="tailleSelect" class="form-label fw-bold text-uppercase">Choisir le format</label>
                <select name="taille" id="tailleSelect" class="form-select rounded-0 border-dark border-2 p-3 fw-bold cursor-pointer">
                    <option value="A4" data-price="45.00">Format A4 (21 x 29,7 cm) - 45 €</option>
                    <option value="A3" data-price="60.00">Format A3 (29,7 x 42 cm) - 60 €</option>
                    <option value="A2" data-price="80.00">Format A2 (42 x 59,4 cm) - 80 €</option>
                    <option value="A1" data-price="120.00">Format A1 (59,4 x 84,1 cm) - 120 €</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="custom_text" class="form-label fw-bold text-uppercase">Votre personnalisation</label>
                <textarea name="custom_text" id="custom_text" rows="3" required 
                          class="form-control rounded-0 border-dark border-2 p-3"
                          placeholder="Décrivez votre idée : ville, date, prénoms, ajout d'un objet..."></textarea>
            </div>

            <div class="mb-4">
                <label for="quantite" class="form-label fw-bold text-uppercase">Quantité</label>
                <input type="number" name="quantite" id="quantite" value="1" min="1" max="100" 
                       class="form-control rounded-0 border-dark border-2 p-3">
            </div>

            <button type="submit" name="add_to_cart" 
                    class="btn btn-dark w-100 btn-lg rounded-0 fw-bold border-2 border-dark hard-shadow text-uppercase">
                Ajouter au panier
            </button>
        </form>
    </div>
</div>

<script>
    // On sélectionne le menu déroulant et le titre du prix
    const select = document.getElementById('tailleSelect');
    const priceDisplay = document.getElementById('displayPrice');

    // Dès qu'on change la sélection...
    select.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const price = selectedOption.getAttribute('data-price');
        priceDisplay.textContent = parseFloat(price).toFixed(2).replace('.', ',') + ' €';
    });
</script>

<?php include 'includes/footer.php'; ?>