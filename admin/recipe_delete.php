<?php
/**
 * Sélection d'une recette à supprimer — admin/recipe_delete.php
 *
 * Affiche la liste de toutes les recettes existantes sous forme de cartes.
 * Chaque carte contient un formulaire POST vers supprimer_recette.php
 * avec le titre de la recette en champ caché.
 * Le bouton de suppression porte la classe .delete-confirm qui déclenche
 * une confirmation JavaScript avant soumission (via validation.js).
 */

// Vérification de l'authentification admin
require_once '../includes/auth.php';
require_once '../classes/Template.php';
require_once '../classes/RecetteDB.php';

// Initialisation du template avec basePath '../' car on est dans admin/
$template = new Template('Supprimer une recette - Admin', '', '../');

// Connexion à la base de données et récupération de toutes les recettes
$DB = new RecetteDB();
$recipes = $DB->getRecettes();

// Début du tampon de sortie
ob_start();
?>

<!-- ===== BANNIÈRE DE LA PAGE ===== -->
<section class="page-hero small-hero">
    <div class="section-title">
        <h1>Supprimer une recette</h1>
        <p>Sélectionnez une recette à supprimer</p>
    </div>
</section>

<!-- ===== LISTE DES RECETTES À SUPPRIMER ===== -->
<section class="admin-list-section">
    <div class="admin-grid">
        <?php foreach ($recipes as $recipe): ?>
            <div class="admin-card">
                <h3><?= htmlspecialchars($recipe['title']); ?></h3>
                <p>Supprimer cette recette.</p>

                <!--
                    Le titre est transmis en champ caché vers supprimer_recette.php.
                    La classe .delete-confirm déclenche une boîte de confirmation JS
                    avant la soumission effective (voir setupDeleteConfirmations() dans validation.js).
                -->
                <form action="supprimer_recette.php" method="POST">
                    <input name="name" value="<?= $recipe['title']; ?>" hidden>
                    <button type="submit" class="btn-danger delete-confirm">Supprimer</button>
                </form>
            </div>
        <?php endforeach; ?>
    </div>
</section>

<?php
// Récupération du HTML et rendu via le template commun
$content = ob_get_clean();
$template->render($content);
