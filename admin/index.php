<?php
require_once '../includes/auth.php';
require_once '../classes/Template.php';

$template = new Template(
    'Administration - Mon Livre de Recettes',
    'login',
    '../'
);



ob_start();
?>

<section class="page-hero small-hero">
    <div class="section-title">
        <h1>Espace administrateur</h1>
        <p>Gérez les recettes, ingrédients et tags</p>
    </div>
</section>

<section class="admin-dashboard">
    <div class="admin-grid">
        <a href="recipe_add.php" class="admin-card">
            <h3>Ajouter une recette</h3>
            <p>Créer une nouvelle recette.</p>
        </a>

        <a href="recipe_edit.php" class="admin-card">
            <h3>Modifier une recette</h3>
            <p>Mettre à jour une recette existante.</p>
        </a>

        <a href="recipe_delete.php" class="admin-card">
            <h3>Supprimer une recette</h3>
            <p>Retirer une recette du site.</p>
        </a>

        <a href="ingredient_add.php" class="admin-card">
            <h3>Ajouter un ingrédient</h3>
            <p>Créer un nouvel ingrédient.</p>
        </a>

        <a href="ingredient_edit.php" class="admin-card">
            <h3>Modifier un ingrédient</h3>
            <p>Mettre à jour un ingrédient existant.</p>
        </a>

        <a href="tag_add.php" class="admin-card">
            <h3>Ajouter un tag</h3>
            <p>Créer un nouveau tag.</p>
        </a>

        <a href="tag_edit.php" class="admin-card">
            <h3>Modifier un tag</h3>
            <p>Mettre à jour un tag existant.</p>
        </a>

        <a href="tag_delete.php" class="admin-card">
            <h3>Supprimer un tag</h3>
            <p>Retirer un tag de la base.</p>
        </a>

        <a href="../logout.php" class="admin-card">
            <h3>Déconnexion</h3>
            <p>Quitter l’espace administrateur.</p>
        </a>
    </div>
</section>

<?php
$content = ob_get_clean();
$template->render($content);