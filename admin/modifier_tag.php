<?php
/**
 * Traitement de la modification d'un tag — admin/modifier_tag.php
 *
 * Script de traitement POST appelé depuis tag_edit_form.php.
 * Valide que le nouveau nom n'est pas vide, met à jour le tag en BDD,
 * puis redirige vers le tableau de bord admin.
 *
 * Champs POST attendus :
 *  - 'name'     : nouveau nom du tag (obligatoire)
 *  - 'old_name' : ancien nom (pour identifier l'enregistrement en BDD)
 */

require_once '../classes/RecetteDB.php';

$DB = new RecetteDB();

// Démarrage de la session pour écrire les messages d'erreur
session_start();

if (!empty($_POST['name'])) {
    // --- Données valides ---
    $_SESSION['erreur'] = ''; // Réinitialisation du message d'erreur
    $name1 = $_POST['name'];     // Nouveau nom du tag
    $name2 = $_POST['old_name']; // Ancien nom (clé de recherche en BDD)

    // Mise à jour du tag en base de données
    $DB->modifierTag($name1, $name2);

    // Redirection vers le tableau de bord admin
    header('Location: index.php');
    exit();

} else {
    // --- Nom vide : retour au formulaire avec un message d'erreur ---
    $_SESSION['erreur'] = 'Le nom est vide !';
    header('Location: tag_edit_form.php');
    exit();
}
