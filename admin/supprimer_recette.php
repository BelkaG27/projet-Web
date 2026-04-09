<?php
/**
 * Traitement de la suppression d'une recette — admin/supprimer_recette.php
 *
 * Script de traitement POST appelé depuis recipe_delete.php.
 * Valide que le nom reçu n'est pas vide, supprime la recette en BDD,
 * puis redirige vers le tableau de bord admin.
 *
 * Champs POST attendus :
 *  - 'name' : titre de la recette à supprimer (obligatoire)
 */

require_once '../classes/RecetteDB.php';

$DB = new RecetteDB();

// Démarrage de la session pour écrire les messages d'erreur
session_start();

if (!empty($_POST['name'])) {
    // --- Données valides ---
    $_SESSION['erreur'] = ''; // Réinitialisation du message d'erreur
    $nom = $_POST['name'];

    // Suppression de la recette en base de données
    $DB->supprimerRecette($nom);

    // Redirection vers le tableau de bord admin
    header('Location: index.php');
    exit();

} else {
    // --- Nom vide : retour à la liste avec un message d'erreur ---
    $_SESSION['erreur'] = 'Le nom est vide !';
    header('Location: recipe_delete.php');
    exit();
}
