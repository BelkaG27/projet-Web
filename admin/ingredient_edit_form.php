<?php
require_once '../includes/auth.php';
require_once '../classes/Template.php';

$template = new Template('Modifier un ingrédient - Admin', '', '../');

$nameValue = $_GET['name'] ?? '';


ob_start();
?>

<section class="page-hero small-hero">
    <div class="section-title">
        <h1>Modifier un ingrédient</h1>
        <p>Modifier un ingrédient éxistant</p>
    </div>
</section>

<section class="admin-form-section">
    <div class="search-card">
        <form class="search-form admin-simple-form" id="ingredientForm" method="POST" action="modifier_ingredient.php" novalidate enctype="multipart/form-data">
            <div class="form-group">
                <label for="name">Nom de l’ingrédient</label>
                <input type="text" id="name" name="name" value="<?= htmlspecialchars($nameValue); ?>" placeholder="Nom de l’ingrédient">
                <input name="old_name" value="<?php echo''.$_POST['name'].''?>" hidden>
                <small class="error-message"></small>
                <?php if(isset($_SESSION['erreur']) && $_SESSION['erreur']!=""){
                    echo'<h1>'.$_SESSION['erreur'].'</h1>';
                }?>
            </div>

            <div class="form-group">
            <label for="image">Image</label>
            <input  type="file" id="image" name="image">
            <small class="error-message"></small>
            </div>

            <button type="submit" class="btn-primary">Modifier l’ingrédient</button>
        </form>
    </div>
</section>

<?php
$content = ob_get_clean();
$template->render($content);
