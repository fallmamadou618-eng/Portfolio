<?php
session_start();
require 'fonction.php';
 require 'config/connexion.php'; 
// Enregistrer la visite
$stmt = $pdo->prepare('INSERT INTO visites (page, ip) VALUES (:page, :ip)');
$stmt->execute([
    ':page' => basename($_SERVER['PHP_SELF']),
    ':ip'   => $_SERVER['REMOTE_ADDR'],
]);
// Initialisation des variables
$erreurs = [];
$succes  = false;
$nom     = '';
$email   = '';
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom     = nettoyer($_POST['nom']     ?? '');
    $email   = nettoyer($_POST['email']   ?? '');
    $message = nettoyer($_POST['message'] ?? '');

    if (!champ_requis($nom))
        $erreurs[] = 'Le nom est obligatoire.';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL))
        $erreurs[] = "L'adresse e-mail est invalide.";
    if (!champ_requis($message))
        $erreurs[] = 'Le message ne peut pas être vide.';

    if (empty($erreurs)) {
    $stmt = $pdo->prepare(
        'INSERT INTO messages_contact (nom, email, message)
         VALUES (:nom, :email, :message)'
    );
    $stmt->execute([
        ':nom'     => $nom,
        ':email'   => $email,
        ':message' => $message,
    ]);
    $succes = true;
}
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Contact</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>

<?php require 'composants/navigateur.php'; ?>

  <!-- TITRE -->
  <section class="page-titre">
    <h1>Me contacter</h1>
    <p>Un projet ? Une question ? Écris-moi !</p>
  </section>

  <!-- FORMULAIRE CONTACT -->
  <section class="formulaire-section">
    <h2>Envoyer un message</h2>

    <?php if ($succes): ?>
      <p class="succes">Merci <?= $nom ?> ! Ton message a bien été envoyé.</p>
    <?php endif; ?>

    <?php if (!empty($erreurs)): ?>
      <ul class="erreur">
        <?php foreach ($erreurs as $erreur): ?>
          <li><?= $erreur ?></li>
        <?php endforeach; ?>
      </ul>
    <?php endif; ?>

    <form method="POST" action="contact.php" class="formulaire">

      <label for="nom">Nom :</label>
      <input type="text" id="nom" name="nom"
        value="<?= $nom ?>"
        placeholder="Votre nom" required>

      <label for="email">E-mail :</label>
      <input type="email" id="email" name="email"
        value="<?= $email ?>"
        placeholder="votre@email.com" required>

      <label for="message">Message :</label>
      <textarea id="message" name="message" rows="5"
        placeholder="Votre message..." required><?= $message ?></textarea>
<input type="hidden" name="csrf_token" value="<?= generer_token_csrf() ?>">
      <button type="submit" class="bouton">Envoyer</button>

    </form>
  </section>
<?php
// Initialisation des variables pour le formulaire de projet
$erreurs_projet = [];
$succes_projet  = false;
$demande        = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['envoyer_projet'])) {

    // Stocker les données dans un tableau associatif
    $demande = [
        'nom'         => nettoyer($_POST['nom_client']   ?? ''),
        'email'       => nettoyer($_POST['email_client'] ?? ''),
        'type_projet' => nettoyer($_POST['type_projet']  ?? ''),
        'description' => nettoyer($_POST['description']  ?? ''),
        'budget'      => nettoyer($_POST['budget']       ?? ''),
    ];

    // Validation champ par champ
    if (!champ_requis($demande['nom']))
        $erreurs_projet['nom'] = 'Le nom est obligatoire.';
    if (!filter_var($demande['email'], FILTER_VALIDATE_EMAIL))
        $erreurs_projet['email'] = "L'adresse e-mail est invalide.";
    if (!champ_requis($demande['type_projet']))
        $erreurs_projet['type_projet'] = 'Le type de projet est obligatoire.';
    if (!champ_requis($demande['description']))
        $erreurs_projet['description'] = 'La description est obligatoire.';

    if (empty($erreurs_projet)) {
    $stmt = $pdo->prepare(
        'INSERT INTO demandes_projet (nom, email, type_projet, budget, description)
         VALUES (:nom, :email, :type_projet, :budget, :description)'
    );
    $stmt->execute([
        ':nom'         => $demande['nom'],
        ':email'       => $demande['email'],
        ':type_projet' => $demande['type_projet'],
        ':budget'      => $demande['budget'],
        ':description' => $demande['description'],
    ]);
    $succes_projet = true;
}
}
?>

  <!-- FORMULAIRE DEMANDE DE PROJET -->
  <section class="formulaire-section">
    <h2>Demande de projet</h2>

    <?php if ($succes_projet): ?>
      <div class="succes">
        <p>Merci <?= $demande['nom'] ?> ! Voici le récapitulatif de ta demande :</p>
        <ul>
          <li>Type de projet : <?= $demande['type_projet'] ?></li>
          <li>Budget : <?= $demande['budget'] ?></li>
          <li>Description : <?= $demande['description'] ?></li>
        </ul>
      </div>
    <?php endif; ?>

    <form method="POST" action="contact.php" class="formulaire">

      <label for="nom_client">Nom :</label>
      <input type="text" id="nom_client" name="nom_client"
        value="<?= $demande['nom'] ?? '' ?>"
        placeholder="Votre nom" required>
      <?php if (isset($erreurs_projet['nom'])): ?>
        <span class="erreur-champ"><?= $erreurs_projet['nom'] ?></span>
      <?php endif; ?>

      <label for="email_client">E-mail :</label>
      <input type="email" id="email_client" name="email_client"
        value="<?= $demande['email'] ?? '' ?>"
        placeholder="votre@email.com" required>
      <?php if (isset($erreurs_projet['email'])): ?>
        <span class="erreur-champ"><?= $erreurs_projet['email'] ?></span>
      <?php endif; ?>

      <label for="type_projet">Type de projet :</label>
      <select id="type_projet" name="type_projet" required>
        <option value="">-- Choisir --</option>
        <option value="site-vitrine">Site vitrine</option>
        <option value="ecommerce">Boutique en ligne</option>
        <option value="app-web">Application web</option>
        <option value="blog">Blog</option>
        <option value="autre">Autre</option>
      </select>
      <?php if (isset($erreurs_projet['type_projet'])): ?>
        <span class="erreur-champ"><?= $erreurs_projet['type_projet'] ?></span>
      <?php endif; ?>

      <label for="budget">Budget :</label>
      <select id="budget" name="budget">
        <option value="">-- Choisir --</option>
        <option value="moins-500">Moins de 500€</option>
        <option value="500-1500">500€ – 1 500€</option>
        <option value="1500-3000">1 500€ – 3 000€</option>
        <option value="plus-3000">Plus de 3 000€</option>
      </select>

      <label for="description">Description :</label>
      <textarea id="description" name="description" rows="5"
        placeholder="Décrivez votre projet..." required><?= $demande['description'] ?? '' ?></textarea>
      <?php if (isset($erreurs_projet['description'])): ?>
        <span class="erreur-champ"><?= $erreurs_projet['description'] ?></span>
      <?php endif; ?>
<input type="hidden" name="csrf_token" value="<?= generer_token_csrf() ?>">
      <button type="submit" name="envoyer_projet" class="bouton">Envoyer la demande</button>

    </form>
  </section>
<?php require 'composants/pied-de-page.php'; ?>

</body>
</html>
