<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Titre dynamique injecté par la classe Template via $pageTitle -->
    <title><?= htmlspecialchars($pageTitle); ?></title>

    <!-- Feuille de style principale — $basePath permet de résoudre le chemin
         depuis n'importe quel sous-dossier (ex : admin/) -->
    <link rel="stylesheet" href="<?= $basePath; ?>assets/css/style.css">
</head>
<body>

<!-- ===== EN-TÊTE DU SITE ===== -->
<header class="site-header">
    <div class="header-container">

        <!-- Zone logo : image + nom du site -->
        <div class="logo-area">
            <img src="<?= $basePath; ?>img/logo.jpeg" style="width:120px;height:120px" alt="Logo du site" class="logo">
            <span class="site-name">Ratatouille</span>
        </div>

        <!-- Navigation principale
             La classe CSS 'active' est ajoutée au lien correspondant à la page courante
             grâce à la variable $currentPage transmise par Template -->
        <nav class="main-nav">
            <a href="<?= $basePath; ?>index.php"   class="<?= ($currentPage === 'index')   ? 'active' : ''; ?>">Accueil</a>
            <a href="<?= $basePath; ?>recipes.php" class="<?= ($currentPage === 'recipes') ? 'active' : ''; ?>">Recettes</a>
            <a href="<?= $basePath; ?>search.php"  class="<?= ($currentPage === 'search')  ? 'active' : ''; ?>">Recherche</a>
            <a href="<?= $basePath; ?>login.php"   class="<?= ($currentPage === 'login')   ? 'active' : ''; ?>">Admin</a>
        </nav>

    </div>
</header>
