<?php
require_once '../includes/auth.php';
require_once '../classes/Template.php';
require_once '../classes/RecetteDB.php';

$template = new Template('Modifier un tag - Admin', '', '../');

$DB = new RecetteDB();

$tags = $DB->getTags();

ob_start();
?>

<section class="page-hero small-hero">
    <div class="section-title">
        <h1>Modifier un tag</h1>
        <p>Sélectionnez un tag à modifier</p>
    </div>
</section>

<section class="admin-list-section">
    <div class="admin-grid">
        <?php foreach ($tags as $tag): ?>
            <div class="admin-card">
                <h3><?= htmlspecialchars($tag['name']); ?></h3>
                <p>Modifier ce tag.</p>
                <form action="tag_edit_form.php" method="POST">
                    <input name="name" value="<?php echo''.$tag['name'].''?>" hidden>
                    <button type="submit" class="btn-secondary">Modifier</button>   
                </form>
            </div>
        <?php endforeach; ?>
    </div>
</section>

<?php
$content = ob_get_clean();
$template->render($content);