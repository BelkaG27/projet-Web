<?php
require_once '../includes/auth.php';
require_once '../classes/Template.php';

$template = new Template('Modifier un ingrédient - Admin', '', '../');

$ingredients = [
    ['id' => 1, 'name' => 'Tomate'],
    ['id' => 2, 'name' => 'Fromage'],
    ['id' => 3, 'name' => 'Farine'],
    ['id' => 4, 'name' => 'Pomme']
];

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
                <a href="#" class="btn-secondary">Modifier</a>
            </div>
        <?php endforeach; ?>
    </div>
</section>

<?php
$content = ob_get_clean();
$template->render($content);