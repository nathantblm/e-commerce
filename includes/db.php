<?php
// Mes identifiants pour la base de données
$serveur = "localhost";
$nom_base = "ecommerce_ynov";
$utilisateur = "root";
$motdepasse = ""; // Vide sur wamp

try {
    // Je me connecte
    $pdo = new PDO("mysql:host=$serveur;dbname=$nom_base;charset=utf8", $utilisateur, $motdepasse);
    
    // Je veux voir les erreurs SQL s'il y en a (utile pour débugger)
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (Exception $e) {
    // Si ça marche pas, j'arrête tout et j'affiche le message
    die("Erreur de connexion : " . $e->getMessage());
}
?>