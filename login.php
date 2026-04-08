<?php
/**
 * Page de connexion administrateur — login.php
 *
 * Affiche un formulaire de connexion et vérifie les identifiants soumis.
 * Si l'utilisateur est déjà connecté (session active), il est redirigé
 * directement vers l'espace admin.
 * En cas de succès, la session est marquée comme admin et une redirection
 * vers admin/index.php est effectuée.
 *
 * ⚠️  Les identifiants sont actuellement codés en dur — à remplacer
 *     par une vérification en base de données pour un usage en production.
 */

session_start();
require_once 'classes/Template.php';

// Si l'utilisateur est déjà authentifié, inutile d'afficher le formulaire
if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] === true) {
    header('Location: admin/index.php');
    exit;
}

$error      = '';   // Message d'erreur à afficher sous le formulaire
$loginValue = '';   // Valeur conservée dans le champ login après une tentative échouée

// Traitement du formulaire soumis en POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $loginValue = trim($_POST['login']    ?? '');
    $password   = trim($_POST['password'] ?? '');

    // Identifiants administrateur attendus (à sécuriser en production)
    $adminLogin    = 'admin';
    $adminPassword = 'admin123';

    if ($loginValue === '' || $password === '') {
        // Cas 1 : un ou plusieurs champs sont vides
        $error = 'Veuillez remplir tous les champs.';

    } elseif ($loginValue === $adminLogin && $password === $adminPassword) {
        // Cas 2 : identifiants corrects — ouverture de la session admin
        $_SESSION['is_admin']    = true;
        $_SESSION['admin_login'] = $loginValue;

        header('Location: admin/index.php');
        exit;

    } else {
        // Cas 3 : identifiants incorrects
        $error = 'Identifiants incorrects.';
    }
}

// Initialisation du template (titre de l'onglet + lien actif "Admin" dans le menu)
$template = new Template('Connexion administrateur - Ratatouille', 'login');

// Début du tampon de sortie
ob_start();
?>

<!-- ===== BANNIÈRE DE LA PAGE ===== -->
<section class="page-hero small-hero">
    <div class="section-title">
        <h1>Connexion administrateur</h1>
        <p>Accédez à l'espace d'administration du site</p>
    </div>
</section>

<!-- ===== FORMULAIRE DE CONNEXION ===== -->
<section class="login-section">
    <div class="login-card">

        <!-- Affichage conditionnel du message d'erreur -->
        <?php if ($error !== ''): ?>
            <div class="form-error">
                <p><?= htmlspecialchars($error); ?></p>
            </div>
        <?php endif; ?>

        <!-- novalidate : la validation HTML5 native est désactivée au profit de validation.js -->
        <form action="login.php" method="POST" class="login-form" novalidate>
            <div class="form-group">
                <label for="login">Login</label>
                <input
                    type="text"
                    id="login"
                    name="login"
                    value="<?= htmlspecialchars($loginValue); ?>"
                    placeholder="Entrez votre login"
                >
                <!-- Emplacement réservé au message d'erreur JS pour ce champ -->
                <small class="error-message"></small>
            </div>

            <div class="form-group">
                <label for="password">Mot de passe</label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    placeholder="Entrez votre mot de passe"
                >
                <small class="error-message"></small>
            </div>

            <button type="submit" class="btn-primary login-btn">Se connecter</button>
        </form>
    </div>
</section>

<?php
// Récupération du HTML et rendu via le template commun
$content = ob_get_clean();
$template->render($content);
