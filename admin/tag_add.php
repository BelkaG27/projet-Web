<?php
require_once '../includes/auth.php';
require_once '../classes/Template.php';

$template = new Template('Ajouter un tag - Admin', '', '../');

$nameValue = $_GET['name'] ?? '';

ob_start();
?>

<section class="page-hero small-hero">
<div class="section-title">
<h1>Ajouter un tag</h1>
<p>Créez un nouveau tag</p>
</div>
</section>

<section class="admin-form-section">
<div class="search-card">
<form class="search-form admin-simple-form" id="tagForm" method="POST" action="" novalidate>
<div class="form-group">
<label for="name">Nom du tag</label>
<input
type="text"
id="name"
name="name"
value="<?= htmlspecialchars($nameValue); ?>"
placeholder="Nom du tag"
>
<small class="error-message"></small>
</div>

<button type="submit" class="btn-primary">Ajouter le tag</button>
</form>
</div>
</section>

<?php
$content = ob_get_clean();
$template->render($content);