<?php

/**
 * Vérifie qu'un champ n'est pas vide après nettoyage.
 * @param string $valeur  La valeur à vérifier
 * @return bool           true si le champ est valide, false sinon
 */
function champ_requis(string $valeur): bool {
    return !empty(trim($valeur));
}

/**
 * Nettoie une valeur pour l'afficher sans risque dans le HTML.
 * @param string $valeur  La valeur brute
 * @return string         La valeur nettoyée
 */
function nettoyer(string $valeur): string {
    return htmlspecialchars(trim($valeur));
}
/**
 * Génère un token CSRF et le stocke en session
 * @return string Le token généré
 */
function generer_token_csrf(): string {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Vérifie que le token CSRF est valide
 * @return bool
 */
function verifier_token_csrf(): bool {
    return isset($_POST['csrf_token']) &&
           isset($_SESSION['csrf_token']) &&
           hash_equals($_SESSION['csrf_token'], $_POST['csrf_token']);
}
?>
