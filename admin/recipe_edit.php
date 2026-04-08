<?php
/**
 * Sélection d'une recette à modifier — admin/recipe_edit.php
 *
 * Affiche la liste de toutes les recettes existantes sous forme de cartes.
 * Chaque carte contient un formulaire POST vers recipe_edit_form.php
 * qui transmet le titre de la recette sélectionnée pour pré-remplir le formulaire d'édition.
 */

// Vérification de l'authentification admin
require_once '../includes/auth.php';
require_once '../classes/Template.php';
require_once '../classes/RecetteDB.php';

// Initialisation du template avec basePath '../' car on est dans admin/
$template = new Template('Modifier une recette - Admin', '', '../');

// Connexion à la base de données et récupération de toutes les recettes
$DB = new RecetteDB();
$recipes = $DB->getRecettes();

// Début du tampon de sortie
ob_start();
?>

<!-- ===== BANNIÈRE DE LA PAGE ===== -->
<section class="page-hero small-hero">
    <div class="section-title">
        <h1>Modifier une recette</h1>
        <p>Sélectionnez une recette à modifier</p>
    </div>
</section>

<!-- ===== LISTE DES RECETTES ===== -->
<section class="admin-list-section">
    <div class="admin-grid">
        <?php foreach ($recipes as $recipe): ?>
            <div class="admin-card">
                <h3><?= htmlspecialchars($recipe['title']); ?></h3>
                <p>Modifier cette recette.</p>

                <!--
                    Le titre est transmis en champ caché (POST) vers recipe_edit_form.php
                    pour identifier la recette et pré-remplir le formulaire d'édition.
                -->
                <form action="recipe_edit_form.php" method="POST">
                    <input name="name" value="<?= $recipe['title']; ?>" hidden>
                    <button type="submit" class="btn-secondary">Modifier</button>
                </form>
            </div>
        <?php endforeach; ?>
    </div>
</section>

<?php
// Récupération du HTML et rendu via le template commun
$content = ob_get_clean();
$template->render($content);
