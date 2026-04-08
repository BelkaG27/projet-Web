<?php
/**
 * Formulaire d'ajout d'un ingrédient — admin/ingredient_add.php
 *
 * Affiche un formulaire permettant à l'administrateur de créer un nouvel ingrédient
 * en renseignant son nom et en téléversant une image.
 * La soumission est traitée par ajouter_ingredient.php.
 *
 * Le nom peut être pré-rempli via le paramètre GET 'name' (utilisé depuis
 * le formulaire de recette quand l'ingrédient n'existe pas encore).
 */

// Vérification de l'authentification admin
require_once '../includes/auth.php';
require_once '../classes/Template.php';

// Initialisation du template avec basePath '../' car on est dans admin/
$template = new Template('Ajouter un ingrédient - Admin', '', '../');

// Pré-remplissage du champ nom si un paramètre GET est fourni (depuis la page recette)
$nameValue = $_GET['name'] ?? '';

// Début du tampon de sortie
ob_start();
?>

<!-- ===== BANNIÈRE DE LA PAGE ===== -->
<section class="page-hero small-hero">
    <div class="section-title">
        <h1>Ajouter un ingrédient</h1>
        <p>Créez un nouvel ingrédient</p>
    </div>
</section>

<!-- ===== FORMULAIRE D'AJOUT ===== -->
<section class="admin-form-section">
    <div class="search-card">
        <!--
            enctype="multipart/form-data" est obligatoire pour le téléversement de fichier.
            novalidate désactive la validation HTML5 native (gérée par validation.js).
            L'action pointe vers ajouter_ingredient.php qui traite la logique métier.
        -->
        <form class="search-form admin-simple-form" id="ingredientForm"
              method="POST" action="ajouter_ingredient.php"
              novalidate enctype="multipart/form-data">

            <div class="form-group">
                <label for="name">Nom de l'ingrédient</label>
                <input
                    type="text" id="name" name="name"
                    value="<?= htmlspecialchars($nameValue); ?>"
                    placeholder="Nom de l'ingrédient"
                >
                <!-- Emplacement pour le message d'erreur JS -->
                <small class="error-message"></small>

                <!-- Affichage d'une éventuelle erreur de session (ex : nom vide côté serveur) -->
                <?php if (isset($_SESSION['erreur']) && $_SESSION['erreur'] !== ''): ?>
                    <h1><?= $_SESSION['erreur']; ?></h1>
                <?php endif; ?>
            </div>

            <!-- Champ de téléversement de l'image de l'ingrédient -->
            <div class="form-group">
                <label for="image">Image</label>
                <input type="file" id="image" name="image">
                <small class="error-message"></small>
            </div>

            <button type="submit" class="btn-primary">Ajouter l'ingrédient</button>
        </form>
    </div>
</section>

<?php
// Récupération du HTML et rendu via le template commun
$content = ob_get_clean();
$template->render($content);
