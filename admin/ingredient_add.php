<?php
require_once '../includes/auth.php';
require_once '../classes/Template.php';

$template = new Template('Ajouter un ingrédient - Admin', '', '../');

$nameValue = $_GET['name'] ?? '';

ob_start();
?>

<section class="page-hero small-hero">
<div class="section-title">
<h1>Ajouter un ingrédient</h1>
<p>Créez un nouvel ingrédient</p>
</div>
</section>

<section class="admin-form-section">
<div class="search-card">
<form class="search-form admin-simple-form" id="ingredientForm" method="POST" action="" novalidate>
<div class="form-group">
<label for="name">Nom de l’ingrédient</label>
<input
type="text"
id="name"
name="name"
value="<?= htmlspecialchars($nameValue); ?>"
placeholder="Nom de l’ingrédient"
>
<small class="error-message"></small>
</div>

<div class="form-group">
<label for="image">Image</label>
<input
type="text"
id="image"
name="image"
placeholder="Chemin de l’image"
>
<small class="error-message"></small>
</div>

<button type="submit" class="btn-primary">Ajouter l’ingrédient</button>
</form>
</div>
</section>

<?php
$content = ob_get_clean();
$template->render($content);
