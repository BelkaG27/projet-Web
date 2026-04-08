<?php
/**
 * Traitement de l'ajout d'un ingrédient — admin/ajouter_ingredient.php
 *
 * Script de traitement POST appelé depuis ingredient_add.php.
 * Il valide les données reçues, gère le téléversement de l'image dans le dossier img/,
 * insère le nouvel ingrédient en base de données, puis redirige vers le tableau de bord.
 *
 * En cas d'erreur (nom vide), un message est stocké en session
 * et l'utilisateur est renvoyé vers le formulaire.
 */

require_once '../classes/RecetteDB.php';

$DB = new RecetteDB();

// Démarrage de la session pour écrire les messages d'erreur
session_start();

if (!empty($_POST['name'])) {
    // --- Données valides ---
    $_SESSION['erreur'] = ''; // Réinitialisation du message d'erreur
    $name1 = $_POST['name'];

    // Gestion du téléversement de l'image
    $file = $_FILES['image'];
    if ($file['error'] == 0) {
        // Téléversement réussi : déplacement du fichier temporaire vers le dossier img/
        $tmp_name = $file['tmp_name'];
        $name     = $file['name'];
        move_uploaded_file($tmp_name, '../img/' . $name);
        $img = $name; // Nom du fichier enregistré en BDD
    } else {
        // Échec du téléversement (fichier absent ou erreur PHP)
        echo '<h1 style="color:red">ERROR : importation du fichier</h1>';
        $img = null; // L'ingrédient sera créé sans image
    }

    // Insertion de l'ingrédient en base de données
    $DB->ajouterIngredient($name1, $img);

    // Redirection vers le tableau de bord admin
    header('Location: index.php');
    exit();

} else {
    // --- Nom vide : retour au formulaire avec un message d'erreur ---
    $_SESSION['erreur'] = 'Le nom est vide !';
    header('Location: ingredient_add.php');
    exit();
}
