<?php
// Je démarre la session si elle n'est pas déjà lancée
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Je vérifie si je suis dans le dossier "admin" pour gérer les liens (retour en arrière)
$chemin = "";
// Si l'url contient "/admin/", alors je dois remonter d'un dossier
if (strpos($_SERVER['PHP_SELF'], '/admin/') !== false) {
    $chemin = "../";
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Affiche Perso</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo $chemin; ?>assets/css/style.css">
</head>
<body class="d-flex flex-column min-vh-100 bg-white text-dark">

<header class="border-bottom border-3 border-dark py-3">
    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container">
            <a class="navbar-brand fw-bold text-uppercase fs-3" href="<?php echo $chemin; ?>index.php">🎨 Mon Affiche Perso</a>
            
            <button class="navbar-toggler border-2 border-dark rounded-0" type="button" data-bs-toggle="collapse" data-bs-target="#monMenu">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse justify-content-end" id="monMenu">
                <ul class="navbar-nav align-items-center fw-bold text-uppercase small">
                    <li class="nav-item"><a class="nav-link text-dark mx-2" href="<?php echo $chemin; ?>index.php">Accueil</a></li>
                    <li class="nav-item"><a class="nav-link text-dark mx-2" href="<?php echo $chemin; ?>about.php">Qui sommes-nous ?</a></li>
                    <li class="nav-item"><a class="nav-link text-dark mx-2" href="<?php echo $chemin; ?>articles.php">Catalogue</a></li>
                    
                    <?php 
                    // Si l'utilisateur est connecté
                    if (isset($_SESSION['user'])) { 
                    ?>
                        <li class="nav-item"><a class="nav-link text-dark mx-2" href="<?php echo $chemin; ?>cart.php">Panier</a></li>
                        
                        <?php 
                        // Si c'est l'admin, j'affiche le lien rouge
                        if ($_SESSION['user']['role'] == 'admin') { 
                        ?>
                            <li class="nav-item"><a class="nav-link text-danger mx-2" href="<?php echo $chemin; ?>admin/index.php">ADMIN</a></li>
                        <?php } ?>

                        <li class="nav-item ms-3">
                            <a href="<?php echo $chemin; ?>logout.php" class="btn btn-outline-dark btn-sm rounded-0 fw-bold border-2">Déconnexion</a>
                        </li>

                    <?php } else { ?>
                        <li class="nav-item"><a class="nav-link text-dark mx-2" href="<?php echo $chemin; ?>login.php">Connexion</a></li>
                        <li class="nav-item ms-2">
                            <a href="<?php echo $chemin; ?>register.php" class="btn btn-dark rounded-0 fw-bold border-2 border-dark hard-shadow">INSCRIPTION</a>
                        </li>
                    <?php } ?>
                </ul>
            </div>
        </div>
    </nav>
</header>
<main class="container my-5 flex-grow-1">