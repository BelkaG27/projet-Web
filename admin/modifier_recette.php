<?php
/**
 * Traitement de la modification d'une recette — admin/modifier_recette.php
 *
 * Script de traitement POST appelé depuis recipe_edit_form.php.
 * Valide les champs obligatoires, gère le téléversement de la nouvelle photo,
 * récupère les ingrédients et tags sélectionnés, puis met à jour la recette en BDD.
 *
 * Champs POST attendus :
 *  - 'title'        : nouveau titre de la recette (obligatoire)
 *  - 'old_name'     : ancien titre (pour identifier l'enregistrement en BDD)
 *  - 'description'  : nouvelle description (obligatoire)
 *  - 'photo'        : nouveau fichier image (via $_FILES)
 *  - 'ingredients[]': tableau des noms d'ingrédients sélectionnés
 *  - 'tags[]'       : tableau des tags sélectionnés
 *
 * ⚠️  Les var_dump() présents sont des restes de débogage — à supprimer en production.
 * ⚠️  Les redirections d'erreur pointent vers 'ingredient_add.php' au lieu de 'recipe_edit_form.php'.
 */

require_once '../classes/RecetteDB.php';

$DB = new RecetteDB();

// Démarrage de la session pour lire/écrire les messages d'erreur
session_start();

if (!empty($_POST['title']) && !empty($_POST['description'])) {
    // --- Données valides ---
    $_SESSION['erreur'] = ''; // Réinitialisation du message d'erreur
    $name1 = $_POST['title'];     // Nouveau titre
    $name2 = $_POST['old_name'];  // Ancien titre (clé de recherche en BDD)
    $desc  = $_POST['description'];

    // Gestion du téléversement de la nouvelle photo
    $file = $_FILES['photo'];
    if ($file['error'] == 0) {
        // Téléversement réussi : déplacement vers img/
        $tmp_name = $file['tmp_name'];
        $name     = $file['name'];
        move_uploaded_file($tmp_name, '../img/' . $name);
        $img = $name;
    } else {
        // Échec du téléversement (aucun fichier sélectionné ou erreur PHP)
        echo '<h1 style="color:red">ERROR : importation du fichier</h1>';
        $img = null; // La mise à jour s'effectuera sans changer la photo
    }

    // Récupération des ingrédients et tags transmis via les inputs cachés
    $ingredients = $_POST['ingredients'];
    $tags        = $_POST['tags'];

    // ⚠️ Déboggage — à retirer avant la mise en production
    var_dump($ingredients);
    var_dump($tags);

    // Mise à jour de la recette en base de données
    $DB->modifierRecette($name1, $name2, $desc, $img, $ingredients, $tags);

    // Redirection vers le tableau de bord admin
    header('Location: index.php');
    exit();

} elseif (empty($_POST['title'])) {
    // Titre manquant
    // ⚠️ Redirection incorrecte : devrait pointer vers 'recipe_edit_form.php'
    $_SESSION['erreur'] = 'Le nom est vide !';
    header('Location: ingredient_add.php');
    exit();

} elseif (empty($_POST['description'])) {
    // Description manquante
    // ⚠️ Même remarque sur la redirection
    $_SESSION['erreur'] = 'La description est vide !';
    header('Location: ingredient_add.php');
    exit();
}
