<?php 
require_once 'includes/db.php';
include 'includes/header.php'; 

if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];

// Ajout simple
if (isset($_POST['add_to_cart'])) {
    $id = $_POST['id_item'];
    $qty = $_POST['quantite'];
    $text = $_POST['custom_text'];
    // Clé simple pour la V1
    $key = $id . '_' . md5($text);
    $_SESSION['cart'][$key] = ['id' => $id, 'qty' => $qty, 'text' => $text];
}

// Suppression
if (isset($_GET['delete'])) {
    unset($_SESSION['cart'][$_GET['delete']]);
    header('Location: cart.php'); exit;
}
?>

<h2>Votre Panier</h2>

<?php if (empty($_SESSION['cart'])): ?>
    <div class="alert alert-info">Votre panier est vide.</div>
<?php else: ?>
    <table class="table table-striped mt-4">
        <thead class="table-dark">
            <tr><th>Produit</th><th>Info</th><th>Prix</th><th>Qté</th><th>Total</th><th>Action</th></tr>
        </thead>
        <tbody>
            <?php 
            $total = 0;
            foreach ($_SESSION['cart'] as $key => $item): 
                $stmt = $pdo->prepare("SELECT * FROM items WHERE id = ?");
                $stmt->execute([$item['id']]);
                $prod = $stmt->fetch();
                $subtotal = $prod['prix'] * $item['qty'];
                $total += $subtotal;
            ?>
            <tr>
                <td><?= $prod['nom'] ?></td>
                <td><small><?= $item['text'] ?></small></td>
                <td><?= $prod['prix'] ?> €</td>
                <td><?= $item['qty'] ?></td>
                <td><?= $subtotal ?> €</td>
                <td><a href="cart.php?delete=<?= $key ?>" class="btn btn-sm btn-danger">X</a></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div class="text-end">
        <h3>Total: <?= $total ?> €</h3>
        <button class="btn btn-primary btn-lg">Passer commande</button>
    </div>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>