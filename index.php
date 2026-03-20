<?php
$recipes=[
    [
        "id"=>1,
        "title" => "Tarte aux pommes",
        "photo" => "assets/images/recipe1.jpg",
        "tags" => ["dessert", "four"]
    ],
    [
        "id" => 2,
        "title" => "Pizza maison",
        "photo" => "assets/images/recipe2.jpg",
        "tags" => ["rapide", "italien"]
    ],
    [
        "id" => 3,
        "title" => "Salade fraîche",
        "photo" => "assets/images/recipe3.jpg",
        "tags" => ["léger", "été"]
    ],
    [
        "id" => 4,
        "title" => "Gâteau au chocolat",
        "photo" => "assets/images/recipe4.jpg",
        "tags" => ["dessert", "gourmand"]
    ],
    [
        "id" => 5,
        "title" => "Omelette au fromage",
        "photo" => "assets/images/recipe5.jpg",
        "tags" => ["facile", "rapide"]
    ],
    [
        "id" => 6,
        "title" => "Soupe de légumes",
        "photo" => "assets/images/recipe6.jpg",
        "tags" => ["hiver", "léger"]
    ]
];

$tags = [
    "dessert",
    "four",
    "rapide",
    "hiver",
    "fruit",
    "léger",
    "salade",
    "été",
    "végétarien",
    "chocolat",
    "facile",
    "maison",
    "test1",
    "1",
    "2"
];

$featuredRecipes=array_slice($recipes,0,4);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Livre de Recettes</title>
    <link rel="stylesheet" href="assets\css\style.css">
</head>
<body>
    
<?php include 'includes/header.php'; ?>

<main>

    <!-- SECTION HERO -->
    <section class="hero">
        <div class="hero-text">
            <p class="hero-subtitle">Des idées simples avec ce que vous avez</p>
            <h1>Trouvez une recette selon vos envies</h1>
            <p>
                Découvrez des recettes gourmandes en recherchant par nom,
                par tag, ou à partir des ingrédients que vous avez déjà chez vous.
            </p>
            <a href="recipes.php" class="btn-primary">Explorer les recttes</a>
        </div>

        <div class="hero-image">
            <img src="assets/images/hero-dish.jpg" alt="Plat mis en avant">
        </div>
    </section>

    <!-- Recettes a decouvrir -->
    <section class="discover-recipes">
        <div class="section-title">
            <h2>Recettes à découvrir</h2>
            <p>Quelques idées gourmandes pour commencer</p>
        </div>

        <div class="recipes-grid">
            <?php foreach ($featuredRecipes as $recipe): ?>
                <article class="recipe-card">
                    <img src="<?= htmlspecialchars($recipe['photo']); ?>" alt="<?= htmlspecialchars($recipe['title']); ?>">

                    <div class="recipe-card-content">
                        <h3>
                            <?= htmlspecialchars($recipe['title']); ?>
                        </h3>

                        <div class="recipe-tags">
                            <?php foreach(($recipe['tags']) as $tag): ?>
                                <span><?= htmlspecialchars($tag); ?></span>
                            <?php endforeach; ?>
                        </div>

                        <a href="recipe.php?id=<? urlencode($recipe['id']); ?>" class="btn-secondary">
                            voir la recette
                        </a>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- TAGS HORIZONTAUX -->
     <section class="tags-section">
        <div class="section-title">
            <h2>Explorer par tags</h2>
            <p>Faites défiler les catégories</p>
        </div>

        <div class="tags-wrapper">
            <button class="scroll-btn left" type="button" aria-label="Défiler à gauche">
                &#10094;
            </button>

            <div id="tagsSlider" class="tags-slider">
                <?php foreach ($tags as $tag): ?>
                    <a href="search.php?tag=<?= urlencode($tag); ?>" class="tag-item">
                        <?= htmlspecialchars($tag); ?>
                    </a>
                <?php endforeach; ?>
            </div>

            <button class="scroll-btn right" type="button" aria-label="Défiler à droite">
                &#10095;
            </button>
        </div>
     </section>

     <!-- Recherche -->
      <section class="search-section">
        <div class="search-card">
            <div class="section-title">
                <h2>Recherche de recettes</h2>
                <p>Recherchez par nom, tag ou ingrédients</p>
            </div>

            <form action="serach.php" method="GET" class="search-form">
                <div class="form-group">
                    <label for="title">Nom de recette</label>
                    <input type="text" id="title" name="title" placeholder="Ex: Tarte aux pommes">
                </div>

                <div class="form-group">
                    <label for="tag">Tag</label>
                    <input type="text" id="tag" name="tag" placeholder="Ex: dessert">
                </div>

                <div class="form-group">
                    <label for="ingredients">Ingédients que vous avez</label>
                    <input type="text" id="ingredients" name="ingredients" placeholder="Ex: tomate, fromage, oeuf">
                </div>

                <button type="submit" class="btn-primary">Rechercher</button>
            </form>
        </div>
      </section>

      <!-- Comment ca marche -->
       <section class="how-it-works">
        <div class="section-title">
            <h2>Comment ça marche ?</h2>
            <p>3 étapes pour trouver votre recette</p>
        </div>

        <div class="steps-grid">
            <article class="step-card">
                <div class="step-number">1</div>
                <h3>Recherchez</h3>
                <p>Saisissez un nom, un tag ou les ingrédients que vous avez.</p>
            </article>

            <article class="step-card">
                <div class="step-number">2</div>
                <h3>Choisissez</h3>
                <p>Parcourez les recettes proposées et trouvez celle qui vous plaît.</p>
            </article>

            <article class="step-card">
                <div class="step-number">3</div>
                <h3>Cuisinez</h3>
                <p>Suivez la recette et profitez d’un plat simple et gourmand.</p>
            </article>
        </div>
       </section>

</main>

<?php include 'includes/footer.php'; ?>

<script src="assets\js\main.js"></script>
</body>
</html>