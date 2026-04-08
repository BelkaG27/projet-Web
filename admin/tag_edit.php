<?php
/**
 * Sélection d'un tag à modifier — admin/tag_edit.php
 *
 * Affiche la liste de tous les tags existants sous forme de cartes.
 * Chaque carte contient un formulaire POST vers tag_edit_form.php
 * qui transmet le nom du tag sélectionné pour pré-remplir le formulaire d'édition.
 */

// Vérification de l'authentification admin
require_once '../includes/auth.php';
require_once '../classes/Template.php';
require_once '../classes/RecetteDB.php';

// Initialisation du template avec basePath '../' car on est dans admin/
$template = new Template('Modifier un tag - Admin', '', '../');

// Connexion à la base de données et récupération de tous les tags
$DB = new RecetteDB();
$tags = $DB->getTags();

// Début du tampon de sortie
ob_start();
?>

<!-- ===== BANNIÈRE DE LA PAGE ===== -->
<section class="page-hero small-hero">
    <div class="section-title">
        <h1>Modifier un tag</h1>
        <p>Sélectionnez un tag à modifier</p>
    </div>
</section>

<!-- ===== LISTE DES TAGS ===== -->
<section class="admin-list-section">
    <div class="admin-grid">
        <?php foreach ($tags as $tag): ?>
            <div class="admin-card">
                <h3><?= htmlspecialchars($tag['name']); ?></h3>
                <p>Modifier ce tag.</p>

                <!--
                    Le nom du tag est transmis en champ caché (POST) vers tag_edit_form.php
                    pour pré-remplir le formulaire d'édition et identifier le tag à modifier.
                -->
                <form action="tag_edit_form.php" method="POST">
                    <input name="name" value="<?= $tag['name']; ?>" hidden>
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
