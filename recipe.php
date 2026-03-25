<?php
require_once 'classes/Template.php';

$recipes = [
    [
        "id" => 1,
        "title" => "Tarte aux pommes",
        "photo" => "assets/images/recipe1.jpg",
        "tags" => ["dessert", "four"],
        "ingredients" => ["pomme", "farine", "sucre", "beurre", "oeuf"],
        "description" => "Épluchez les pommes, préparez la pâte, disposez les morceaux de pommes puis enfournez jusqu'à obtenir une tarte dorée et fondante."
    ],
    [
        "id" => 2,
        "title" => "Pizza maison",
        "photo" => "assets/images/recipe2.jpg",
        "tags" => ["rapide", "italien"],
        "ingredients" => ["tomate", "fromage", "farine", "huile d'olive"],
        "description" => "Préparez la pâte, étalez-la, ajoutez la sauce tomate, le fromage et enfournez quelques minutes jusqu'à cuisson complète."
    ],
    [
        "id" => 3,
        "title" => "Salade fraîche",
        "photo" => "assets/images/recipe3.jpg",
        "tags" => ["léger", "été"],
        "ingredients" => ["salade", "tomate", "concombre", "huile d'olive"],
        "description" => "Coupez les légumes, mélangez-les dans un saladier et assaisonnez selon votre goût pour une salade légère et rafraîchissante."
    ],
    [
        "id" => 4,
        "title" => "Gâteau au chocolat",
        "photo" => "assets/images/recipe4.jpg",
        "tags" => ["dessert", "gourmand"],
        "ingredients" => ["chocolat", "farine", "sucre", "beurre", "oeuf"],
        "description" => "Faites fondre le chocolat avec le beurre, ajoutez les autres ingrédients puis enfournez pour obtenir un gâteau moelleux."
    ],
    [
        "id" => 5,
        "title" => "Omelette au fromage",
        "photo" => "assets/images/recipe5.jpg",
        "tags" => ["facile", "rapide"],
        "ingredients" => ["oeuf", "fromage", "beurre", "sel"],
        "description" => "Battez les oeufs, ajoutez le fromage puis faites cuire le tout dans une poêle beurrée jusqu'à ce que l'omelette soit bien prise."
    ],
    [
        "id" => 6,
        "title" => "Soupe de légumes",
        "photo" => "assets/images/recipe6.jpg",
        "tags" => ["hiver", "léger"],
        "ingredients" => ["carotte", "pomme de terre", "oignon", "eau"],
        "description" => "Faites cuire les légumes dans l'eau, mixez le tout puis servez chaud pour une soupe douce et réconfortante."
    ]
];

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
                    <ul class="ingredients-list">
                        <?php foreach ($selectedRecipe['ingredients'] as $ingredient): ?>
                            <li><?= htmlspecialchars($ingredient); ?></li>
                        <?php endforeach; ?>
                    </ul>
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