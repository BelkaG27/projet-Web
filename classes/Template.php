<?php

/**
 * Classe Template
 *
 * Gère le rendu des pages HTML en encapsulant le contenu principal
 * entre un en-tête (header.php) et un pied de page (footer.php).
 * Utilisée sur toutes les pages du site pour garantir une mise en page cohérente.
 */
class Template
{
    /** @var string Titre affiché dans l'onglet du navigateur (<title>) */
    private string $title;

    /** @var string Identifiant de la page courante, utilisé pour marquer le lien actif dans la navigation */
    private string $currentPage;

    /** @var string Chemin de base relatif vers la racine du site (ex : '../' depuis un sous-dossier) */
    private string $basePath;

    /**
     * Constructeur
     *
     * @param string $title       Titre de la page (défaut : 'Ratatouille')
     * @param string $currentPage Page active pour la navigation (défaut : '')
     * @param string $basePath    Préfixe des chemins vers les assets/pages (défaut : '')
     */
    public function __construct(
        string $title = 'Ratatouille',
        string $currentPage = '',
        string $basePath = ''
    ) {
        $this->title = $title;
        $this->currentPage = $currentPage;
        $this->basePath = $basePath;
    }

    /**
     * Affiche la page complète
     *
     * Inclut header.php, injecte le contenu HTML dans une balise <main>,
     * puis inclut footer.php. Les variables $pageTitle, $currentPage et
     * $basePath sont rendues disponibles dans les fichiers inclus.
     *
     * @param string $content Contenu HTML de la page (généré via ob_start/ob_get_clean)
     */
    public function render(string $content): void
    {
        // Rend les propriétés accessibles dans header.php et footer.php
        $pageTitle = $this->title;
        $currentPage = $this->currentPage;
        $basePath = $this->basePath;

        // Inclusion de l'en-tête commun (doctype, <head>, barre de navigation)
        include __DIR__ . '/../includes/header.php';
        ?>
        <main>
            <?= $content; ?>
        </main>
        <?php
        // Inclusion du pied de page commun (copyright, scripts JS)
        include __DIR__ . '/../includes/footer.php';
    }
}
