<?php
session_start();

// Protection de la page : l'utilisateur doit être connecté
if (!isset($_SESSION['pseudo'])) {
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Projet Cibles - Le Jeu</title>
    <link rel="stylesheet" href="css/style.css">
    <script src="js/game.js" defer></script>
</head>
<body>
    <div class="logout-container">
        <a href="logout.php" class="logout-btn">Se déconnecter</a>
    </div>

    <!-- Message de bienvenue (Étape 8+) -->
    <h2 style="text-align:center; color:white; margin-top:20px; font-size: 2rem; text-shadow: 2px 2px 4px rgba(0,0,0,0.5);">
        BONJOUR <?= htmlspecialchars(strtoupper($_SESSION['pseudo'])) ?>
    </h2>

    <div class="target-container" id="cible">
        <div class="ring ring-1"></div>
        <div class="ring ring-2"></div>
        <div class="ring ring-3"></div>
        <div class="ring ring-4"></div>
        <div class="ring ring-5"></div>
        <div class="ring ring-6"></div>
        <div class="ring ring-7"></div>
        <div class="ring ring-8"></div>
        <div class="ring ring-9"></div>
        <div class="ring ring-10"></div>
    </div>

    <div class="game-area">
        <!-- Informations de score (Étape 6+) -->
        <div class="stats" id="stats">Tir n°: <span id="num">1</span> | Score: <span id="score">0</span></div>

        <!-- Boussole et vent (Étape 5+) -->
        <div class="compass-container">
            <img src="images/compass.svg" class="compass" alt="Boussole">
            <img src="images/pointer.svg" class="pointer" id="pointer" alt="Aiguille">
        </div>

        <!-- Projectile et arc -->
        <img src="images/arrow.svg" class="projectile" id="arrow" alt="Flèche">
        <img src="images/bow.svg" class="weapon" id="bow" alt="Arc" style="margin-left: 0px;">

        <!-- Contrôles de déplacement (Étape 5+) -->
        <div class="controls" id="controls">
            <button id="btn-ll">&lt;&lt; (-20px)</button>
            <button id="btn-l">&lt; (-5px)</button>
            <button id="btn-r">&gt; (+5px)</button>
            <button id="btn-rr">&gt;&gt; (+20px)</button>
        </div>
        
        <br>
        
        <!-- Bouton de Tir -->
        <button class="btn-fire" id="btn-fire" disabled>FIRE !!!</button>
        <button class="btn-fire hidden" id="btn-next" style="background:#4cc9f0;">TIR SUIVANT</button>
    </div>
</body>
</html>
