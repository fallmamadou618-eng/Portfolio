<?php
require 'fonction.php';

// Tableau de projets — sera remplacé par la base de données plus tard
$projets = [
    [
        'titre'        => 'Boutique en ligne',
        'description'  => 'Création d\'une boutique en ligne pour artisans sénégalais.',
        'technologies' => ['HTML', 'CSS', 'PHP', 'MySQL'],
    ],
    [
        'titre'        => 'Configuration réseau',
        'description'  => 'Mise en place et configuration d\'un réseau informatique local.',
        'technologies' => ['Réseau', 'Infrastructure'],
    ],
    [
        'titre'        => 'Analyse de matchs',
        'description'  => 'Application web permettant d\'analyser les statistiques de matchs de football.',
        'technologies' => ['HTML', 'CSS', 'JavaScript', 'Python'],
    ],
];

// Filtrage par mot-clé
$mot_cle   = nettoyer($_GET['q'] ?? '');
$resultats = [];

if ($mot_cle !== '') {
    foreach ($projets as $projet) {
        $titre       = strtolower($projet['titre']);
        $description = strtolower($projet['description']);
        $recherche   = strtolower($mot_cle);

        if (strpos($titre, $recherche) !== false ||
            strpos($description, $recherche) !== false) {
            $resultats[] = $projet;
        }
    }
} else {
    $resultats = $projets;
}
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
        <div class="technologies">
          <?php foreach ($projet['technologies'] as $tech) : ?>
            <span class="projet-tags"><?= htmlspecialchars($tech) ?></span>
          <?php endforeach; ?>
        </div>
        <a href="#" class="bouton">Voir</a>
      </article>
    <?php endforeach; ?>

    <?php if (empty($resultats)) : ?>
      <p style="text-align:center; color:#aaa; padding:2rem;">
        Aucun projet ne correspond à ta recherche.
      </p>
    <?php endif; ?>

  </section>

<?php require 'composants/pied-de-page.php'; ?>

</body>
</html>