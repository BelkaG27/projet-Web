<?php
/**
 * Page liste des recettes — recipes.php
 *
 * Affiche toutes les recettes disponibles sous forme de grille de cartes.
 * Chaque carte présente la photo, le titre, les tags et un aperçu des ingrédients,
 * avec un lien vers la page de détail (recipe.php).
 */

require_once 'classes/Template.php';
require_once 'classes/RecetteDB.php';

// Initialisation du template (titre de l'onglet + lien actif "Recettes" dans le menu)
$template = new Template('Recettes - Ratatouille', 'recipes');

// Connexion à la base de données
$DB = new RecetteDB();

// Récupération des identifiants de toutes les recettes à afficher
$id_recette = $DB->getIdRecettesPageAcceuil();

// Construction du tableau $recipes avec toutes les données d'affichage nécessaires
foreach ($id_recette as $key => $id) {
    $recipes[$key] = [
        "id"          => $id,
        "title"       => $DB->getNomRecette($id),
        "photo"       => 'img/' . $DB->getImageRecette($id),
        "tags"        => $DB->getTagsRecette($id),
        // Seuls les noms des ingrédients sont récupérés (pas les images) pour l'aperçu
        "ingredients" => $DB->getIngredientsRecette($id)["nom"],
    ];
}

// Début du tampon de sortie
ob_start();
?>

<!-- ===== BANNIÈRE DE LA PAGE ===== -->
<section class="page-hero small-hero">
    <div class="section-title">
        <h1>Toutes les recettes</h1>
        <p>Découvrez l'ensemble de nos idées gourmandes</p>
    </div>
</section>

<!-- ===== GRILLE DE TOUTES LES RECETTES ===== -->
<section class="all-recipes-section">
    <div class="recipes-grid">
        <?php foreach ($recipes as $recipe): ?>
            <article class="recipe-card">
                <img src="<?= htmlspecialchars($recipe['photo']); ?>" alt="<?= htmlspecialchars($recipe['title']); ?>">

                <div class="recipe-card-content">
                    <h3><?= htmlspecialchars($recipe['title']); ?></h3>

                    <!-- Badges des tags de la recette -->
                    <div class="recipe-tags">
                        <?php foreach ($recipe['tags'] as $tag): ?>
                            <span><?= htmlspecialchars($tag); ?></span>
                        <?php endforeach; ?>
                    </div>

                    <!-- Aperçu textuel des ingrédients séparés par des virgules -->
                    <p class="recipe-ingredients-preview">
                        <strong>Ingrédients :</strong>
                        <?= htmlspecialchars(implode(', ', $recipe['ingredients'])); ?>
                    </p>

                    <!-- Lien vers la page de détail, l'id est passé en paramètre GET -->
                    <a href="recipe.php?id=<?= urlencode($recipe['id']); ?>" class="btn-secondary">
                        Voir la recette
                    </a>
                </div>
            </article>
        <?php endforeach; ?>
    </div>
</section>

<?php
// Récupération du HTML et rendu via le template commun
$content = ob_get_clean();
$template->render($content);
