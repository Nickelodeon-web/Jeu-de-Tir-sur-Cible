<?php
session_start();
include_once 'dbconnect.php';

// Redirect to game if already logged in
if (isset($_SESSION['pseudo'])) {
    header('Location: game.php');
    exit;
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validation côté serveur
    $pseudo = trim($_POST['pseudo'] ?? '');
    $pass = $_POST['pass'] ?? '';
    $email = trim($_POST['email'] ?? '');
    $dob = $_POST['dob'] ?? '';
    $region_id = $_POST['region_id'] ?? '';
    
    if(empty($pseudo)) $errors['pseudo'] = 'Le pseudo est obligatoire.';
    if(empty($pass)) $errors['pass'] = 'Le mot de passe est obligatoire.';
    if(empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors['email'] = 'Un email valide est obligatoire.';
    if(empty($dob)) $errors['dob'] = 'La date de naissance est obligatoire.';
    if(empty($region_id)) $errors['region_id'] = 'La région est obligatoire.';
    
    if(empty($errors)) {
        try {
            // Vérifier si le pseudo existe déjà
            $stmt_check = $pdo->prepare("SELECT pseudo FROM players WHERE pseudo = ?");
            $stmt_check->execute([$pseudo]);
            if ($stmt_check->fetch()) {
                $errors['pseudo'] = 'Ce pseudo est déjà utilisé.';
            } else {
                // Hachage du mot de passe
                $hash = password_hash($pass, PASSWORD_DEFAULT);
                
                // Insertion sécurisée via requête préparée
                $stmt = $pdo->prepare('INSERT INTO players (pseudo, password, email, birthdate, registration_date, region_id) VALUES (?, ?, ?, ?, NOW(), ?)');
                
                if ($stmt->execute([$pseudo, $hash, $email, $dob, $region_id])) {
                    // Connexion automatique après inscription
                    $_SESSION['pseudo'] = $pseudo;
                    header('Location: game.php');
                    exit;
                } else {
                    $errors['global'] = 'Une erreur est survenue lors de l\'inscription.';
                }
            }
        } catch (PDOException $e) {
            $errors['global'] = 'Erreur de base de données: Impossible de finaliser l\'inscription.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Projet Cibles - Inscription</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="login-form">
        <h2>Créer un compte</h2>
        
        <?php if(isset($errors['global'])): ?>
            <div class="alert-error"><?= htmlspecialchars($errors['global']) ?></div>
        <?php endif; ?>
        
        <form method="POST" action="register.php">
            <!-- Pseudo (Sticky) -->
            <input type="text" name="pseudo" placeholder="Pseudo" value="<?= htmlspecialchars($_POST['pseudo'] ?? '') ?>">
            <?php if(isset($errors['pseudo'])) echo "<div class='error'>{$errors['pseudo']}</div>"; ?>
            
            <!-- Mot de passe (Not sticky for security) -->
            <input type="password" name="pass" placeholder="Mot de passe">
            <?php if(isset($errors['pass'])) echo "<div class='error'>{$errors['pass']}</div>"; ?>
            
            <!-- Email (Sticky) -->
            <input type="email" name="email" placeholder="Adresse Email" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
            <?php if(isset($errors['email'])) echo "<div class='error'>{$errors['email']}</div>"; ?>
            
            <!-- Date de naissance (Sticky) -->
            <input type="date" name="dob" value="<?= htmlspecialchars($_POST['dob'] ?? '') ?>">
            <?php if(isset($errors['dob'])) echo "<div class='error'>{$errors['dob']}</div>"; ?>
            
            <!-- Région dynamique -->
            <select name="region_id">
                <option value="">-- Sélectionnez votre région --</option>
                <?php
                try {
                    $regs = $pdo->query("SELECT * FROM regions ORDER BY name")->fetchAll(PDO::FETCH_ASSOC);
                    foreach($regs as $r) {
                        $sel = (isset($_POST['region_id']) && $_POST['region_id'] == $r['id']) ? "selected" : "";
                        echo "<option value=\"{$r['id']}\" $sel>" . htmlspecialchars($r['name']) . "</option>";
                    }
                } catch (PDOException $e) {
                    echo "<option value=\"\">Erreur de chargement des régions</option>";
                }
                ?>
            </select>
            <?php if(isset($errors['region_id'])) echo "<div class='error'>{$errors['region_id']}</div>"; ?>
            
            <button type="submit" style="margin-top: 15px;">M'inscrire</button>
            <a href="index.php">Retour à la connexion</a>
        </form>
    </div>
</body>
</html>
