<?php
require_once '../includes/auth.php';
require_once '../classes/Template.php';
require_once '../classes/RecetteDB.php';

$template = new Template('Modifier une recette - Admin', '', '../');
$DB = new RecetteDB();

$nameValue = $_GET['name'] ?? '';

$existingIngredients = $DB->getIngredients(); 
$existingTags = $DB->getTags();

ob_start();
?>




<section class="page-hero small-hero">
    <div class="section-title">
        <h1>Modifier une recette</h1>
        <p>Modifier une recette existante</p>
    </div>
</section>

<section class="admin-form-section">
    <div class="search-card">
        <form class="search-form admin-recipe-form" id="recipeForm" method="POST" action="modifier_recette.php" novalidate enctype="multipart/form-data">
            <div class="form-group">
                <label for="title">Titre de la recette</label>
                <input type="text" id="title" name="title" placeholder="Ex : Tarte aux pommes">
                <input name="old_name" value="<?php echo''.$_POST['name'].''?>" hidden>
                <small class="error-message"></small>
                <?php if(isset($_SESSION['erreur']) && $_SESSION['erreur']!=""){
                    echo'<h1>'.$_SESSION['erreur'].'</h1>';
                }?>
            </div>

            <div class="form-group">
                <label for="description">Description / préparation</label>
                <textarea id="description" name="description" rows="5" placeholder="Décrivez les étapes de préparation..."></textarea>
                <small class="error-message"></small>
                <?php if(isset($_SESSION['erreur']) && $_SESSION['erreur']!=""){
                    echo'<h1>'.$_SESSION['erreur'].'</h1>';
                }?>
            </div>

            <div class="form-group">
                <label for="photo">Photo de la recette</label>
                <input  type="file" id="photo" name="photo">
                <small class="error-message"></small>
            </div>

            <div class="admin-block">
                <h2>Ingrédients</h2>

                <div class="form-group">
                    <label for="ingredientSearch">Rechercher un ingrédient</label>
                    <input type="text" id="ingredientSearch" placeholder="Tapez pour chercher un ingrédient" autocomplete="off">
                    <select id="ingredientSelect" size="5" class="dropdown-list"></select>
                </div>

                <div class="selected-items" id="selectedIngredients"></div>
                <small class="error-message group-error" id="ingredientsGroupError"></small>
            </div>

            <div class="admin-block">
                <h2>Tags</h2>

                <div class="form-group">
                    <label for="tagSearch">Rechercher un tag</label>
                    <input type="text" id="tagSearch" placeholder="Tapez pour chercher un tag" autocomplete="off">
                    <select id="tagSelect" size="5" class="dropdown-list"></select>
                </div>

                <div class="selected-items" id="selectedTags"></div>
            </div>

            <button type="submit" class="btn-primary">Modidifer la recette</button>
        </form>
    </div>
</section>

<script>
    window.recipeFormData = {ingredients: <?= json_encode($existingIngredients, JSON_UNESCAPED_UNICODE); ?>,tags: <?= json_encode($existingTags, JSON_UNESCAPED_UNICODE); ?>};
</script>

<?php
$content = ob_get_clean();
$template->render($content);
