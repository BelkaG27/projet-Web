<?php
require_once '../includes/auth.php';
require_once '../classes/Template.php';

$template = new Template('Ajouter une recette - Admin', '', '../');

$existingIngredients = [
['id' => 1, 'name' => 'Tomate'],
['id' => 2, 'name' => 'Fromage'],
['id' => 3, 'name' => 'Farine'],
['id' => 4, 'name' => 'Pomme'],
['id' => 5, 'name' => 'Chocolat'],
['id' => 6, 'name' => 'Sucre'],
['id' => 7, 'name' => 'Oeuf'],
['id' => 8, 'name' => 'Beurre']
];

$existingTags = [
['id' => 1, 'name' => 'dessert'],
['id' => 2, 'name' => 'rapide'],
['id' => 3, 'name' => 'hiver'],
['id' => 4, 'name' => 'léger'],
['id' => 5, 'name' => 'four'],
['id' => 6, 'name' => 'été'],
['id' => 7, 'name' => 'gourmand']
];

ob_start();
?>

<section class="page-hero small-hero">
<div class="section-title">
<h1>Ajouter une recette</h1>
<p>Créez une nouvelle recette</p>
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
<h2>Ingrédients</h2>

<div class="form-group">
<label for="ingredientSearch">Rechercher un ingrédient</label>
<input
type="text"
id="ingredientSearch"
placeholder="Tapez pour chercher un ingrédient"
autocomplete="off"
>
<select id="ingredientSelect" size="5" class="dropdown-list"></select>
</div>

<div class="selected-items" id="selectedIngredients"></div>
<small class="error-message group-error" id="ingredientsGroupError"></small>
</div>

<div class="admin-block">
<h2>Tags</h2>

<div class="form-group">
<label for="tagSearch">Rechercher un tag</label>
<input
type="text"
id="tagSearch"
placeholder="Tapez pour chercher un tag"
autocomplete="off"
>
<select id="tagSelect" size="5" class="dropdown-list"></select>
</div>

<div class="selected-items" id="selectedTags"></div>
</div>

<button type="submit" class="btn-primary">Ajouter la recette</button>
</form>
</div>
</section>

<script>
window.recipeFormData = {
ingredients: <?= json_encode($existingIngredients, JSON_UNESCAPED_UNICODE); ?>,
tags: <?= json_encode($existingTags, JSON_UNESCAPED_UNICODE); ?>
};
</script>

<?php
$content = ob_get_clean();
$template->render($content);