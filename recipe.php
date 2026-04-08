<?php
/**
 * Page de détail d'une recette — recipe.php
 *
 * Reçoit l'identifiant d'une recette via le paramètre GET 'id',
 * recherche la recette correspondante dans les données chargées depuis la BDD,
 * et affiche sa photo, ses tags, ses ingrédients (avec image) et sa description.
 * Si l'identifiant est invalide ou absent, un message d'erreur est affiché.
 */

require_once 'classes/Template.php';
require_once 'classes/RecetteDB.php';

// Initialisation temporaire du template (sera redéfini après récupération du titre réel)
$template = new Template('Accueil - Ratatouille', 'index');

// Connexion à la base de données
$DB = new RecetteDB();

// Récupération des identifiants de toutes les recettes disponibles
$id_recette = $DB->getIdRecettesPageAcceuil();

// Construction du tableau complet des recettes avec toutes les données nécessaires au détail
foreach ($id_recette as $key => $id) {
    $recipes[$key] = [
        "id"          => $id,
        "title"       => $DB->getNomRecette($id),
        "photo"       => 'img/' . $DB->getImageRecette($id),
        "description" => $DB->getDescriptionRecette($id),
        "tags"        => $DB->getTagsRecette($id),
        // Ingrédients avec nom ET image pour la grille de la page de détail
        "ingredients" => $DB->getIngredientsRecette_pageRecipe($id),
    ];
}

// Lecture de l'id passé en GET, casté en entier pour sécuriser la comparaison
$recipeId = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$selectedRecipe = null;

// Recherche de la recette dont l'id correspond au paramètre reçu
foreach ($recipes as $recipe) {
    if ($recipe['id'] === $recipeId) {
        $selectedRecipe = $recipe;
        break;
    }
}

// Définition du titre de la page selon que la recette ait été trouvée ou non
$pageTitle = $selectedRecipe
    ? $selectedRecipe['title'] . ' - Ratatouille'
    : 'Recette introuvable - Ratatouille';

// Réinitialisation du template avec le bon titre et la page "recipes" active dans le menu
$template = new Template($pageTitle, 'recipes');

// Début du tampon de sortie
ob_start();
?>

<?php if (!$selectedRecipe): ?>
    <!-- Cas d'erreur : aucune recette ne correspond à l'id fourni -->
    <section class="recipe-page">
        <div class="empty-state">
            <p>La recette demandée est introuvable.</p>
        </div>
    </section>

<?php else: ?>

    <!-- ===== PAGE DE DÉTAIL DE LA RECETTE ===== -->
    <section class="recipe-page">
        <div class="recipe-detail-card">

            <!-- Photo principale de la recette -->
            <div class="recipe-detail-image">
                <img
                    src="<?= htmlspecialchars($selectedRecipe['photo']); ?>"
                    alt="<?= htmlspecialchars($selectedRecipe['title']); ?>"
                >
            </div>

            <div class="recipe-detail-content">
                <p class="recipe-detail-subtitle">Détail de la recette</p>
                <h1><?= htmlspecialchars($selectedRecipe['title']); ?></h1>

                <!-- Badges des tags de la recette -->
                <div class="recipe-tags detail-tags">
                    <?php foreach ($selectedRecipe['tags'] as $tag): ?>
                        <span><?= htmlspecialchars($tag); ?></span>
                    <?php endforeach; ?>
                </div>

                <!-- ===== GRILLE DES INGRÉDIENTS (nom + image) ===== -->
                <div class="recipe-detail-section">
                    <h2>Ingrédients</h2>
                    <div class="ingredients-grid">
                        <?php foreach ($selectedRecipe['ingredients'] as $ingredient): ?>
                            <div class="ingredient-card">
                                <img
                                    src="img/<?= htmlspecialchars($ingredient['image']); ?>"
                                    alt="<?= htmlspecialchars($ingredient['name']); ?>"
                                >
                                <span><?= htmlspecialchars($ingredient['name']); ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- ===== DESCRIPTION DE LA RECETTE ===== -->
                <div class="recipe-detail-section">
                    <h2>Description</h2>
                    <p><?= htmlspecialchars($selectedRecipe['description']); ?></p>
                </div>

                <!-- Liens de navigation vers les autres pages -->
                <div class="recipe-detail-actions">
                    <a href="recipes.php" class="btn-secondary">Voir toutes les recettes</a>
                    <a href="search.php" class="btn-primary">Nouvelle recherche</a>
                </div>
            </div>

        </div>
    </section>

<?php endif; ?>

<?php
// Récupération du HTML et rendu via le template commun
$content = ob_get_clean();
$template->render($content);
