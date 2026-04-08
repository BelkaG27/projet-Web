<?php
/**
 * Déconnexion administrateur
 *
 * Détruit complètement la session PHP courante pour supprimer
 * les droits d'accès administrateur, puis redirige vers l'accueil.
 */

// Démarre (ou reprend) la session afin de pouvoir la manipuler
session_start();

// Supprime toutes les variables stockées en session (is_admin, admin_login, etc.)
session_unset();

// Détruit définitivement la session côté serveur
session_destroy();

// Redirige l'utilisateur vers la page d'accueil publique
header('Location: index.php');
exit;
