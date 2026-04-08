<?php
/**
 * Page de recherche — search.php
 *
 * Permet à l'utilisateur de filtrer les recettes selon trois critères :
 *  - le nom (recherche partielle insensible à la casse),
 *  - un tag (recherche partielle),
 *  - des ingrédients séparés par des virgules (correspondance exacte sur chaque terme).
 *
 * La recherche est déclenchée par soumission GET du formulaire.
 * Si aucun critère n'est saisi, un message invite l'utilisateur à en renseigner un.
 */

require_once 'classes/Template.php';
require_once 'classes/RecetteDB.php';

// Initialisation du template (titre de l'onglet + lien actif "Recherche" dans le menu)
$template = new Template('Recherche - Ratatouille', 'search');

// Connexion à la base de données
$DB = new RecetteDB();

// Chargement de toutes les recettes avec leurs métadonnées
$id_recette = $DB->getIdRecettesPageAcceuil();

foreach ($id_recette as $key => $id) {
    $recipes[$key] = [
        "id"          => $id,
        "title"       => $DB->getNomRecette($id),
        "photo"       => 'img/' . $DB->getImageRecette($id),
        "tags"        => $DB->getTagsRecette($id),
        "ingredients" => $DB->getIngredientsRecette($id)["nom"],
    ];
}

// --- Lecture et nettoyage des paramètres GET ---
$title           = trim($_GET['title']       ?? '');
$tag             = trim($_GET['tag']         ?? '');
$ingredientsInput = trim($_GET['ingredients'] ?? '');

// Détermine si le formulaire a été soumis (au moins un champ présent dans l'URL)
$isSearchSubmitted = isset($_GET['title']) || isset($_GET['tag']) || isset($_GET['ingredients']);

// Détermine si au moins un critère est renseigné (non vide)
$hasSearchCriteria = ($title !== '' || $tag !== '' || $ingredientsInput !== '');

$results = [];

// --- Logique de filtrage ---
if ($isSearchSubmitted && $hasSearchCriteria) {

    // Découpe la liste d'ingrédients saisie par l'utilisateur (séparateur : virgule)
    $searchedIngredients = [];
    if ($ingredientsInput !== '') {
        $searchedIngredients = array_map('trim', explode(',', strtolower($ingredientsInput)));
        $searchedIngredients = array_filter($searchedIngredients); // Supprime les valeurs vides
    }

    foreach ($recipes as $recipe) {
        $match = true; // On part du principe que la recette correspond, on invalide si besoin

        // Filtre par titre (correspondance partielle, insensible à la casse)
        if ($title !== '' && stripos($recipe['title'], $title) === false) {
            $match = false;
        }

        // Filtre par tag (correspondance partielle sur chacun des tags de la recette)
        if ($tag !== '') {
            $tagFound = false;
            foreach ($recipe['tags'] as $recipeTag) {
                if (stripos($recipeTag, $tag) !== false) {
                    $tagFound = true;
                    break;
                }
            }
            if (!$tagFound) {
                $match = false;
            }
        }

        // Filtre par ingrédients : au moins un ingrédient saisi doit être présent dans la recette
        if (!empty($searchedIngredients)) {
            $recipeIngredientsLower = array_map('strtolower', $recipe['ingredients']);
            $ingredientFound = false;

            foreach ($searchedIngredients as $searchedIngredient) {
                if (in_array($searchedIngredient, $recipeIngredientsLower)) {
                    $ingredientFound = true;
                    break;
                }
            }

            if (!$ingredientFound) {
                $match = false;
            }
        }

        // Ajout de la recette aux résultats si tous les critères actifs sont satisfaits
        if ($match) {
            $results[] = $recipe;
        }
    }
}

// Début du tampon de sortie
ob_start();
?>

<!-- ===== BANNIÈRE DE LA PAGE ===== -->
<section class="page-hero small-hero">
    <div class="section-title">
        <h1>Recherche de recettes</h1>
        <p>Trouvez une recette par nom, tag ou ingrédients</p>
    </div>
</section>

<!-- ===== FORMULAIRE DE RECHERCHE ===== -->
<!-- Méthode GET pour permettre la sauvegarde/partage de l'URL de recherche -->
<section class="search-section">
    <div class="search-card">
        <form action="search.php" method="GET" class="search-form">
            <div class="form-group">
                <label for="title">Nom de recette</label>
                <input
                    type="text" id="title" name="title"
                    placeholder="Ex : Tarte aux pommes"
                    value="<?= htmlspecialchars($title); ?>"
                >
            </div>

            <div class="form-group">
                <label for="tag">Tag</label>
                <input
                    type="text" id="tag" name="tag"
                    placeholder="Ex : dessert"
                    value="<?= htmlspecialchars($tag); ?>"
                >
            </div>

            <div class="form-group">
                <label for="ingredients">Ingrédients que vous avez</label>
                <input
                    type="text" id="ingredients" name="ingredients"
                    placeholder="Ex : tomate, fromage, oeuf"
                    value="<?= htmlspecialchars($ingredientsInput); ?>"
                >
            </div>

            <button type="submit" class="btn-primary">Rechercher</button>
        </form>
    </div>
</section>

<!-- ===== RÉSULTATS DE LA RECHERCHE ===== -->
<section class="search-results">
    <div class="section-title">
        <h2>Résultats</h2>
    </div>

    <?php if (!$isSearchSubmitted): ?>
        <!-- État initial : formulaire non encore soumis -->
        <div class="empty-state">
            <p>Utilisez le formulaire ci-dessus pour lancer une recherche.</p>
        </div>

    <?php elseif (!$hasSearchCriteria): ?>
        <!-- Formulaire soumis mais tous les champs sont vides -->
        <div class="empty-state">
            <p>Veuillez saisir au moins un critère de recherche.</p>
        </div>

    <?php elseif (empty($results)): ?>
        <!-- Recherche effectuée mais aucune recette ne correspond -->
        <div class="empty-state">
            <p>Aucune recette ne correspond à votre recherche.</p>
        </div>

    <?php else: ?>
        <!-- Affichage des recettes trouvées sous forme de grille -->
        <div class="recipes-grid">
            <?php foreach ($results as $recipe): ?>
                <article class="recipe-card">
                    <img src="<?= htmlspecialchars($recipe['photo']); ?>" alt="<?= htmlspecialchars($recipe['title']); ?>">

                    <div class="recipe-card-content">
                        <h3><?= htmlspecialchars($recipe['title']); ?></h3>

                        <!-- Tags de la recette -->
                        <div class="recipe-tags">
                            <?php foreach ($recipe['tags'] as $recipeTag): ?>
                                <span><?= htmlspecialchars($recipeTag); ?></span>
                            <?php endforeach; ?>
                        </div>

                        <!-- Aperçu des ingrédients -->
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
    <?php endif; ?>
</section>

<?php
// Récupération du HTML et rendu via le template commun
$content = ob_get_clean();
$template->render($content);
