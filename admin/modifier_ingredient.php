<?php
/**
 * Traitement de la modification d'un ingrédient — admin/modifier_ingredient.php
 *
 * Script de traitement POST appelé depuis ingredient_edit_form.php.
 * Il valide les données reçues, gère le téléversement d'une nouvelle image,
 * appelle la méthode BDD pour mettre à jour l'ingrédient, puis redirige.
 *
 * Champs POST attendus :
 *  - 'name'     : nouveau nom de l'ingrédient
 *  - 'old_name' : ancien nom (pour identifier l'enregistrement en BDD)
 *  - 'image'    : nouveau fichier image (optionnel techniquement, mais attendu)
 */

require_once '../classes/RecetteDB.php';

$DB = new RecetteDB();

// Démarrage de la session pour écrire les messages d'erreur
session_start();

if (!empty($_POST['name'])) {
    // --- Données valides ---
    $_SESSION['erreur'] = ''; // Réinitialisation du message d'erreur
    $name1 = $_POST['name'];     // Nouveau nom
    $name2 = $_POST['old_name']; // Ancien nom (clé de recherche en BDD)

    // Gestion du téléversement de la nouvelle image
    $file = $_FILES['image'];
    if ($file['error'] == 0) {
        // Téléversement réussi : déplacement vers img/
        $tmp_name = $file['tmp_name'];
        $name     = $file['name'];
        move_uploaded_file($tmp_name, '../img/' . $name);
        $img = $name;
    } else {
        // Échec du téléversement (aucun fichier sélectionné ou erreur PHP)
        echo '<h1 style="color:red">ERROR : importation du fichier</h1>';
        $img = null; // La mise à jour s'effectuera sans changer l'image
    }

    // Mise à jour de l'ingrédient en BDD (nouveau nom, ancien nom, nouvelle image)
    $DB->modifierIngredient($name1, $name2, $img);

    // ⚠️ Attention : 'ingredient_.php' semble être une coquille — vérifier le nom exact du fichier cible
    header('Location: ingredient_.php');
    exit();

} else {
    // --- Nom vide : retour au formulaire avec un message d'erreur ---
    $_SESSION['erreur'] = 'Le nom est vide !';
    header('Location: ingredient_edit_form.php');
    exit();
}
