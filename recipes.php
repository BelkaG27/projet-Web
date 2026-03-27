<?php
require_once 'classes/Template.php';
require_once 'classes/RecetteDB.php';

$template = new Template('Accueil - Mon Livre de Recettes', 'index');
$DB = new RecetteDB();

$id_recette = $DB->getIdRecettesPageAcceuil();

foreach($id_recette as $key=>$id){
    $recipes[$key] = ["id"=>$id,"title"=>$DB->getNomRecette($id),"photo"=>'img/'.$DB->getImageRecette($id),"tags"=>$DB->getTagsRecette($id),"ingredients"=>$DB->getIngredientsRecette($id)["nom"]];
}


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