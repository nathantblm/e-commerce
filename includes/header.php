<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon E-Commerce V2</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex flex-column min-vh-100">

<nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4">
    <div class="container">
        <a class="navbar-brand fw-bold" href="/e-commerce/index.php">Ma Boutique</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarText">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarText">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item"><a class="nav-link" href="/e-commerce/index.php">Accueil</a></li>
                <li class="nav-item"><a class="nav-link" href="/e-commerce/articles.php">Catalogue</a></li>
            </ul>
            <ul class="navbar-nav">
                <?php if (isset($_SESSION['user'])): ?>
                    
                    <?php if ($_SESSION['user']['role'] === 'admin'): ?>
                        <li class="nav-item">
                            <a class="nav-link fw-bold text-warning" href="/e-commerce/admin/index.php">⚙️ Admin</a>
                        </li>
                    <?php endif; ?>
                    
                    <li class="nav-item"><a class="nav-link" href="/e-commerce/cart.php">🛒 Panier</a></li>
                    <li class="nav-item"><a class="nav-link" href="/e-commerce/logout.php">Déconnexion</a></li>
                <?php else: ?>
                    <li class="nav-item"><a class="nav-link" href="/e-commerce/login.php">Connexion</a></li>
                    <li class="nav-item"><a class="nav-link" href="/e-commerce/register.php">Inscription</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>
<div class="container flex-grow-1">