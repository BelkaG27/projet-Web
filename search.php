<?php
require_once 'classes/Template.php';
require_once 'classes/RecetteDB.php';

$template = new Template('Recherche - Mon Livre de Recettes', 'search');
$DB = new RecetteDB();

$id_recette = $DB->getIdRecettesPageAcceuil();

foreach($id_recette as $key=>$id){
    $recipes[$key] = ["id"=>$id,"title"=>$DB->getNomRecette($id),"photo"=>'img/'.$DB->getImageRecette($id),"tags"=>$DB->getTagsRecette($id),"ingredients"=>$DB->getIngredientsRecette($id)["nom"]];
}



$title = trim($_GET['title'] ?? '');
$tag = trim($_GET['tag'] ?? '');
$ingredientsInput = trim($_GET['ingredients'] ?? '');

$isSearchSubmitted = isset($_GET['title']) || isset($_GET['tag']) || isset($_GET['ingredients']);
$hasSearchCriteria = ($title !== '' || $tag !== '' || $ingredientsInput !== '');

$results = [];

if ($isSearchSubmitted && $hasSearchCriteria) {
    $searchedIngredients = [];

    if ($ingredientsInput !== '') {
        $searchedIngredients = array_map('trim', explode(',', strtolower($ingredientsInput)));
        $searchedIngredients = array_filter($searchedIngredients);
    }

    foreach ($recipes as $recipe) {
        $match = true;

        if ($title !== '' && stripos($recipe['title'], $title) === false) {
            $match = false;
        }

        if ($tag !== '') {
            $tagFound = false;

            foreach ($recipe['tags'] as $recipeTag) {
                if (stripos($recipeTag, $tag) !== false) {
                    $tagFound = true;
                    break;
                }
            }

            if (!$tagFound) {
                $match = false;
            }
        }

        if (!empty($searchedIngredients)) {
            $recipeIngredientsLower = array_map('strtolower', $recipe['ingredients']);
            $ingredientFound = false;

            foreach ($searchedIngredients as $searchedIngredient) {
                if (in_array($searchedIngredient, $recipeIngredientsLower)) {
                    $ingredientFound = true;
                    break;
                }
            }

            if (!$ingredientFound) {
                $match = false;
            }
        }

        if ($match) {
            $results[] = $recipe;
        }
    }
}

ob_start();
?>

<section class="page-hero small-hero">
    <div class="section-title">
        <h1>Recherche de recettes</h1>
        <p>Trouvez une recette par nom, tag ou ingrédients</p>
    </div>
</section>

<section class="search-section">
    <div class="search-card">
        <form action="search.php" method="GET" class="search-form">
            <div class="form-group">
                <label for="title">Nom de recette</label>
                <input
                    type="text"
                    id="title"
                    name="title"
                    placeholder="Ex : Tarte aux pommes"
                    value="<?= htmlspecialchars($title); ?>"
                >
            </div>

            <div class="form-group">
                <label for="tag">Tag</label>
                <input
                    type="text"
                    id="tag"
                    name="tag"
                    placeholder="Ex : dessert"
                    value="<?= htmlspecialchars($tag); ?>"
                >
            </div>

            <div class="form-group">
                <label for="ingredients">Ingrédients que vous avez</label>
                <input
                    type="text"
                    id="ingredients"
                    name="ingredients"
                    placeholder="Ex : tomate, fromage, oeuf"
                    value="<?= htmlspecialchars($ingredientsInput); ?>"
                >
            </div>

            <button type="submit" class="btn-primary">Rechercher</button>
        </form>
    </div>
</section>

<section class="search-results">
    <div class="section-title">
        <h2>Résultats</h2>
    </div>

    <?php if (!$isSearchSubmitted): ?>
        <div class="empty-state">
            <p>Utilisez le formulaire ci-dessus pour lancer une recherche.</p>
        </div>

    <?php elseif (!$hasSearchCriteria): ?>
        <div class="empty-state">
            <p>Veuillez saisir au moins un critère de recherche.</p>
        </div>

    <?php elseif (empty($results)): ?>
        <div class="empty-state">
            <p>Aucune recette ne correspond à votre recherche.</p>
        </div>

    <?php else: ?>
        <div class="recipes-grid">
            <?php foreach ($results as $recipe): ?>
                <article class="recipe-card">
                    <img src="<?= htmlspecialchars($recipe['photo']); ?>" alt="<?= htmlspecialchars($recipe['title']); ?>">

                    <div class="recipe-card-content">
                        <h3><?= htmlspecialchars($recipe['title']); ?></h3>

                        <div class="recipe-tags">
                            <?php foreach ($recipe['tags'] as $recipeTag): ?>
                                <span><?= htmlspecialchars($recipeTag); ?></span>
                            <?php endforeach; ?>
                        </div>

                        <p class="recipe-ingredients-preview">
                            <strong>Ingrédients :</strong>
                            <?= htmlspecialchars(implode(', ', $recipe['ingredients'])); ?>
                        </p>

                        <a href="recipe.php?id=<?= urlencode($recipe['id']); ?>" class="btn-secondary">
                            Voir la recette
                        </a>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>

<?php
$content = ob_get_clean();
$template->render($content);