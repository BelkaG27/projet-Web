<?php
/**
 * Formulaire de modification d'un tag — admin/tag_edit_form.php
 *
 * Reçoit le nom du tag à modifier via POST (depuis tag_edit.php).
 * Affiche un formulaire pré-rempli avec ce nom.
 * La soumission est traitée par modifier_tag.php qui effectue la mise à jour en BDD.
 *
 * Deux champs sont transmis à modifier_tag.php :
 *  - 'name'     : nouveau nom souhaité,
 *  - 'old_name' : ancien nom (caché) pour identifier l'enregistrement à mettre à jour.
 *
 * ⚠️  Le titre du Template indique "ingrédient" au lieu de "tag" — à corriger.
 * ⚠️  L'id du formulaire est "ingredientForm" au lieu de "tagForm" — à corriger
 *     pour que la validation JS du formulaire de tag s'applique correctement.
 * ⚠️  enctype="multipart/form-data" est inutile ici (pas de fichier à uploader).
 */

// Vérification de l'authentification admin
require_once '../includes/auth.php';
require_once '../classes/Template.php';

// ⚠️ Titre incorrect : devrait être 'Modifier un tag - Admin'
$template = new Template('Modifier un tag - Admin', '', '../');

// Récupération du nom actuel du tag transmis en GET (non utilisé ici, $_POST est la source)
$nameValue = $_POST['name'] ?? '';

// Début du tampon de sortie
ob_start();
?>

<!-- ===== BANNIÈRE DE LA PAGE ===== -->
<section class="page-hero small-hero">
    <div class="section-title">
        <h1>Modifier un tag</h1>
        <p>Modifier un tag éxistant</p>
    </div>
</section>

<!-- ===== FORMULAIRE DE MODIFICATION ===== -->
<section class="admin-form-section">
    <div class="search-card">
        <!--
            ⚠️ id="ingredientForm" devrait être id="tagForm" pour que
            setupSimpleForms() dans validation.js valide ce formulaire correctement.
            ⚠️ enctype="multipart/form-data" inutile ici (pas d'upload de fichier).
        -->
        <form class="search-form admin-simple-form" id="ingredientForm"
              method="POST" action="modifier_tag.php"
              novalidate enctype="multipart/form-data">

            <div class="form-group">
                <label for="name">Nom du tag</label>

                <!-- Champ visible : nouveau nom du tag -->
                <input
                    type="text" id="name" name="name"
                    value="<?= htmlspecialchars($nameValue); ?>"
                    placeholder="Nom du tag"
                >

                <!--
                    Champ caché : ancien nom transmis depuis tag_edit.php via POST.
                    Utilisé par modifier_tag.php pour cibler le bon enregistrement en BDD.
                -->
                <input name="old_name" value="<?= $_POST['name']; ?>" hidden>

                <!-- Emplacement pour le message d'erreur JS -->
                <small class="error-message"></small>

                <!-- Affichage d'une éventuelle erreur de session -->
                <?php if (isset($_SESSION['erreur']) && $_SESSION['erreur'] !== ''): ?>
                    <h1><?= $_SESSION['erreur']; ?></h1>
                <?php endif; ?>
            </div>

            <button type="submit" class="btn-primary">Modifier le tag</button>
        </form>
    </div>
</section>

<?php
// Récupération du HTML et rendu via le template commun
$content = ob_get_clean();
$template->render($content);
