<?php
session_start();
require '../config/connexion.php';

// Vérifier que l'admin est connecté
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

// Récupérer tous les messages
$messages = $pdo->query('SELECT * FROM messages_contact ORDER BY date_envoi DESC')->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Messages — Admin</title>
  <link rel="stylesheet" href="../style.css">
</head>
<body>

  <nav>
    <a href="index.php" class="logo">Admin<span>.</span></a>
    <ul>
      <li><a href="index.php">Dashboard</a></li>
      <li><a href="projets.php">Projets</a></li>
      <li><a href="messages.php" class="actif">Messages</a></li>
      <li><a href="logout.php">Déconnexion</a></li>
    </ul>
  </nav>

  <section class="page-titre">
    <h1>Messages reçus</h1>
    <p><?= count($messages) ?> message(s) au total.</p>
  </section>

  <section class="formulaire-section">
    <?php if (empty($messages)): ?>
      <p style="text-align:center; color:#aaa;">Aucun message pour l'instant.</p>
    <?php else: ?>
      <?php foreach ($messages as $msg): ?>
        <div class="message-card">
          <div class="message-header">
            <strong><?= htmlspecialchars($msg['nom']) ?></strong>
            <span><?= htmlspecialchars($msg['email']) ?></span>
            <span class="date"><?= $msg['date_envoi'] ?></span>
          </div>
          <p><?= htmlspecialchars($msg['message']) ?></p>
        </div>
      <?php endforeach; ?>
    <?php endif; ?>
  </section>

</body>
</html>
