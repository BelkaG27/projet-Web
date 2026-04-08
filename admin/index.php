<?php
// Inclusion des classes nécessaires
require_once 'classes/Template.php';
require_once 'classes/RecetteDB.php';

// Création du template de la page (titre + page active)
$template = new Template('Accueil - Ratatouille', 'index');

// Création de l'objet permettant d'accéder à la base de données des recettes
$DB = new RecetteDB();

// Récupère les ID des recettes à afficher sur la page d'accueil
$id_recette = $DB->getIdRecettesPageAcceuil();

// Pour chaque ID de recette récupéré
foreach($id_recette as $key=>$id){
    // On construit un tableau contenant les informations nécessaires pour l'affichage
    $recipes[$key] = [
        "id"=>$id, // ID de la recette
        "title"=>$DB->getNomRecette($id), // Nom de la recette
        "photo"=>'img/'.$DB->getImageRecette($id), // Chemin de l'image
        "tags"=>$DB->getTagsRecette($id) // Tags associés à la recette
    ];
}

// Récupération des tags populaires pour la page d'accueil
$tags = $DB->getTagsPageAcceuil();

// Sélectionne seulement les 4 premières recettes pour la section "Recettes à découvrir"
$featuredRecipes = array_slice($recipes, 0, 4);

// Démarre un buffer pour capturer le HTML généré
ob_start();
?>

<!-- Section principale de présentation -->
<section class="hero">
    <div class="hero-text">
        <p class="hero-subtitle">Des idées simples avec ce que vous avez</p>
        <h1>Trouvez une recette selon vos envies</h1>
        <p>
            Découvrez des recettes gourmandes en recherchant par nom,
            par tag, ou à partir des ingrédients que vous avez déjà chez vous.
        </p>
        <!-- Bouton vers la page de toutes les recettes -->
        <a href="recipes.php" class="btn-primary">Explorer les recettes</a>
    </div>

    <!-- Image mise en avant -->
    <div class="hero-image">
        <img src="img/indexx.jpg" alt="Plat mis en avant">
    </div>
</section>

<!-- Section affichant quelques recettes -->
<section class="discover-recipes">
    <div class="section-title">
        <h2>Recettes à découvrir</h2>
        <p>Quelques idées gourmandes pour commencer</p>
    </div>

    <div class="recipes-grid">
        <!-- Boucle sur les recettes sélectionnées -->
        <?php foreach ($featuredRecipes as $recipe): ?>
            <article class="recipe-card">
                <!-- Image de la recette -->
                <img src="<?= htmlspecialchars($recipe['photo']); ?>" alt="<?= htmlspecialchars($recipe['title']); ?>">

                <div class="recipe-card-content">
                    <!-- Titre de la recette -->
                    <h3><?= htmlspecialchars($recipe['title']); ?></h3>

                    <!-- Affichage des tags de la recette -->
                    <div class="recipe-tags">
                        <?php foreach ($recipe['tags'] as $tag): ?>
                            <span><?= htmlspecialchars($tag); ?></span>
                        <?php endforeach; ?>
                    </div>

                    <!-- Lien vers la page détaillée de la recette -->
                    <a href="recipe.php?id=<?= urlencode($recipe['id']); ?>" class="btn-secondary">
                        Voir la recette
                    </a>
                </div>
            </article>
        <?php endforeach; ?>
    </div>
</section>

<!-- Section permettant de parcourir les recettes par tags -->
<section class="tags-section">
    <div class="section-title">
        <h2>Explorer par tags</h2>
        <p>Faites défiler les catégories</p>
    </div>

    <div class="tags-wrapper">
        <!-- Bouton pour défiler à gauche -->
        <button class="scroll-btn left" type="button" aria-label="Défiler à gauche">&#10094;</button>

        <!-- Slider contenant les tags -->
        <div class="tags-slider" id="tagsSlider">
            <?php foreach ($tags as $tag): ?>
                <!-- Chaque tag renvoie vers la page de recherche -->
                <a href="search.php?tag=<?= urlencode($tag); ?>" class="tag-item">
                    <?= htmlspecialchars($tag); ?>
                </a>
            <?php endforeach; ?>

            <!-- Duplication des tags pour créer un effet de défilement infini -->
            <?php foreach ($tags as $tag): ?>
                <a href="search.php?tag=<?= urlencode($tag); ?>" class="tag-item">
                    <?= htmlspecialchars($tag); ?>
                </a>
            <?php endforeach; ?>
        </div>

        <!-- Bouton pour défiler à droite -->
        <button class="scroll-btn right" type="button" aria-label="Défiler à droite">&#10095;</button>
    </div>
</section>

<!-- Section contenant le formulaire de recherche -->
<section class="search-section">
    <div class="search-card">
        <div class="section-title">
            <h2>Recherche de recettes</h2>
            <p>Recherchez par nom, tag ou ingrédients</p>
        </div>

        <!-- Formulaire envoyé vers la page search.php -->
        <form action="search.php" method="GET" class="search-form">

            <!-- Champ pour rechercher par nom -->
            <div class="form-group">
                <label for="title">Nom de recette</label>
                <input type="text" id="title" name="title" placeholder="Ex : Tarte aux pommes">
            </div>

            <!-- Champ pour rechercher par tag -->
            <div class="form-group">
                <label for="tag">Tag</label>
                <input type="text" id="tag" name="tag" placeholder="Ex : dessert">
            </div>

            <!-- Champ pour rechercher par ingrédients -->
            <div class="form-group">
                <label for="ingredients">Ingrédients que vous avez</label>
                <input type="text" id="ingredients" name="ingredients" placeholder="Ex : tomate, fromage, oeuf">
            </div>

            <!-- Bouton de recherche -->
            <button type="submit" class="btn-primary">Rechercher</button>
        </form>
    </div>
</section>

<!-- Section expliquant le fonctionnement du site -->
<section class="how-it-works">
    <div class="section-title">
        <h2>Comment ça marche ?</h2>
        <p>3 étapes pour trouver votre recette</p>
    </div>

    <div class="steps-grid">

        <!-- Étape 1 -->
        <article class="step-card">
            <div class="step-number">1</div>
            <h3>Recherchez</h3>
            <p>Saisissez un nom, un tag ou les ingrédients que vous avez.</p>
        </article>

        <!-- Étape 2 -->
        <article class="step-card">
            <div class="step-number">2</div>
            <h3>Choisissez</h3>
            <p>Parcourez les recettes proposées et trouvez celle qui vous plaît.</p>
        </article>

        <!-- Étape 3 -->
        <article class="step-card">
            <div class="step-number">3</div>
            <h3>Cuisinez</h3>
            <p>Suivez la recette et profitez d’un plat simple et gourmand.</p>
        </article>

    </div>
</section>

<?php
// Récupère tout le HTML généré dans le buffer
$content = ob_get_clean();

// Envoie le contenu au template pour afficher la page complète
$template->render($content);