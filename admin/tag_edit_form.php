<?php
require_once '../includes/auth.php';
require_once '../classes/Template.php';

$template = new Template('Modifier un ingrédient - Admin', '', '../');

$nameValue = $_GET['name'] ?? '';


ob_start();
?>

<section class="page-hero small-hero">
    <div class="section-title">
        <h1>Modifier un tag</h1>
        <p>Modifier un tag éxistant</p>
    </div>
</section>

<section class="admin-form-section">
    <div class="search-card">
        <form class="search-form admin-simple-form" id="ingredientForm" method="POST" action="modifier_tag.php" novalidate enctype="multipart/form-data">
            <div class="form-group">
                <label for="name">Nom du tag</label>
                <input type="text" id="name" name="name" value="<?= htmlspecialchars($nameValue); ?>" placeholder="Nom du tag">
                <input name="old_name" value="<?php echo''.$_POST['name'].''?>" hidden>
                <small class="error-message"></small>
                <?php if(isset($_SESSION['erreur']) && $_SESSION['erreur']!=""){
                    echo'<h1>'.$_SESSION['erreur'].'</h1>';
                }?>
            </div>

            <button type="submit" class="btn-primary">Modifier le tag</button>
        </form>
    </div>
</section>

<?php
$content = ob_get_clean();
$template->render($content);
