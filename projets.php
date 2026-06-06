<?php
require 'fonction.php';
require 'config/connexion.php';
// Enregistrer la visite
$stmt = $pdo->prepare('INSERT INTO visites (page, ip) VALUES (:page, :ip)');
$stmt->execute([
    ':page' => basename($_SERVER['PHP_SELF']),
    ':ip'   => $_SERVER['REMOTE_ADDR'],
]);
// Récupérer le mot-clé de recherche
$mot_cle   = nettoyer($_GET['q'] ?? '');
$resultats = [];

// Lire les projets depuis MySQL
if ($mot_cle !== '') {
    $stmt = $pdo->prepare(
        'SELECT * FROM projets
         WHERE titre LIKE :mot1 
         OR description LIKE :mot2 
         OR technologies LIKE :mot3'
    );
    $stmt->execute([
        ':mot1' => '%' . $mot_cle . '%',
        ':mot2' => '%' . $mot_cle . '%',
        ':mot3' => '%' . $mot_cle . '%',
    ]);
} else {
    $stmt = $pdo->query('SELECT * FROM projets');
}

$resultats = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Mes Projets</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>

<?php require 'composants/navigateur.php'; ?>

  <!-- TITRE -->
  <section class="page-titre">
    <h1>Mes Projets</h1>
    <p>Voici les projets réalisés pendant ma formation.</p>
  </section>

  <!-- FORMULAIRE DE RECHERCHE -->
  <section class="recherche">
    <h2>Rechercher un projet</h2>
    <form method="GET" action="projets.php">
      <input
        type="text"
        name="q"
        value="<?= $mot_cle ?>"
        placeholder="Tapez un mot-clé : PHP, réseau...">
      <button type="submit">Rechercher</button>
    </form>
  </section>

  <!-- GRILLE DE PROJETS -->
  <section class="projets">
<?php foreach ($resultats as $projet) : ?>
  <article class="carte-projet">
    <h3><?= htmlspecialchars($projet['titre']) ?></h3>
    <p><?= htmlspecialchars($projet['description']) ?></p>
    <p class="technologies"><?= htmlspecialchars($projet['technologies']) ?></p>
    <a href="#" class="bouton">Voir</a>
  </article>
<?php endforeach; ?>

<?php if (empty($resultats)) : ?>
  <p style="text-align:center; color:#aaa; padding:2rem;">
    Aucun projet trouvé pour "<?= htmlspecialchars($mot_cle) ?>".
  </p>
<?php endif; ?>
    <?php if (empty($resultats)) : ?>
      <p style="text-align:center; color:#aaa; padding:2rem;">
        Aucun projet ne correspond à ta recherche.
      </p>
    <?php endif; ?>

  </section>

<?php require 'composants/pied-de-page.php'; ?>

</body>
</html>
