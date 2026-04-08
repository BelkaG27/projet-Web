<?php
/**
 * Sélection d'un tag à supprimer — admin/tag_delete.php
 *
 * Affiche la liste de tous les tags existants sous forme de cartes.
 * Chaque carte contient un formulaire POST vers supprimer_tag.php
 * avec le nom du tag en champ caché.
 * Le bouton de suppression porte la classe .delete-confirm qui déclenche
 * une confirmation JavaScript avant soumission (via validation.js).
 */

// Vérification de l'authentification admin
require_once '../includes/auth.php';
require_once '../classes/Template.php';
require_once '../classes/RecetteDB.php';

// Initialisation du template avec basePath '../' car on est dans admin/
$template = new Template('Supprimer un tag - Admin', '', '../');

// Connexion à la base de données et récupération de tous les tags
$DB = new RecetteDB();
$tags = $DB->getTags();

// Début du tampon de sortie
ob_start();
?>

<!-- ===== BANNIÈRE DE LA PAGE ===== -->
<section class="page-hero small-hero">
    <div class="section-title">
        <h1>Supprimer un tag</h1>
        <p>Sélectionnez un tag à supprimer</p>
    </div>
</section>

<!-- ===== LISTE DES TAGS À SUPPRIMER ===== -->
<section class="admin-list-section">
    <div class="admin-grid">
        <?php foreach ($tags as $tag): ?>
            <div class="admin-card">
                <h3><?= htmlspecialchars($tag['name']); ?></h3>
                <p>Supprimer ce tag.</p>

                <!--
                    Le nom est transmis en champ caché vers supprimer_tag.php.
                    La classe .delete-confirm déclenche une confirmation JS
                    avant soumission (voir setupDeleteConfirmations() dans validation.js).
                -->
                <form action="supprimer_tag.php" method="POST">
                    <input name="name" value="<?= $tag['name']; ?>" hidden>
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
