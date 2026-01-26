<?php 
require_once 'includes/db.php';
include 'includes/header.php'; 

// Initialisation du panier
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// AJOUT AU PANIER
if (isset($_POST['add_to_cart'])) {
    $id = (int)$_POST['id_item'];
    $qty = (int)$_POST['quantite'];
    $text = htmlspecialchars($_POST['custom_text']);

    // On génère un ID unique pour la ligne (Produit + Personnalisation)
    // Pas de taille ici, c'est simple.
    $line_id = $id . '_' . md5($text);

    // Si le produit est déjà là avec le même texte, on augmente la quantité
    if (isset($_SESSION['cart'][$line_id])) {
        $_SESSION['cart'][$line_id]['quantite'] += $qty;
    } else {
        $_SESSION['cart'][$line_id] = [
            'id_item' => $id,
            'quantite' => $qty,
            'personnalisation' => $text
        ];
    }
}

// SUPPRESSION
if (isset($_GET['delete'])) {
    unset($_SESSION['cart'][$_GET['delete']]);
    header('Location: cart.php'); exit();
}

// VIDER LE PANIER
if (isset($_GET['action']) && $_GET['action'] == 'empty') {
    unset($_SESSION['cart']);
    header('Location: cart.php'); exit();
}
?>

<h2 class="mb-4 border-bottom pb-2">Votre Panier</h2>

<?php if (empty($_SESSION['cart'])): ?>
    <div class="alert alert-info">Votre panier est vide. <a href="articles.php">Retourner au catalogue</a></div>
<?php else: ?>
    <div class="row">
        <div class="col-lg-8">
            <table class="table table-striped align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>Produit</th>
                        <th>Personnalisation</th>
                        <th>Prix U.</th>
                        <th>Qté</th>
                        <th>Total</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $totalGlobal = 0;
                    foreach ($_SESSION['cart'] as $line_id => $data): 
                        // On récupère les infos du produit en BDD
                        $stmt = $pdo->prepare("SELECT * FROM items WHERE id = ?");
                        $stmt->execute([$data['id_item']]);
                        $product = $stmt->fetch();

                        $totalLigne = $product['prix'] * $data['quantite'];
                        $totalGlobal += $totalLigne;
                    ?>
                    <tr>
                        <td>
                            <img src="/e-commerce/assets/uploads/<?= $product['image'] ?>" width="40" class="me-2 rounded">
                            <?= htmlspecialchars($product['nom']) ?>
                        </td>
                        <td><small class="text-muted"><?= $data['personnalisation'] ?: 'Aucune' ?></small></td>
                        <td><?= number_format($product['prix'], 2) ?> €</td>
                        <td><?= $data['quantite'] ?></td>
                        <td class="fw-bold"><?= number_format($totalLigne, 2) ?> €</td>
                        <td>
                            <a href="cart.php?delete=<?= $line_id ?>" class="btn btn-sm btn-danger">Supprimer</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="col-lg-4">
            <div class="card bg-light p-4 border-0">
                <h4 class="fw-bold">Résumé</h4>
                <hr>
                <div class="d-flex justify-content-between mb-4">
                    <span class="h5">Total à payer</span>
                    <span class="h5 fw-bold text-primary"><?= number_format($totalGlobal, 2) ?> €</span>
                </div>
                
                <?php if (isset($_SESSION['user'])): ?>
                    <a href="validate_order.php" class="btn btn-success w-100 btn-lg">Passer la commande</a>
                <?php else: ?>
                    <a href="login.php" class="btn btn-warning w-100">Se connecter pour commander</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>