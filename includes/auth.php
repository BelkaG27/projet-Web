<?php
/**
 * Garde d'authentification administrateur
 *
 * Ce fichier doit être inclus en tête de chaque page réservée à l'administration.
 * Il démarre la session PHP et vérifie que l'utilisateur courant possède bien
 * le droit d'accès administrateur.
 * Si ce n'est pas le cas, il redirige immédiatement vers la page de connexion.
 */

// Démarre (ou reprend) la session PHP
session_start();

// Vérifie que la variable de session 'is_admin' existe et vaut true
// Si la condition n'est pas remplie, l'accès est refusé
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    // Redirige vers la page de connexion située un niveau au-dessus
    header('Location: ../login.php');
    exit; // Arrête l'exécution du script pour éviter tout affichage non autorisé
}
