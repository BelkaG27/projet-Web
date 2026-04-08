<?php
/**
 * Traitement de la suppression d'un tag — admin/supprimer_tag.php
 *
 * Script de traitement POST appelé depuis tag_delete.php.
 * Valide que le nom reçu n'est pas vide, supprime le tag en BDD,
 * puis redirige vers le tableau de bord admin.
 *
 * Champs POST attendus :
 *  - 'name' : nom du tag à supprimer (obligatoire)
 */

require_once '../classes/RecetteDB.php';

$DB = new RecetteDB();

// Démarrage de la session pour écrire les messages d'erreur
session_start();

if (!empty($_POST['name'])) {
    // --- Données valides ---
    $_SESSION['erreur'] = ''; // Réinitialisation du message d'erreur
    $name1 = $_POST['name'];

    // Suppression du tag en base de données
    $DB->supprimerTag($name1);

    // Redirection vers le tableau de bord admin
    header('Location: index.php');
    exit();

} else {
    // --- Nom vide : retour à la liste avec un message d'erreur ---
    $_SESSION['erreur'] = 'Le nom est vide !';
    header('Location: tag_delete.php');
    exit();
}
