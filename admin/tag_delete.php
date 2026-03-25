<?php
require_once '../includes/auth.php';
require_once '../classes/Template.php';

$template = new Template('Supprimer un tag - Admin', '', '../');

$tags = [
    ['id' => 1, 'name' => 'dessert'],
    ['id' => 2, 'name' => 'rapide'],
    ['id' => 3, 'name' => 'léger'],
    ['id' => 4, 'name' => 'hiver']
];

ob_start();
?>

<section class="page-hero small-hero">
    <div class="section-title">
        <h1>Supprimer un tag</h1>
        <p>Sélectionnez un tag à supprimer</p>
    </div>
</section>

<section class="admin-list-section">
    <div class="admin-grid">
        <?php foreach ($tags as $tag): ?>
            <div class="admin-card">
                <h3><?= htmlspecialchars($tag['name']); ?></h3>
                <p>Supprimer ce tag.</p>
                <a href="#" class="btn-danger delete-confirm">Supprimer</a>
            </div>
        <?php endforeach; ?>
    </div>
</section>

<?php
$content = ob_get_clean();
$template->render($content);