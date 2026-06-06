<?php
session_start();
require '../config/connexion.php';

// Vérifier que l'admin est connecté
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

// Compter les données
$nb_projets  = $pdo->query('SELECT COUNT(*) FROM projets')->fetchColumn();
$nb_messages = $pdo->query('SELECT COUNT(*) FROM messages_contact')->fetchColumn();
$nb_demandes = $pdo->query('SELECT COUNT(*) FROM demandes_projet')->fetchColumn();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard Admin</title>
  <link rel="stylesheet" href="../style.css">
</head>
<body>

  <!-- NAVIGATION ADMIN -->
  <nav>
    <a href="index.php" class="logo">Admin<span>.</span></a>
    <ul>
      <li><a href="index.php">Dashboard</a></li>
      <li><a href="projets.php">Projets</a></li>
      <li><a href="messages.php">Messages</a></li>
      <li><a href="logout.php">Déconnexion</a></li>
    </ul>
  </nav>

  <section class="page-titre">
    <h1>Bonjour <?= $_SESSION['admin_nom'] ?> !</h1>
    <p>Bienvenue sur ton tableau de bord.</p>
  </section>

  <!-- STATISTIQUES -->
  <section class="stats-admin">
    <div class="container">
      <div class="stats-grid">
        <div class="stat-card">
          <span class="stat-number"><?= $nb_projets ?></span>
          <span class="stat-label">Projets</span>
        </div>
        <div class="stat-card">
          <span class="stat-number"><?= $nb_messages ?></span>
          <span class="stat-label">Messages reçus</span>
        </div>
        <div class="stat-card">
          <span class="stat-number"><?= $nb_demandes ?></span>
          <span class="stat-label">Demandes de projet</span>
        </div>
      </div>
    </div>
  </section>

  <!-- LIENS RAPIDES -->
  <section class="liens-admin">
    <div class="container">
      <h2>Actions rapides</h2>
      <div class="actions-grid">
        <a href="projets.php" class="bouton">Gérer les projets</a>
        <a href="messages.php" class="bouton">Voir les messages</a>
      </div>
    </div>
  </section>

</body>
</html>
