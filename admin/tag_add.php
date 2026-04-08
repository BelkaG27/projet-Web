<?php
/**
 * Formulaire d'ajout d'un tag — admin/tag_add.php
 *
 * Affiche un formulaire permettant à l'administrateur de créer un nouveau tag
 * en renseignant simplement son nom.
 * La soumission est traitée par ajouter_tag.php.
 *
 * Le nom peut être pré-rempli via le paramètre GET 'name' (utilisé depuis
 * le formulaire de recette quand le tag n'existe pas encore).
 */

// Vérification de l'authentification admin
require_once '../includes/auth.php';
require_once '../classes/Template.php';

// Initialisation du template avec basePath '../' car on est dans admin/
$template = new Template('Ajouter un tag - Admin', '', '../');

// Pré-remplissage du champ nom si un paramètre GET est fourni
$nameValue = $_GET['name'] ?? '';

// Début du tampon de sortie
ob_start();
?>

<!-- ===== BANNIÈRE DE LA PAGE ===== -->
<section class="page-hero small-hero">
    <div class="section-title">
        <h1>Ajouter un tag</h1>
        <p>Créez un nouveau tag</p>
    </div>
</section>

<!-- ===== FORMULAIRE D'AJOUT ===== -->
<section class="admin-form-section">
    <div class="search-card">
        <!--
            novalidate désactive la validation HTML5 native (gérée par validation.js).
            L'action pointe vers ajouter_tag.php qui traite la logique métier.
        -->
        <form class="search-form admin-simple-form" id="tagForm"
              method="POST" action="ajouter_tag.php" novalidate>

            <div class="form-group">
                <label for="name">Nom du tag</label>
                <input
                    type="text" id="name" name="name"
                    value="<?= htmlspecialchars($nameValue); ?>"
                    placeholder="Nom du tag"
                >
                <!-- Emplacement pour le message d'erreur JS -->
                <small class="error-message"></small>

                <!-- Affichage d'une éventuelle erreur de session (ex : nom vide côté serveur) -->
                <?php if (isset($_SESSION['erreur']) && $_SESSION['erreur'] !== ''): ?>
                    <h1><?= $_SESSION['erreur']; ?></h1>
                <?php endif; ?>
            </div>

            <button type="submit" class="btn-primary">Ajouter le tag</button>
        </form>
    </div>
</section>

<?php
// Récupération du HTML et rendu via le template commun
$content = ob_get_clean();
$template->render($content);
