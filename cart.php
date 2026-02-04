<?php 
require_once 'includes/db.php';
include 'includes/header.php'; 

// Si le panier n'existe pas, je le crée
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Ajout au panier
if (isset($_POST['add_to_cart'])) {
    $id = intval($_POST['id_item']);
    $qte = intval($_POST['quantite']);
    $texte = htmlspecialchars($_POST['custom_text']);
    $taille = $_POST['taille']; // J'ai ajouté la taille ici si elle est postée

    // Je crée une clé unique pour différencier les articles
    $ligne_id = $id . '_' . md5($texte) . '_' . $taille;
    
    $_SESSION['cart'][$ligne_id] = [
        'id_item' => $id, 
        'quantite' => $qte, 
        'personnalisation' => $texte,
        'taille' => $taille
    ];
}

// Supprimer une ligne
if (isset($_GET['delete'])) {
    unset($_SESSION['cart'][$_GET['delete']]);
    echo "<script>window.location='cart.php';</script>";
}

// Vider tout
if (isset($_GET['action']) && $_GET['action'] == 'empty') {
    unset($_SESSION['cart']);
    echo "<script>window.location='cart.php';</script>";
}
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="fw-bold">VOTRE PANIER</h1>
    <?php if (!empty($_SESSION['cart'])) { ?>
        <a href="cart.php?action=empty" class="btn btn-outline-danger btn-sm rounded-0">VIDER LE PANIER</a>
    <?php } ?>
</div>

<?php if (empty($_SESSION['cart'])) { ?>
    <div class="alert alert-info border-dark rounded-0">Votre panier est vide. <a href="articles.php">Retour au catalogue</a></div>
<?php } else { ?>
    <div class="row">
        <div class="col-md-8">
            <div class="table-responsive">
                <table class="table table-bordered border-dark">
                    <thead>
                        <tr>
                            <th>Format</th>
                            <th>Infos</th>
                            <th>Prix U.</th>
                            <th>Qté</th>
                            <th>Total</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $totalGlobal = 0;
                        foreach ($_SESSION['cart'] as $id_ligne => $data) {
                            $req = $pdo->prepare("SELECT nom FROM items WHERE id = ?");
                            $req->execute(array($data['id_item']));
                            $produit = $req->fetch();

                            // Je recalcule le prix selon la taille (PHP)
                            $prix = 45;
                            if($data['taille'] == 'A3') $prix = 60;
                            if($data['taille'] == 'A2') $prix = 80;
                            if($data['taille'] == 'A1') $prix = 120;

                            $totalLigne = $prix * $data['quantite'];
                            $totalGlobal += $totalLigne;
                        ?>
                        <tr>
                            <td class="fw-bold"><?= $produit['nom'] ?></td>
                            <td>
                                Taille : <?= $data['taille'] ?><br>
                                <em><?= $data['personnalisation'] ?></em>
                            </td>
                            <td><?= number_format($prix, 2) ?> €</td>
                            <td><?= $data['quantite'] ?></td>
                            <td><?= number_format($totalLigne, 2) ?> €</td>
                            <td><a href="cart.php?delete=<?= $id_ligne ?>" class="btn btn-danger btn-sm">X</a></td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
            <h3 class="fw-bold text-end mt-3">TOTAL : <?= number_format($totalGlobal, 2) ?> €</h3>
        </div>

        <div class="col-md-4">
            <div class="card p-3 bg-light rounded-0 border-2 border-dark">
                <h4 class="fw-bold mb-3">FACTURATION</h4>
                <form action="validate_order.php" method="POST">
                    <div class="mb-3">
                        <label>Adresse complète</label>
                        <textarea name="adresse" class="form-control rounded-0 border-dark" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label>Ville</label>
                        <input type="text" name="ville" class="form-control rounded-0 border-dark" required>
                    </div>
                    <div class="mb-3">
                        <label>Code Postal</label>
                        <input type="text" name="code_postal" class="form-control rounded-0 border-dark" required>
                    </div>
                    <input type="hidden" name="total_amount" value="<?= $totalGlobal ?>">
                    
                    <?php if (isset($_SESSION['user'])) { ?>
                        <button type="submit" class="btn btn-dark w-100 btn-lg rounded-0 fw-bold border-2 border-dark">PAYER & VALIDER</button>
                    <?php } else { ?>
                        <a href="login.php" class="btn btn-warning w-100 border-dark rounded-0 fw-bold">CONNECTEZ-VOUS POUR PAYER</a>
                    <?php } ?>
                </form>
            </div>
        </div>
    </div>
<?php } ?>

<?php include 'includes/footer.php'; ?>