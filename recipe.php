<?php
require_once 'classes/Template.php';
require_once 'classes/RecetteDB.php';

$template = new Template('Accueil - Mon Livre de Recettes', 'index');
$DB = new RecetteDB();

$id_recette = $DB->getIdRecettesPageAcceuil();

foreach($id_recette as $key=>$id){
    $recipes[$key] = ["id"=>$id,"title"=>$DB->getNomRecette($id),"photo"=>'img/'.$DB->getImageRecette($id),"description"=>$DB->getDescriptionRecette($id),"tags"=>$DB->getTagsRecette($id),"ingredients"=>$DB->getIngredientsRecette_pageRecipe($id)];
}



$recipeId = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$selectedRecipe = null;

foreach ($recipes as $recipe) {
    if ($recipe['id'] === $recipeId) {
        $selectedRecipe = $recipe;
        break;
    }
}

$pageTitle = $selectedRecipe
    ? $selectedRecipe['title'] . ' - Mon Livre de Recettes'
    : 'Recette introuvable - Mon Livre de Recettes';

$template = new Template($pageTitle, 'recipes');

ob_start();
?>

<?php if (!$selectedRecipe): ?>
    <section class="recipe-page">
        <div class="empty-state">
            <p>La recette demandée est introuvable.</p>
        </div>
    </section>
<?php else: ?>

    <section class="recipe-page">
        <div class="recipe-detail-card">

            <div class="recipe-detail-image">
                <img
                    src="<?= htmlspecialchars($selectedRecipe['photo']); ?>"
                    alt="<?= htmlspecialchars($selectedRecipe['title']); ?>"
                >
            </div>

            <div class="recipe-detail-content">
                <p class="recipe-detail-subtitle">Détail de la recette</p>
                <h1><?= htmlspecialchars($selectedRecipe['title']); ?></h1>

                <div class="recipe-tags detail-tags">
                    <?php foreach ($selectedRecipe['tags'] as $tag): ?>
                        <span><?= htmlspecialchars($tag); ?></span>
                    <?php endforeach; ?>
                </div>

                <div class="recipe-detail-section">
                    <h2>Ingrédients</h2>
                    <div class="ingredients-grid">
                        <?php foreach ($selectedRecipe['ingredients'] as $ingredient): ?>
                            <div class="ingredient-card">
                                <img
                                    src="img/<?= htmlspecialchars($ingredient['image']); ?>"
                                    alt="<?= htmlspecialchars($ingredient['name']); ?>"
                                >
                                <span><?= htmlspecialchars($ingredient['name']); ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="recipe-detail-section">
                    <h2>Préparation</h2>
                    <p><?= htmlspecialchars($selectedRecipe['description']); ?></p>
                </div>

                <div class="recipe-detail-actions">
                    <a href="recipes.php" class="btn-secondary">Voir toutes les recettes</a>
                    <a href="search.php" class="btn-primary">Nouvelle recherche</a>
                </div>
            </div>

        </div>
    </section>

<?php endif; ?>

<?php
$content = ob_get_clean();
$template->render($content);