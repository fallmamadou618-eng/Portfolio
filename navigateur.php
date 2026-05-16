<?php
// Récupérer le nom du fichier actuellement chargé
$page_courante = basename($_SERVER['PHP_SELF']);
?>

<nav>
  <a href="index.php" class="logo">Mamadou<span>.</span></a>
  <ul>
    <li>
      <a href="index.php" <?php if($page_courante == 'index.php') echo 'class="actif"'; ?>>Accueil</a>
    </li>
    <li>
      <a href="projets.php" <?php if($page_courante == 'projets.php') echo 'class="actif"'; ?>>Projets</a>
    </li>
    <li>
      <a href="contact.php" <?php if($page_courante == 'contact.php') echo 'class="actif"'; ?>>Contact</a>
    </li>
  </ul>
</nav>