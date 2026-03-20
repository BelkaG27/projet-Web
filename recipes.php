<?php
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
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Toutes les recettes - Mon Livre de Recetteq</title>
    <link rel="stylesheet" href="assets\css\style.css">
</head>
<body>
    
<?php include 'includes/header.php';?>

<main>

    <section class="page-hero small-hero">
        <div class="section-title">
            <h1>Toutes les recettes</h1>
            <p>Découvrez l'ensemble de nos idées gourmandes</p>
        </div>
    </section>

    <section class="all-recipes-section">
        <div class="recipes-grid">
            <?php foreach($recipes as $recipe): ?>
                <article class="recipe-card">
                    <img src="<?= htmlspecialchars($recipe['photo']); ?>" alt="<?= htmlspecialchars($recipe['title']); ?>">

                    <div class="recipe-card-content">
                        <h3><?= htmlspecialchars($recipe['title']); ?></h3>

                        <div class="recipe-tags">
                            <?php foreach($recipe['tags'] as $tag): ?>
                                <span><?= htmlspecialchars($tag); ?></span>
                            <?php endforeach; ?>
                        </div>

                        <p class="recipe-ingredients-preview">
                            <strong>Ingrédients :</strong>
                            <?= htmlspecialchars(implode(', ',$recipe['ingredients'])); ?>
                        </p>

                        <a href="recipe.php?id=<?= urlencode($recipe['id']); ?>" class="btn-secondary">Voir la recette</a>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    </section>

</main>

<?php include 'includes/footer.php'; ?>

<script src="assets\js\main.js"></script>
</body>
</html>