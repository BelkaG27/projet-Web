<?php
/**
 * Tableau de bord de l'administration — admin/index.php
 *
 * Page d'accueil de l'espace admin. Accessible uniquement après authentification
 * (vérifiée par auth.php). Présente des liens rapides vers toutes les actions
 * d'administration : recettes, ingrédients, tags et déconnexion.
 */

// Vérification de l'authentification admin — redirige vers login.php si non connecté
require_once '../includes/auth.php';
require_once '../classes/Template.php';

// Le basePath '../' est nécessaire car ce fichier est dans le sous-dossier admin/
$template = new Template(
    'Administration - Mon Livre de Recettes',
    '',      // Aucune page du menu public n'est "active" ici
    '../'    // Chemin de base pour les assets et les liens vers la racine
);

// Début du tampon de sortie
ob_start();
?>

<!-- ===== BANNIÈRE DE LA PAGE ===== -->
<section class="page-hero small-hero">
    <div class="section-title">
        <h1>Espace administrateur</h1>
        <p>Gérez les recettes, ingrédients et tags</p>
    </div>
</section>

<!-- ===== GRILLE DES ACTIONS ADMIN ===== -->
<section class="admin-dashboard">
    <div class="admin-grid">

        <!-- Actions sur les recettes -->
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

        <!-- Actions sur les ingrédients -->
        <a href="ingredient_add.php" class="admin-card">
            <h3>Ajouter un ingrédient</h3>
            <p>Créer un nouvel ingrédient.</p>
        </a>

        <a href="ingredient_edit.php" class="admin-card">
            <h3>Modifier un ingrédient</h3>
            <p>Mettre à jour un ingrédient existant.</p>
        </a>

        <!-- Actions sur les tags -->
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

        <!-- Déconnexion : détruit la session et redirige vers l'accueil public -->
        <a href="../logout.php" class="admin-card">
            <h3>Déconnexion</h3>
            <p>Quitter l'espace administrateur.</p>
        </a>

    </div>
</section>

<?php
// Récupération du HTML et rendu via le template commun
$content = ob_get_clean();
$template->render($content);
