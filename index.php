<?php
require_once 'classes/Template.php';
require_once 'classes/RecetteDB.php';

$template = new Template('Accueil - Ratatouille', 'index');
$DB = new RecetteDB();

$id_recette = $DB->getIdRecettesPageAcceuil();

foreach($id_recette as $key=>$id){
    $recipes[$key] = ["id"=>$id,"title"=>$DB->getNomRecette($id),"photo"=>'img/'.$DB->getImageRecette($id),"tags"=>$DB->getTagsRecette($id)];
}



$tags = $DB->getTagsPageAcceuil();

$featuredRecipes = array_slice($recipes, 0, 4);

ob_start();
?>

<section class="hero">
    <div class="hero-text">
        <p class="hero-subtitle">Des idées simples avec ce que vous avez</p>
        <h1>Trouvez une recette selon vos envies</h1>
        <p>
            Découvrez des recettes gourmandes en recherchant par nom,
            par tag, ou à partir des ingrédients que vous avez déjà chez vous.
        </p>
        <a href="recipes.php" class="btn-primary">Explorer les recettes</a>
    </div>

    <div class="hero-image">
        <img src="img/indexx.jpg" alt="Plat mis en avant">
    </div>
</section>

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
                    <h3><?= htmlspecialchars($recipe['title']); ?></h3>

                    <div class="recipe-tags">
                        <?php foreach ($recipe['tags'] as $tag): ?>
                            <span><?= htmlspecialchars($tag); ?></span>
                        <?php endforeach; ?>
                    </div>

                    <a href="recipe.php?id=<?= urlencode($recipe['id']); ?>" class="btn-secondary">
                        Voir la recette
                    </a>
                </div>
            </article>
        <?php endforeach; ?>
    </div>
</section>

<section class="tags-section">
    <div class="section-title">
        <h2>Explorer par tags</h2>
        <p>Faites défiler les catégories</p>
    </div>

    <div class="tags-wrapper">
        <button class="scroll-btn left" type="button" aria-label="Défiler à gauche">&#10094;</button>

        <div class="tags-slider" id="tagsSlider">
            <?php foreach ($tags as $tag): ?>
                <a href="search.php?tag=<?= urlencode($tag); ?>" class="tag-item">
                    <?= htmlspecialchars($tag); ?>
                </a>
            <?php endforeach; ?>

            <?php foreach ($tags as $tag): ?>
                <a href="search.php?tag=<?= urlencode($tag); ?>" class="tag-item">
                    <?= htmlspecialchars($tag); ?>
                </a>
            <?php endforeach; ?>
        </div>

        <button class="scroll-btn right" type="button" aria-label="Défiler à droite">&#10095;</button>
    </div>
</section>

<section class="search-section">
    <div class="search-card">
        <div class="section-title">
            <h2>Recherche de recettes</h2>
            <p>Recherchez par nom, tag ou ingrédients</p>
        </div>

        <form action="search.php" method="GET" class="search-form">
            <div class="form-group">
                <label for="title">Nom de recette</label>
                <input type="text" id="title" name="title" placeholder="Ex : Pizza">
            </div>

            <div class="form-group">
                <label for="tag">Tag</label>
                <input type="text" id="tag" name="tag" placeholder="Ex : poisson">
            </div>

            <div class="form-group">
                <label for="ingredients">Ingrédients que vous avez</label>
                <input type="text" id="ingredients" name="ingredients" placeholder="Ex : tomate, fromage, oeuf">
            </div>

            <button type="submit" class="btn-primary">Rechercher</button>
        </form>
    </div>
</section>

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

<?php
$content = ob_get_clean();
$template->render($content);