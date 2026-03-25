<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle); ?></title>
    <link rel="stylesheet" href="<?= $basePath; ?>assets/css/style.css">
</head>
<body>

<header class="site-header">
    <div class="header-container">
        <div class="logo-area">
            <img src="<?= $basePath; ?>assets/images/logo.png" alt="Logo du site" class="logo">
            <span class="site-name">Mon Livre de Recettes</span>
        </div>

        <nav class="main-nav">
            <a href="<?= $basePath; ?>index.php" class="<?= ($currentPage === 'index') ? 'active' : ''; ?>">Accueil</a>
            <a href="<?= $basePath; ?>recipes.php" class="<?= ($currentPage === 'recipes') ? 'active' : ''; ?>">Recettes</a>
            <a href="<?= $basePath; ?>search.php" class="<?= ($currentPage === 'search') ? 'active' : ''; ?>">Recherche</a>
            <a href="<?= $basePath; ?>login.php" class="<?= ($currentPage === 'login') ? 'active' : ''; ?>">Admin</a>
        </nav>
    </div>
</header>