<?php
/**
 * Formulaire de modification d'un ingrédient — admin/ingredient_edit_form.php
 *
 * Reçoit le nom de l'ingrédient à modifier via POST (depuis ingredient_edit.php).
 * Affiche un formulaire pré-rempli avec ce nom et permet de téléverser une nouvelle image.
 * La soumission est traitée par modifier_ingredient.php qui effectue la mise à jour en BDD.
 *
 * Deux champs sont transmis à modifier_ingredient.php :
 *  - 'name'     : nouveau nom souhaité,
 *  - 'old_name' : ancien nom (caché) pour identifier l'enregistrement à mettre à jour.
 */

// Vérification de l'authentification admin
require_once '../includes/auth.php';
require_once '../classes/Template.php';

// Initialisation du template avec basePath '../' car on est dans admin/
$template = new Template('Modifier un ingrédient - Admin', '', '../');

// Récupération du nom actuel de l'ingrédient passé en GET (non utilisé ici, $_POST est la source)
$nameValue = $_POST['name'] ?? '';

// Début du tampon de sortie
ob_start();
?>

<!-- ===== BANNIÈRE DE LA PAGE ===== -->
<section class="page-hero small-hero">
    <div class="section-title">
        <h1>Modifier un ingrédient</h1>
        <p>Modifier un ingrédient éxistant</p>
    </div>
</section>

<!-- ===== FORMULAIRE DE MODIFICATION ===== -->
<section class="admin-form-section">
    <div class="search-card">
        <!--
            enctype="multipart/form-data" est obligatoire pour le téléversement de la nouvelle image.
            novalidate désactive la validation HTML5 native (gérée par validation.js).
        -->
        <form class="search-form admin-simple-form" id="ingredientForm"
              method="POST" action="modifier_ingredient.php"
              novalidate enctype="multipart/form-data">

            <div class="form-group">
                <label for="name">Nom de l'ingrédient</label>
                <!-- Champ visible : nouveau nom (initialisé avec la valeur actuelle) -->
                <input
                    type="text" id="name" name="name"
                    value="<?= htmlspecialchars($nameValue); ?>"
                    placeholder="Nom de l'ingrédient"
                >
                <!--
                    Champ caché : ancien nom transmis depuis ingredient_edit.php via POST.
                    Utilisé par modifier_ingredient.php pour cibler le bon enregistrement en BDD.
                -->
                <input name="old_name" value="<?= $_POST['name']; ?>" hidden>

                <!-- Emplacement pour le message d'erreur JS -->
                <small class="error-message"></small>

                <!-- Affichage d'une éventuelle erreur de session (ex : nom vide côté serveur) -->
                <?php if (isset($_SESSION['erreur']) && $_SESSION['erreur'] !== ''): ?>
                    <h1><?= $_SESSION['erreur']; ?></h1>
                <?php endif; ?>
            </div>

            <!-- Champ de téléversement de la nouvelle image -->
            <div class="form-group">
                <label for="image">Image</label>
                <input type="file" id="image" name="image">
                <small class="error-message"></small>
            </div>

            <button type="submit" class="btn-primary">Modifier l'ingrédient</button>
        </form>
    </div>
</section>

<?php
// Récupération du HTML et rendu via le template commun
$content = ob_get_clean();
$template->render($content);
