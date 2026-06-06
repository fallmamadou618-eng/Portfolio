<?php
session_start();
require '../config/connexion.php';
require '../fonction.php';

$erreur = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email      = nettoyer($_POST['email']      ?? '');
    $mot_de_passe = nettoyer($_POST['mot_de_passe'] ?? '');

    // Chercher l'admin dans la base de données
    $stmt = $pdo->prepare('SELECT * FROM administrateurs WHERE email = :email');
    $stmt->execute([':email' => $email]);
    $admin = $stmt->fetch();

    // Vérifier le mot de passe
    if ($admin && password_verify($mot_de_passe, $admin['mot_de_passe'])) {
        session_regenerate_id(true);
        $_SESSION['admin_id']  = $admin['id'];
        $_SESSION['admin_nom'] = $admin['nom'];
        header('Location: index.php');
        exit;
    } else {
        $erreur = 'Email ou mot de passe incorrect.';
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Connexion Admin</title>
  <link rel="stylesheet" href="../style.css">
</head>
<body>

  <section class="page-titre">
    <h1>Espace Admin</h1>
    <p>Connecte-toi pour accéder au tableau de bord.</p>
  </section>

  <section class="formulaire-section">
    <h2>Connexion</h2>

    <?php if ($erreur): ?>
      <p class="erreur"><?= $erreur ?></p>
    <?php endif; ?>

    <form method="POST" action="login.php" class="formulaire">
      <label for="email">E-mail :</label>
      <input type="email" id="email" name="email" placeholder="votre@email.com" required>

      <label for="mot_de_passe">Mot de passe :</label>
      <input type="password" id="mot_de_passe" name="mot_de_passe" placeholder="••••••••" required>

      <button type="submit" class="bouton">Se connecter</button>
    </form>
  </section>

</body>
</html>
