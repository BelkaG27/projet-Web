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
 */

// Vérification de l'authentification admin
require_once '../includes/auth.php';
require_once '../classes/Template.php';

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
        
        <form class="search-form admin-simple-form" id="tagForm"
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
