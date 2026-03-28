<?php
require_once '../includes/auth.php';
require_once '../classes/Template.php';
require_once '../classes/RecetteDB.php';

$template = new Template('Modifier une recette - Admin', '', '../');
$DB = new RecetteDB();

$recipes = $DB->getRecettes();

ob_start();
?>

<section class="page-hero small-hero">
    <div class="section-title">
        <h1>Modifier une recette</h1>
        <p>Sélectionnez une recette à modifier</p>
    </div>
</section>

<section class="admin-list-section">
    <div class="admin-grid">
        <?php foreach ($recipes as $recipe): ?>
            <div class="admin-card">
                <h3><?= htmlspecialchars($recipe['title']); ?></h3>
                <p>Modifier cette recette.</p>
                <form action="recipe_edit_form.php" method="POST">
                    <input name="name" value="<?php echo''.$recipe['title'].''?>" hidden>
                    <button type="submit" class="btn-secondary">Modifier</button>   
                </form>
            </div>
        <?php endforeach; ?>
    </div>
</section>

<?php
$content = ob_get_clean();
$template->render($content);