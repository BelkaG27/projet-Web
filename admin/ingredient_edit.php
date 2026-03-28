<?php
require_once '../includes/auth.php';
require_once '../classes/Template.php';
require_once '../classes/RecetteDB.php';

$template = new Template('Modifier un ingrédient - Admin', '', '../');
$DB = new recetteDB();

$ingredients = $DB->getIngredients();

ob_start();
?>

<section class="page-hero small-hero">
    <div class="section-title">
        <h1>Modifier un ingrédient</h1>
        <p>Sélectionnez un ingrédient à modifier</p>
    </div>
</section>

<section class="admin-list-section">
    <div class="admin-grid">
        <?php foreach ($ingredients as $ingredient): ?>
            <div class="admin-card">
                <h3><?= htmlspecialchars($ingredient['name']); ?></h3>
                <p>Modifier cet ingrédient.</p>
                <form action="ingredient_edit_form.php" method="POST">
                    <input name="name" value="<?php echo''.$ingredient['name'].''?>" hidden>
                    <button type="submit" class="btn-secondary">Modifier</button>   
                </form>
                
            </div>
        <?php endforeach; ?>
    </div>
</section>

<?php
$content = ob_get_clean();
$template->render($content);