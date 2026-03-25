<?php
require_once '../includes/auth.php';
require_once '../classes/Template.php';

$template = new Template('Supprimer une recette - Admin', '', '../');

$recipes = [
    ['id' => 1, 'title' => 'Tarte aux pommes'],
    ['id' => 2, 'title' => 'Pizza maison'],
    ['id' => 3, 'title' => 'Salade fraîche'],
    ['id' => 4, 'title' => 'Gâteau au chocolat']
];

ob_start();
?>

<section class="page-hero small-hero">
    <div class="section-title">
        <h1>Supprimer une recette</h1>
        <p>Sélectionnez une recette à supprimer</p>
    </div>
</section>

<section class="admin-list-section">
    <div class="admin-grid">
        <?php foreach ($recipes as $recipe): ?>
            <div class="admin-card">
                <h3><?= htmlspecialchars($recipe['title']); ?></h3>
                <p>Supprimer cette recette.</p>
                <a href="#" class="btn-danger delete-confirm">Supprimer</a>
            </div>
        <?php endforeach; ?>
    </div>
</section>

<?php
$content = ob_get_clean();
$template->render($content);