<?php
session_start();
require '../config/connexion.php';
require '../fonction.php';

// Vérifier que l'admin est connecté
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

$succes = '';
$erreur = '';

// Supprimer un projet
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['supprimer'])) {
    $id = (int) $_POST['id'];
    $pdo->prepare('DELETE FROM projets WHERE id = :id')->execute([':id' => $id]);
    $succes = 'Projet supprimé avec succès.';
}

// Ajouter un projet
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ajouter'])) {
    $titre        = nettoyer($_POST['titre']        ?? '');
    $description  = nettoyer($_POST['description']  ?? '');
    $technologies = nettoyer($_POST['technologies'] ?? '');

    if (!champ_requis($titre) || !champ_requis($description)) {
        $erreur = 'Le titre et la description sont obligatoires.';
    } else {
        $stmt = $pdo->prepare(
            'INSERT INTO projets (titre, description, technologies)
             VALUES (:titre, :description, :technologies)'
        );
        $stmt->execute([
            ':titre'        => $titre,
            ':description'  => $description,
            ':technologies' => $technologies,
        ]);
        $succes = 'Projet ajouté avec succès.';
    }
}

// Récupérer tous les projets
$projets = $pdo->query('SELECT * FROM projets ORDER BY date_creation DESC')->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Projets — Admin</title>
  <link rel="stylesheet" href="../style.css">
</head>
<body>

  <nav>
    <a href="index.php" class="logo">Admin<span>.</span></a>
    <ul>
      <li><a href="index.php">Dashboard</a></li>
      <li><a href="projets.php" class="actif">Projets</a></li>
      <li><a href="messages.php">Messages</a></li>
      <li><a href="logout.php">Déconnexion</a></li>
    </ul>
  </nav>

  <section class="page-titre">
    <h1>Gérer les projets</h1>
  </section>

  <section class="formulaire-section">

    <?php if ($succes): ?>
      <p class="succes"><?= $succes ?></p>
    <?php endif; ?>

    <?php if ($erreur): ?>
      <p class="erreur"><?= $erreur ?></p>
    <?php endif; ?>

    <!-- FORMULAIRE AJOUTER UN PROJET -->
    <h2>Ajouter un projet</h2>
    <form method="POST" action="projets.php" class="formulaire">
      <label for="titre">Titre :</label>
      <input type="text" id="titre" name="titre" placeholder="Titre du projet" required>

      <label for="description">Description :</label>
      <textarea id="description" name="description" rows="3" placeholder="Description..." required></textarea>

      <label for="technologies">Technologies :</label>
      <input type="text" id="technologies" name="technologies" placeholder="HTML, CSS, PHP...">

      <button type="submit" name="ajouter" class="bouton">Ajouter</button>
    </form>

    <!-- LISTE DES PROJETS -->
    <h2 style="margin-top:3rem;">Liste des projets</h2>
    <?php if (empty($projets)): ?>
      <p style="color:#aaa;">Aucun projet pour l'instant.</p>
    <?php else: ?>
      <?php foreach ($projets as $projet): ?>
        <div class="message-card">
          <div class="message-header">
            <strong><?= htmlspecialchars($projet['titre']) ?></strong>
            <span><?= htmlspecialchars($projet['technologies']) ?></span>
          </div>
          <p><?= htmlspecialchars($projet['description']) ?></p>
          <form method="POST" action="projets.php" style="margin-top:0.8rem;">
            <input type="hidden" name="id" value="<?= $projet['id'] ?>">
            <button type="submit" name="supprimer" class="bouton"
              onclick="return confirm('Supprimer ce projet ?')">
              Supprimer
            </button>
          </form>
        </div>
      <?php endforeach; ?>
    <?php endif; ?>

  </section>

</body>
</html>
