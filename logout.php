<?php
session_start();
// Je vide la session et je la détruis
session_unset();
session_destroy();
// Retour accueil
header('Location: index.php');
exit();
?>