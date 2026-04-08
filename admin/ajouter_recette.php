<?php
/**
 * Traitement de l'ajout d'une recette — admin/ajouter_recette.php
 *
 * Script de traitement POST appelé depuis le formulaire de création de recette (recipe_add.php).
 * Il valide les champs obligatoires, gère le téléversement de la photo,
 * récupère les listes d'ingrédients et de tags sélectionnés,
 * puis crée la recette en base de données.
 *
 * Champs POST attendus :
 *  - 'title'        : titre de la recette (obligatoire)
 *  - 'description'  : description de la recette (obligatoire)
 *  - 'photo'        : fichier image de la recette (via $_FILES)
 *  - 'ingredients[]': tableau des noms d'ingrédients sélectionnés
 *  - 'tags[]'       : tableau des tags sélectionnés
 */

require_once '../classes/RecetteDB.php';

$DB = new RecetteDB();

// Démarrage de la session pour écrire les messages d'erreur
session_start();

if (!empty($_POST['title']) && !empty($_POST['description'])) {
    // --- Données valides ---
    $_SESSION['erreur'] = ''; // Réinitialisation du message d'erreur
    $name1 = $_POST['title'];
    $desc  = $_POST['description'];

    // Gestion du téléversement de la photo principale
    $file = $_FILES['photo'];
    if ($file['error'] == 0) {
        // Téléversement réussi : déplacement vers img/
        $tmp_name = $file['tmp_name'];
        $name     = $file['name'];
        move_uploaded_file($tmp_name, '../img/' . $name);
        $img = $name;
    } else {
        // Échec du téléversement
        echo '<h1 style="color:red">ERROR : importation du fichier</h1>';
        $img = null;
    }

    // Récupération des ingrédients et tags sélectionnés (tableaux depuis les champs cachés)
    $ingredients = $_POST['ingredients'];
    $tags        = $_POST['tags'];

    // Création de la recette en base de données
    $DB->creerRecette($name1, $desc, $img, $ingredients, $tags);

    // Redirection vers le tableau de bord admin
    header('Location: index.php');
    exit();

} elseif (empty($_POST['title'])) {
    // Cas : titre manquant
    // ⚠️ La redirection vers 'ingredient_add.php' semble être une coquille — devrait être 'recipe_add.php'
    $_SESSION['erreur'] = 'Le nom est vide !';
    header('Location: ingredient_add.php');
    exit();

} elseif (empty($_POST['description'])) {
    // Cas : description manquante
    // ⚠️ Même remarque sur la redirection
    $_SESSION['erreur'] = 'La description est vide !';
    header('Location: ingredient_add.php');
    exit();
}
