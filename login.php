<?php 
require_once 'includes/db.php';
include 'includes/header.php'; 

// Si on a cliqué sur le bouton login
if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $mdp = $_POST['password'];

    // Je cherche l'utilisateur dans la base
    $req = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $req->execute(array($email));
    $utilisateur = $req->fetch(PDO::FETCH_ASSOC);

    // Si utilisateur trouvé ET mot de passe bon
    if ($utilisateur && password_verify($mdp, $utilisateur['password'])) {
        // Je remplis la session
        $_SESSION['user'] = $utilisateur;
        // Redirection
        echo "<script>window.location='index.php';</script>";
        exit();
    } else {
        $erreur = "Email ou mot de passe incorrect.";
    }
}
?>

<div class="row justify-content-center">
    <div class="col-md-5 col-lg-4">
        <div class="p-4 bg-white thick-border hard-shadow">
            <h2 class="text-center fw-bold mb-4 text-uppercase">Connexion</h2>
            
            <?php if(isset($erreur)) { echo "<div class='alert alert-danger rounded-0 border-dark'>$erreur</div>"; } ?>

            <form method="POST">
                <div class="mb-3">
                    <label class="form-label fw-bold">Email</label>
                    <input type="email" name="email" class="form-control rounded-0 border-dark border-2 p-2" required>
                </div>
                
                <div class="mb-4">
                    <label class="form-label fw-bold">Mot de passe</label>
                    <input type="password" name="password" class="form-control rounded-0 border-dark border-2 p-2" required>
                </div>
                
                <button type="submit" name="login" class="btn btn-dark w-100 rounded-0 fw-bold border-2 border-dark text-uppercase py-2">
                    Se connecter
                </button>
            </form>
            
            <div class="text-center mt-3">
                <small>Pas encore de compte ? <a href="register.php" class="text-dark fw-bold">S'inscrire</a></small>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>