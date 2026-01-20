<?php 
require_once 'includes/db.php';
include 'includes/header.php'; 

// Traitement du formulaire
if (isset($_POST['register'])) {
    // 1. Sécurisation des entrées
    $nom = htmlspecialchars($_POST['nom']);
    $email = htmlspecialchars($_POST['email']);
    $password = $_POST['password'];

    // 2. Vérifier si l'email existe déjà
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);

    if ($stmt->rowCount() > 0) {
        $error = "Cet email est déjà utilisé par un autre compte.";
    } else {
        // 3. Hachage du mot de passe (Sécurité indispensable)
        $hash = password_hash($password, PASSWORD_DEFAULT);

        // 4. Insertion dans la base de données (Rôle 'client' par défaut)
        $insert = $pdo->prepare("INSERT INTO users (nom, email, password, role) VALUES (?, ?, ?, 'client')");
        
        if ($insert->execute([$nom, $email, $hash])) {
            // Redirection vers la page de connexion avec un message de succès (via JS simple)
            echo "<script>alert('Compte créé avec succès ! Vous pouvez vous connecter.'); window.location='login.php';</script>";
            exit();
        } else {
            $error = "Une erreur est survenue lors de l'inscription.";
        }
    }
}
?>

<div class="row justify-content-center mt-5">
    <div class="col-md-5">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white text-center py-3">
                <h3 class="mb-0">Créer un compte</h3>
            </div>
            <div class="card-body p-4">
                
                <?php if (isset($error)): ?>
                    <div class="alert alert-danger"><?= $error ?></div>
                <?php endif; ?>

                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label">Nom complet</label>
                        <input type="text" name="nom" class="form-control" placeholder="Ex: Jean Dupont" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Adresse Email</label>
                        <input type="email" name="email" class="form-control" placeholder="Ex: jean@mail.com" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Mot de passe</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" name="register" class="btn btn-primary btn-lg">S'inscrire</button>
                    </div>
                </form>

                <div class="text-center mt-3">
                    <p>Déjà un compte ? <a href="login.php" class="text-primary fw-bold">Se connecter</a></p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>