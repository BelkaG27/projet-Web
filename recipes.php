<?php
require_once 'classes/Template.php';

$template = new Template('Toutes les recettes - Mon Livre de Recettes', 'recipes');

$recipes = [
    [
        "id" => 1,
        "title" => "Tarte aux pommes",
        "photo" => "assets/images/recipe1.jpg",
        "tags" => ["dessert", "four"],
        "ingredients" => ["pomme", "farine", "sucre"]
    ],
    [
        "id" => 2,
        "title" => "Pizza maison",
        "photo" => "assets/images/recipe2.jpg",
        "tags" => ["rapide", "italien"],
        "ingredients" => ["tomate", "fromage", "farine"]
    ],
    [
        "id" => 3,
        "title" => "Salade fraîche",
        "photo" => "assets/images/recipe3.jpg",
        "tags" => ["léger", "été"],
        "ingredients" => ["salade", "tomate", "concombre"]
    ],
    [
        "id" => 4,
        "title" => "Gâteau au chocolat",
        "photo" => "assets/images/recipe4.jpg",
        "tags" => ["dessert", "gourmand"],
        "ingredients" => ["chocolat", "farine", "sucre"]
    ],
    [
        "id" => 5,
        "title" => "Omelette au fromage",
        "photo" => "assets/images/recipe5.jpg",
        "tags" => ["facile", "rapide"],
        "ingredients" => ["oeuf", "fromage", "beurre"]
    ],
    [
        "id" => 6,
        "title" => "Soupe de légumes",
        "photo" => "assets/images/recipe6.jpg",
        "tags" => ["hiver", "léger"],
        "ingredients" => ["carotte", "pomme de terre", "oignon"]
    ]
];

ob_start();
?>

<section class="page-hero small-hero">
    <div class="section-title">
        <h1>Toutes les recettes</h1>
        <p>Découvrez l’ensemble de nos idées gourmandes</p>
    </div>
</section>

<section class="all-recipes-section">
    <div class="recipes-grid">
        <?php foreach ($recipes as $recipe): ?>
            <article class="recipe-card">
                <img src="<?= htmlspecialchars($recipe['photo']); ?>" alt="<?= htmlspecialchars($recipe['title']); ?>">

                <div class="recipe-card-content">
                    <h3><?= htmlspecialchars($recipe['title']); ?></h3>

                    <div class="recipe-tags">
                        <?php foreach ($recipe['tags'] as $tag): ?>
                            <span><?= htmlspecialchars($tag); ?></span>
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
</section>

<?php
$content = ob_get_clean();
$template->render($content);