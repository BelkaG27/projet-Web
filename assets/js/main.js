/**
 * main.js — Interactions de la page d'accueil
 *
 * Gère le défilement horizontal du slider de tags présent sur index.php.
 * Les boutons gauche/droite font défiler le conteneur #tagsSlider
 * par pas de 250 px avec une animation fluide (smooth scroll).
 */

document.addEventListener("DOMContentLoaded", function () {
    // Récupération des éléments du slider
    const slider   = document.getElementById("tagsSlider");
    const leftBtn  = document.querySelector(".scroll-btn.left");
    const rightBtn = document.querySelector(".scroll-btn.right");

    // On n'initialise les écouteurs que si tous les éléments sont présents
    // (le slider n'existe que sur la page d'accueil)
    if (slider && leftBtn && rightBtn) {

        // Défilement vers la gauche au clic sur le bouton "<"
        leftBtn.addEventListener("click", function () {
            slider.scrollBy({
                left: -250,       // Nombre de pixels à défiler
                behavior: "smooth" // Animation fluide
            });
        });

        // Défilement vers la droite au clic sur le bouton ">"
        rightBtn.addEventListener("click", function () {
            slider.scrollBy({
                left: 250,
                behavior: "smooth"
            });
        });
    }
});
