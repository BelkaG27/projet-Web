<?php
require_once '../includes/auth.php';
require_once '../classes/Template.php';

$template = new Template('Ajouter une recette - Admin', '', '../');

$existingIngredients = [
    ['id' => 1, 'name' => 'Tomate'],
    ['id' => 2, 'name' => 'Fromage'],
    ['id' => 3, 'name' => 'Farine'],
    ['id' => 4, 'name' => 'Pomme'],
    ['id' => 5, 'name' => 'Chocolat']
];

$existingTags = [
    ['id' => 1, 'name' => 'dessert'],
    ['id' => 2, 'name' => 'rapide'],
    ['id' => 3, 'name' => 'hiver'],
    ['id' => 4, 'name' => 'léger'],
    ['id' => 5, 'name' => 'four']
];

ob_start();
?>

<section class="page-hero small-hero">
    <div class="section-title">
        <h1>Ajouter une recette</h1>
        <p>Créez une nouvelle recette avec ingrédients et tags</p>
    </div>
</section>

<section class="admin-form-section">
    <div class="search-card">
        <form class="search-form admin-recipe-form" id="recipeForm" method="POST" action="" novalidate>
            <div class="form-group">
                <label for="title">Titre de la recette</label>
                <input type="text" id="title" name="title" placeholder="Ex : Tarte aux pommes">
                <small class="error-message"></small>
            </div>

            <div class="form-group">
                <label for="description">Description / préparation</label>
                <textarea id="description" name="description" rows="5" placeholder="Décrivez les étapes de préparation..."></textarea>
                <small class="error-message"></small>
            </div>

            <div class="form-group">
                <label for="photo">Photo de la recette</label>
                <input type="text" id="photo" name="photo" placeholder="Ex : ../assets/images/recipe7.jpg">
                <small class="error-message"></small>
            </div>

            <div class="admin-block">
                <h2>Ingrédients existants</h2>
                <div class="checkbox-grid">
                    <?php foreach ($existingIngredients as $ingredient): ?>
                        <label class="checkbox-card">
                            <input type="checkbox" name="existing_ingredients[]" value="<?= htmlspecialchars($ingredient['id']); ?>">
                            <span><?= htmlspecialchars($ingredient['name']); ?></span>
                        </label>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="admin-block">
                <div class="admin-block-header">
                    <h2>Nouveaux ingrédients</h2>
                    <button type="button" class="btn-secondary small-btn" id="addIngredientBtn">Ajouter un ingrédient</button>
                </div>

                <div id="newIngredientsContainer">
                    <div class="dynamic-row ingredient-row">
                        <input type="text" name="new_ingredients[]" placeholder="Nom du nouvel ingrédient">
                        <button type="button" class="btn-danger remove-row-btn">Supprimer</button>
                    </div>
                </div>
                <small class="error-message group-error" id="ingredientsGroupError"></small>
            </div>

            <div class="admin-block">
                <h2>Tags existants</h2>
                <div class="checkbox-grid">
                    <?php foreach ($existingTags as $tag): ?>
                        <label class="checkbox-card">
                            <input type="checkbox" name="existing_tags[]" value="<?= htmlspecialchars($tag['id']); ?>">
                            <span><?= htmlspecialchars($tag['name']); ?></span>
                        </label>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="admin-block">
                <div class="admin-block-header">
                    <h2>Nouveaux tags</h2>
                    <button type="button" class="btn-secondary small-btn" id="addTagBtn">Ajouter un tag</button>
                </div>

                <div id="newTagsContainer">
                    <div class="dynamic-row tag-row">
                        <input type="text" name="new_tags[]" placeholder="Nom du nouveau tag">
                        <button type="button" class="btn-danger remove-row-btn">Supprimer</button>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn-primary">Ajouter la recette</button>
        </form>
    </div>
</section>

<?php
$content = ob_get_clean();
$template->render($content);