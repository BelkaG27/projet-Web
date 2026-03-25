<?php
session_start();
require_once 'classes/Template.php';

if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] === true) {
    header('Location: admin/index.php');
    exit;
}

$error = '';
$loginValue = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $loginValue = trim($_POST['login'] ?? '');
    $password = trim($_POST['password'] ?? '');

    $adminLogin = 'admin';
    $adminPassword = 'admin123';

    if ($loginValue === '' || $password === '') {
        $error = 'Veuillez remplir tous les champs.';
    } elseif ($loginValue === $adminLogin && $password === $adminPassword) {
        $_SESSION['is_admin'] = true;
        $_SESSION['admin_login'] = $loginValue;

        header('Location: admin/index.php');
        exit;
    } else {
        $error = 'Identifiants incorrects.';
    }
}

$template = new Template('Connexion administrateur - Mon Livre de Recettes', 'login');

ob_start();
?>

<section class="page-hero small-hero">
    <div class="section-title">
        <h1>Connexion administrateur</h1>
        <p>Accédez à l’espace d’administration du site</p>
    </div>
</section>

<section class="login-section">
    <div class="login-card">

        <?php if ($error !== ''): ?>
            <div class="form-error">
                <p><?= htmlspecialchars($error); ?></p>
            </div>
        <?php endif; ?>

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
$content = ob_get_clean();
$template->render($content);