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

$title = trim($_GET['title'] ?? '');
$tag=trim($_GET['tag'] ?? '');
$ingredientsInput= trim($_GET['ingredients'] ?? '');

$isSearchedSubmitted=isset($_GET['title']) || isset($_GET['tag']) || isset($_GET['ingredients']);

$results=[];

if($isSearchedSubmitted){
    $searchedIngredients=[];

    if($ingredientsInput !==''){
        $searchedIngredients=array_map('trim', explode(',', strtolower($ingredientsInput)));
        $searchedIngredients=array_filter($searchedIngredients);
    }

    foreach($recipes as $recipe){
        $match=true;

        if($title !== ''){
            if(stripos($recipe['title'],$title) === false){
                $match=false;
            }
        }

        if($tag !== ''){
            $tagFound=false;

            foreach($recipe['tags'] as $recipeTag){
                if(stripos($recipeTag,$tag) !== false){
                    $tagFound=true;
                    break;
                }
            }

            if(!$tagFound){
                $match=false;
            }
        }

        if(!empty($searchedIngredients)){
            $recipeIngredientsLower=array_map('strtolower', $recipe['ingredients']);
            $ingredientFound= false;

            foreach($searchedIngredients as $searchedIngredient){
                if(in_array($searchedIngredient, $recipeIngredientsLower)){
                    $ingredientFound=true;
                    break;
                }
            }

            if(!$ingredientFound){
                $match=false;
            }
        }

        if($match){
            $results[]=$recipe;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recherche - Mon Livre de Recettes</title>
    <link rel="stylesheet" href="assets\css\style.css">
</head>
<body>

<?php include 'includes/header.php' ?>

<main>

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
                    <input type="text" id="title" name="title" placeholder="Ex: Tarte aux pommes" value="<?= htmlspecialchars($title); ?>">
                </div>

                <div class="form-group">
                    <label for="tag">Tag</label>
                    <input type="text" id="tag" name="tag" placeholder="Ex: dessert" value="<?= htmlspecialchars($tag); ?>" >
                </div>

                <div class="form-group">
                    <label for="ingredients">Ingrédients que vous avez</label>
                    <input type="text" id="ingredients" name="ingredients" placeholder="Ex: tomate, fromage, oeuf" value="<?= htmlspecialchars($ingredientsInput); ?>" >
                </div>

                <button type="submit" class="btn-primary">Rechercher</button>
            </form>
        </div>
    </section>

    <section class="search-results">
        <div class="section-title">
            <h2>Résultats</h2>
        </div>

        <?php if(!$isSearchedSubmitted): ?>
            <div class="empty-state">
                <p>Utilisez le formulaire ci-dessus pour lancer une recherhe.</p>
            </div>
        <?php elseif(empty($results)): ?>
            <div class="empty-state">
                <p>Aucune recette ne correspond à votre rechreche.</p>
            </div>
        <?php else :?>
            <div class="recipres-grid">
                <?php foreach($results as $recipe) : ?>
                    <article class="recipe-card">
                        <img src="<?= htmlspecialchars($recipe['photo']); ?>" alt="<?= htmlspecialchars($recipe['title']); ?>">

                        <div class="recipe-card-content">
                            <h3><?= htmlspecialchars($recipe['title']); ?></h3>

                            <div class="recipe-tags">
                                <?php foreach($recipe['tags'] as $recipeTag): ?>
                                    <span><?= htmlspecialchars($recipeTag); ?></span>
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
        <?php endif; ?>
    </section>

</main>

<?php include 'includes/footer.php'; ?>

<script src="assets\js\main.js"></script>
</body>
</html>