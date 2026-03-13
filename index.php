<?php
session_start();

// Redirect to game if already logged in
if (isset($_SESSION['pseudo'])) {
    header('Location: game.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    include_once 'dbconnect.php';
    
    $pseudo = trim($_POST['pseudo'] ?? '');
    $pass = $_POST['pass'] ?? '';
    
    if (empty($pseudo) || empty($pass)) {
        $error = "Veuillez remplir tous les champs.";
    } else {
        try {
            // Requête préparée pour éviter les injections SQL
            $stmt = $pdo->prepare('SELECT * FROM players WHERE pseudo = ?');
            $stmt->execute([$pseudo]);
            $user = $stmt->fetch();
            
            // Vérification du mot de passe avec password_verify
            if ($user && password_verify($pass, $user['password'])) {
                $_SESSION['pseudo'] = $user['pseudo'];
                header('Location: game.php');
                exit;
            } else {
                $error = "CONNEXION IMPOSSIBLE";
            }
        } catch (PDOException $e) {
            $error = "Erreur de base de données.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Projet Cibles - Connexion</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="login-form">
        <h2>Connexion au jeu</h2>
        <p>Veuillez vous identifier pour jouer.</p>
        
        <?php if(!empty($error)): ?>
            <div class="alert-error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        
        <form method="POST" action="index.php">
            <input type="text" name="pseudo" placeholder="Votre Pseudo" value="<?= htmlspecialchars($_POST['pseudo'] ?? '') ?>" required>
            <input type="password" name="pass" placeholder="Votre Mot de passe" required>
            <button type="submit">Valider</button>
            <a href="register.php">S'inscrire (Nouveau joueur)</a>
        </form>
    </div>
</body>
</html>
