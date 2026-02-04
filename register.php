<?php 
require_once 'includes/db.php';
include 'includes/header.php'; 

if (isset($_POST['register'])) {
    $nom = htmlspecialchars($_POST['nom']);
    $email = htmlspecialchars($_POST['email']);
    $mdp = $_POST['password'];
    $mdp_confirm = $_POST['confirm_password'];

    // Je vérifie les mots de passe
    if ($mdp !== $mdp_confirm) {
        $erreur = "Les mots de passe ne sont pas identiques.";
    } else {
        // Je vérifie si l'email existe déjà
        $verif = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $verif->execute(array($email));

        if ($verif->rowCount() > 0) {
            $erreur = "Cet email est déjà pris.";
        } else {
            // Tout est bon, je crypte et j'enregistre
            $hash = password_hash($mdp, PASSWORD_DEFAULT);
            $insert = $pdo->prepare("INSERT INTO users (nom, email, password, role) VALUES (?, ?, ?, 'client')");
            $insert->execute(array($nom, $email, $hash));

            echo "<div class='alert alert-success rounded-0 border-dark'>Compte créé ! <a href='login.php'>Connectez-vous</a>.</div>";
        }
    }
}
?>

<div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
        <div class="p-4 bg-white thick-border hard-shadow">
            <h2 class="text-center fw-bold mb-4 text-uppercase">Créer un compte</h2>
            
            <?php if(isset($erreur)) { echo "<div class='alert alert-danger rounded-0 border-dark'>$erreur</div>"; } ?>

            <form method="POST">
                <div class="mb-3">
                    <label class="form-label fw-bold">Nom complet</label>
                    <input type="text" name="nom" class="form-control rounded-0 border-dark border-2 p-2" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Email</label>
                    <input type="email" name="email" class="form-control rounded-0 border-dark border-2 p-2" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Mot de passe</label>
                    <input type="password" name="password" class="form-control rounded-0 border-dark border-2 p-2" required>
                </div>
                <div class="mb-4">
                    <label class="form-label fw-bold">Confirmer mot de passe</label>
                    <input type="password" name="confirm_password" class="form-control rounded-0 border-dark border-2 p-2" required>
                </div>
                
                <button type="submit" name="register" class="btn btn-dark w-100 rounded-0 fw-bold border-2 border-dark text-uppercase py-2">
                    M'inscrire
                </button>
            </form>
            
            <div class="text-center mt-3">
                <small>Déjà un compte ? <a href="login.php" class="text-dark fw-bold">Se connecter</a></small>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>