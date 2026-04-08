<?php
/**
 * Page d'accueil publique — index.php
 *
 * Affiche :
 *  - une section hero avec le message de bienvenue,
 *  - les 4 premières recettes en vedette,
 *  - un slider horizontal de tags cliquables,
 *  - un formulaire de recherche rapide,
 *  - une section explicative "Comment ça marche ?".
 */

require_once 'classes/Template.php';
require_once 'classes/RecetteDB.php';

// Initialisation du moteur de template (titre de l'onglet + page active dans le menu)
$template = new Template('Accueil - Ratatouille', 'index');

// Connexion à la base de données
$DB = new RecetteDB();

// Récupération des identifiants des recettes à afficher sur l'accueil
$id_recette = $DB->getIdRecettesPageAcceuil();

// Construction du tableau $recipes avec les données nécessaires à l'affichage
foreach ($id_recette as $key => $id) {
    $recipes[$key] = [
        "id"    => $id,
        "title" => $DB->getNomRecette($id),
        "photo" => 'img/' . $DB->getImageRecette($id),
        "tags"  => $DB->getTagsRecette($id),
    ];
}

// Récupération de tous les tags disponibles pour le slider
$tags = $DB->getTagsPageAcceuil();

// On ne garde que les 4 premières recettes pour la section "Recettes à découvrir"
$featuredRecipes = array_slice($recipes, 0, 4);

// Début du tampon de sortie : tout le HTML ci-dessous sera capturé dans $content
ob_start();
?>

<!-- ===== SECTION HERO ===== -->
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

<!-- ===== RECETTES EN VEDETTE (4 premières) ===== -->
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

                    <!-- Badges des tags associés à la recette -->
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

<!-- ===== SLIDER DE TAGS ===== -->
<!-- Les tags sont dupliqués pour créer un effet de défilement infini -->
<section class="tags-section">
    <div class="section-title">
        <h2>Explorer par tags</h2>
        <p>Faites défiler les catégories</p>
    </div>

    <div class="tags-wrapper">
        <!-- Bouton de défilement vers la gauche (géré par main.js) -->
        <button class="scroll-btn left" type="button" aria-label="Défiler à gauche">&#10094;</button>

        <div class="tags-slider" id="tagsSlider">
            <!-- Premier passage des tags -->
            <?php foreach ($tags as $tag): ?>
                <a href="search.php?tag=<?= urlencode($tag); ?>" class="tag-item">
                    <?= htmlspecialchars($tag); ?>
                </a>
            <?php endforeach; ?>

            <!-- Second passage identique pour l'effet de boucle -->
            <?php foreach ($tags as $tag): ?>
                <a href="search.php?tag=<?= urlencode($tag); ?>" class="tag-item">
                    <?= htmlspecialchars($tag); ?>
                </a>
            <?php endforeach; ?>
        </div>

        <!-- Bouton de défilement vers la droite (géré par main.js) -->
        <button class="scroll-btn right" type="button" aria-label="Défiler à droite">&#10095;</button>
    </div>
</section>

<!-- ===== FORMULAIRE DE RECHERCHE RAPIDE ===== -->
<!-- Ce formulaire soumet les critères vers search.php via GET -->
<section class="search-section">
    <div class="search-card">
        <div class="section-title">
            <h2>Recherche de recettes</h2>
            <p>Recherchez par nom, tag ou ingrédients</p>
        </div>

        <form action="search.php" method="GET" class="search-form">
            <div class="form-group">
                <label for="title">Nom de recette</label>
                <input type="text" id="title" name="title" placeholder="Ex : Tarte aux pommes">
            </div>

            <div class="form-group">
                <label for="tag">Tag</label>
                <input type="text" id="tag" name="tag" placeholder="Ex : dessert">
            </div>

            <div class="form-group">
                <label for="ingredients">Ingrédients que vous avez</label>
                <input type="text" id="ingredients" name="ingredients" placeholder="Ex : tomate, fromage, oeuf">
            </div>

            <button type="submit" class="btn-primary">Rechercher</button>
        </form>
    </div>
</section>

<!-- ===== SECTION "COMMENT ÇA MARCHE ?" ===== -->
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
            <p>Suivez la recette et profitez d'un plat simple et gourmand.</p>
        </article>
    </div>
</section>

<?php
// Récupération du HTML généré et rendu via le template
$content = ob_get_clean();
$template->render($content);
