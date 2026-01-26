<?php
session_start();
session_unset();
session_destroy();
header('Location: index.php'); // Retour à l'accueil après déconnexion
exit();
?>