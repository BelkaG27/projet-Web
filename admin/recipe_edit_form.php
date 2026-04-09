<?php
/**
 * Formulaire de modification d'une recette — admin/recipe_edit_form.php
 *
 * Reçoit le titre de la recette à modifier via POST (depuis recipe_edit.php).
 * Affiche un formulaire avec sélection dynamique d'ingrédients et de tags,
 * identique à recipe_add.php, avec en plus un champ caché 'old_name'
 * permettant à modifier_recette.php d'identifier l'enregistrement à mettre à jour.
 *
 * Champs POST transmis à modifier_recette.php :
 *  - 'title'        : nouveau titre
 *  - 'old_name'     : ancien titre (caché, issu de $_POST['name'] de recipe_edit.php)
 *  - 'description'  : nouvelle description
 *  - 'photo'        : nouveau fichier image
 *  - 'ingredients[]': ingrédients sélectionnés
 *  - 'tags[]'       : tags sélectionnés
 *
 * ⚠️  Le bouton "Modidifer" contient une faute de frappe — à corriger en "Modifier".
 */

// Vérification de l'authentification admin
require_once '../includes/auth.php';
require_once '../classes/Template.php';
require_once '../classes/RecetteDB.php';

// Initialisation du template avec basePath '../' car on est dans admin/
$template = new Template('Modifier une recette - Admin', '', '../');

// Connexion à la base de données
$DB = new RecetteDB();

// Nom récupéré en GET (non utilisé ici, $_POST est la source réelle du nom)
$nameValue = $_POST['name'] ?? '';

// Chargement de tous les ingrédients et tags existants pour le moteur de recherche JS
$existingIngredients = $DB->getIngredients();
$existingTags        = $DB->getTags();

// Début du tampon de sortie
ob_start();
?>

<!-- ===== BANNIÈRE DE LA PAGE ===== -->
<section class="page-hero small-hero">
    <div class="section-title">
        <h1>Modifier une recette</h1>
        <p>Modifier une recette existante</p>
    </div>
</section>

<!-- ===== FORMULAIRE DE MODIFICATION DE RECETTE ===== -->
<section class="admin-form-section">
    <div class="search-card">
        <!--
            enctype="multipart/form-data" obligatoire pour le téléversement de la photo.
            novalidate désactive la validation HTML5 native (gérée par validation.js).
        -->
        <form class="search-form admin-recipe-form" id="recipeForm"
              method="POST" action="modifier_recette.php"
              novalidate enctype="multipart/form-data">

            <!-- Champ titre -->
            <div class="form-group">
                <label for="title">Titre de la recette</label>
                <input type="text" id="title" name="title" value="<?= htmlspecialchars($nameValue); ?>" placeholder="Ex : Tarte aux pommes">
                <!--
                    Champ caché : ancien titre transmis depuis recipe_edit.php via POST.
                    Utilisé par modifier_recette.php pour cibler le bon enregistrement en BDD.
                -->
                <input name="old_name" value="<?= $_POST['name']; ?>" hidden>
                <small class="error-message"></small>
                <!-- Affichage d'une éventuelle erreur de session -->
                <?php if (isset($_SESSION['erreur']) && $_SESSION['erreur'] !== ''): ?>
                    <h1><?= $_SESSION['erreur']; ?></h1>
                <?php endif; ?>
            </div>

            <!-- Champ description -->
            <div class="form-group">
                <label for="description">Description / préparation</label>
                <textarea id="description" name="description" rows="5"
                          placeholder="Décrivez les étapes de préparation..."></textarea>
                <small class="error-message"></small>
                <?php if (isset($_SESSION['erreur']) && $_SESSION['erreur'] !== ''): ?>
                    <h1><?= $_SESSION['erreur']; ?></h1>
                <?php endif; ?>
            </div>

            <!-- Champ téléversement de la nouvelle photo -->
            <div class="form-group">
                <label for="photo">Photo de la recette</label>
                <input type="file" id="photo" name="photo">
                <small class="error-message"></small>
            </div>

            <!-- ===== BLOC INGRÉDIENTS ===== -->
            <!-- Même fonctionnement que recipe_add.php : recherche filtrée + badges -->
            <div class="admin-block">
                <h2>Ingrédients</h2>

                <div class="form-group">
                    <label for="ingredientSearch">Rechercher un ingrédient</label>
                    <input type="text" id="ingredientSearch"
                           placeholder="Tapez pour chercher un ingrédient" autocomplete="off">
                    <select id="ingredientSelect" size="5" class="dropdown-list"></select>
                </div>

                <div class="selected-items" id="selectedIngredients"></div>
                <small class="error-message group-error" id="ingredientsGroupError"></small>
            </div>

            <!-- ===== BLOC TAGS ===== -->
            <div class="admin-block">
                <h2>Tags</h2>

                <div class="form-group">
                    <label for="tagSearch">Rechercher un tag</label>
                    <input type="text" id="tagSearch"
                           placeholder="Tapez pour chercher un tag" autocomplete="off">
                    <select id="tagSelect" size="5" class="dropdown-list"></select>
                </div>

                <div class="selected-items" id="selectedTags"></div>
            </div>

            
            <button type="submit" class="btn-primary">Modifier la recette</button>
        </form>
    </div>
</section>

<!--
    Injection des données PHP en JavaScript pour le moteur de recherche
    d'ingrédients et de tags dans validation.js.
-->
<script>
    window.recipeFormData = {
        ingredients: <?= json_encode($existingIngredients, JSON_UNESCAPED_UNICODE); ?>,
        tags:        <?= json_encode($existingTags,        JSON_UNESCAPED_UNICODE); ?>
    };
</script>

<?php
// Récupération du HTML et rendu via le template commun
$content = ob_get_clean();
$template->render($content);
