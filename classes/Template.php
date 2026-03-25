<?php

class Template
{
    private string $title;
    private string $currentPage;
    private string $basePath;

    public function __construct(
        string $title = 'Mon Livre de Recettes',
        string $currentPage = '',
        string $basePath = ''
    ) {
        $this->title = $title;
        $this->currentPage = $currentPage;
        $this->basePath = $basePath;
    }

    public function render(string $content): void
    {
        $pageTitle = $this->title;
        $currentPage = $this->currentPage;
        $basePath = $this->basePath;

        include __DIR__ . '/../includes/header.php';
        ?>
        <main>
            <?= $content; ?>
        </main>
        <?php
        include __DIR__ . '/../includes/footer.php';
    }
}