<?php
/**
 * Formulaire d'ajout d'une recette — admin/recipe_add.php
 *
 * Affiche un formulaire complet permettant à l'administrateur de créer une nouvelle recette :
 *  - titre, description, photo,
 *  - sélection dynamique d'ingrédients via un champ de recherche filtrant,
 *  - sélection dynamique de tags via un champ de recherche filtrant.
 *
 * Les listes d'ingrédients et de tags existants sont chargées depuis la BDD
 * et injectées en JSON dans window.recipeFormData pour être exploitées par validation.js.
 *
 * La soumission est traitée par ajouter_recette.php.
 */

// Vérification de l'authentification admin
require_once '../includes/auth.php';
require_once '../classes/Template.php';
require_once '../classes/RecetteDB.php';

// Initialisation du template avec basePath '../' car on est dans admin/
$template = new Template('Ajouter une recette - Admin', '', '../');

// Connexion à la base de données
$DB = new RecetteDB();

// Chargement de tous les ingrédients existants pour le moteur de recherche JS
$existingIngredients = $DB->getIngredients();

// Chargement de tous les tags existants pour le moteur de recherche JS
$existingTags = $DB->getTags();

// Début du tampon de sortie
ob_start();
?>

<!-- ===== BANNIÈRE DE LA PAGE ===== -->
<section class="page-hero small-hero">
    <div class="section-title">
        <h1>Ajouter une recette</h1>
        <p>Créez une nouvelle recette</p>
    </div>
</section>

<!-- ===== FORMULAIRE D'AJOUT DE RECETTE ===== -->
<section class="admin-form-section">
    <div class="search-card">
        <!--
            enctype="multipart/form-data" est obligatoire pour le téléversement de la photo.
            novalidate désactive la validation HTML5 native (gérée par validation.js).
            L'action pointe vers ajouter_recette.php qui traite la logique métier.
        -->
        <form class="search-form admin-recipe-form" id="recipeForm"
              method="POST" action="ajouter_recette.php"
              novalidate enctype="multipart/form-data">

            <!-- Champ titre -->
            <div class="form-group">
                <label for="title">Titre de la recette</label>
                <input type="text" id="title" name="title" placeholder="Ex : Tarte aux pommes">
                <small class="error-message"></small>
                <!-- Affichage d'une éventuelle erreur de session -->
                <?php if (isset($_SESSION['erreur']) && $_SESSION['erreur'] !== ''): ?>
                    <h1><?= $_SESSION['erreur']; ?></h1>
                <?php endif; ?>
            </div>

            <!-- Champ description / étapes de préparation -->
            <div class="form-group">
                <label for="description">Description / préparation</label>
                <textarea id="description" name="description" rows="5"
                          placeholder="Décrivez les étapes de préparation..."></textarea>
                <small class="error-message"></small>
                <?php if (isset($_SESSION['erreur']) && $_SESSION['erreur'] !== ''): ?>
                    <h1><?= $_SESSION['erreur']; ?></h1>
                <?php endif; ?>
            </div>

            <!-- Champ téléversement de la photo -->
            <div class="form-group">
                <label for="photo">Photo de la recette</label>
                <input type="file" id="photo" name="photo">
                <small class="error-message"></small>
            </div>

            <!-- ===== BLOC INGRÉDIENTS ===== -->
            <!--
                L'utilisateur tape dans #ingredientSearch → validation.js filtre la liste
                et peuple #ingredientSelect. La sélection crée un badge + un <input hidden>
                dans #selectedIngredients pour la soumission.
                Si l'ingrédient n'existe pas, une option "Créer X" redirige vers ingredient_add.php.
            -->
            <div class="admin-block">
                <h2>Ingrédients</h2>

                <div class="form-group">
                    <label for="ingredientSearch">Rechercher un ingrédient</label>
                    <input type="text" id="ingredientSearch"
                           placeholder="Tapez pour chercher un ingrédient" autocomplete="off">
                    <!-- Liste déroulante filtrée en temps réel par validation.js -->
                    <select id="ingredientSelect" size="5" class="dropdown-list"></select>
                </div>

                <!-- Zone d'affichage des badges des ingrédients sélectionnés -->
                <div class="selected-items" id="selectedIngredients"></div>
                <!-- Message d'erreur affiché si aucun ingrédient n'est sélectionné -->
                <small class="error-message group-error" id="ingredientsGroupError"></small>
            </div>

            <!-- ===== BLOC TAGS ===== -->
            <!-- Même fonctionnement que le bloc ingrédients -->
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

            <button type="submit" class="btn-primary">Ajouter la recette</button>
        </form>
    </div>
</section>

<!--
    Injection des données PHP en JavaScript.
    window.recipeFormData est lu par validation.js pour alimenter
    les listes déroulantes de recherche d'ingrédients et de tags.
    JSON_UNESCAPED_UNICODE conserve les caractères accentués.
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
