<?php
/**
 * Traitement de l'ajout d'un tag — admin/ajouter_tag.php
 *
 * Script de traitement POST appelé depuis le formulaire d'ajout de tag (tag_add.php).
 * Valide que le champ 'name' n'est pas vide, insère le tag en base de données,
 * puis redirige vers le tableau de bord admin.
 *
 * En cas d'erreur (nom vide), stocke un message en session et renvoie
 * l'utilisateur vers le formulaire.
 */

require_once '../classes/RecetteDB.php';

$DB = new RecetteDB();

// Démarrage de la session pour écrire les messages d'erreur
session_start();

if (!empty($_POST['name'])) {
    // --- Données valides ---
    $_SESSION['erreur'] = ''; // Réinitialisation du message d'erreur
    $name1 = $_POST['name'];

    // Insertion du tag en base de données
    $DB->ajouterTag($name1);

    // Redirection vers le tableau de bord admin
    header('Location: index.php');
    exit();

} else {
    // --- Nom vide : retour au formulaire avec un message d'erreur ---
    $_SESSION['erreur'] = 'Le nom est vide !';
    header('Location: tag_add.php');
    exit();
}
