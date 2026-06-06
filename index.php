<?php
require 'fonction.php';
require 'config/connexion.php';

// Enregistrer la visite
$stmt = $pdo->prepare('INSERT INTO visites (page, ip) VALUES (:page, :ip)');
$stmt->execute([
    ':page' => basename($_SERVER['PHP_SELF']),
    ':ip'   => $_SERVER['REMOTE_ADDR'],
]);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Mon Portfolio</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>

  <!-- NAVIGATION -->
 <?php require 'composants/navigateur.php'; ?>

  <!-- SECTION HÉRO -->
  <section class="hero">
    <h1>Bonjour, je suis <span>Mamadou Diop</span></h1>
    <p>Étudiant développeur web passionné par le code et la création de sites.</p>
    <a href="projets.php" class="bouton">Voir mes projets</a>
  </section>

  <!-- À PROPOS -->
  <section class="a-propos">
    <h2>À propos de moi</h2>
    <p>
      Je m'appelle Mamadou Diop, je suis en formation développement web.
      Ce qui me passionne dans le code, c'est de voir une idée devenir un vrai site web.
    </p>

    <h3>Mes compétences</h3>
    <ul class="competences">
      <li>HTML5</li>
      <li>CSS3</li>
      <li>JavaScript</li>
      <li>PHP</li>
      <li>MySQL</li>
      <li>Git / GitHub</li>
    </ul>
  </section>

  <!-- EXPÉRIENCES -->
  <section class="experiences">
    <h2>Mon parcours</h2>

    <div class="experience-item">
      <span class="date">2024 — Aujourd'hui</span>
      <h3>Formation développement web</h3>
      <p>Apprentissage du PHP, MySQL et création de projets complets.</p>
    </div>

    <div class="experience-item">
      <span class="date">2023</span>
      <h3>Initiation HTML / CSS</h3>
      <p>Premiers sites statiques et découverte du responsive design.</p>
    </div>

    <div class="experience-item">
      <span class="date">2022</span>
      <h3>Bénévole — Association TechForAll</h3>
      <p>Participation à la création du site web de l'association.</p>
    </div>

  </section>

  <!-- FOOTER -->
 <?php require 'composants/pied-de-page.php'; ?>

</body>
</html>
