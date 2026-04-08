<?php
/**
 * Sélection d'un ingrédient à modifier — admin/ingredient_edit.php
 *
 * Affiche la liste de tous les ingrédients existants sous forme de cartes.
 * Chaque carte contient un formulaire POST vers ingredient_edit_form.php
 * qui transmet le nom de l'ingrédient sélectionné pour pré-remplir le formulaire d'édition.
 */

// Vérification de l'authentification admin
require_once '../includes/auth.php';
require_once '../classes/Template.php';
require_once '../classes/RecetteDB.php';

// Initialisation du template avec basePath '../' car on est dans admin/
$template = new Template('Modifier un ingrédient - Admin', '', '../');

// Connexion à la base de données et récupération de tous les ingrédients
$DB = new recetteDB();
$ingredients = $DB->getIngredients();

// Début du tampon de sortie
ob_start();
?>

<!-- ===== BANNIÈRE DE LA PAGE ===== -->
<section class="page-hero small-hero">
    <div class="section-title">
        <h1>Modifier un ingrédient</h1>
        <p>Sélectionnez un ingrédient à modifier</p>
    </div>
</section>

<!-- ===== LISTE DES INGRÉDIENTS ===== -->
<section class="admin-list-section">
    <div class="admin-grid">
        <?php foreach ($ingredients as $ingredient): ?>
            <div class="admin-card">
                <h3><?= htmlspecialchars($ingredient['name']); ?></h3>
                <p>Modifier cet ingrédient.</p>

                <!--
                    Le nom de l'ingrédient est transmis en champ caché (POST)
                    vers ingredient_edit_form.php pour pré-remplir le formulaire d'édition
                -->
                <form action="ingredient_edit_form.php" method="POST">
                    <input name="name" value="<?= $ingredient['name']; ?>" hidden>
                    <button type="submit" class="btn-secondary">Modifier</button>
                </form>
            </div>
        <?php endforeach; ?>
    </div>
</section>

<?php
// Récupération du HTML et rendu via le template commun
$content = ob_get_clean();
$template->render($content);
