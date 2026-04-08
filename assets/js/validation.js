/**
 * validation.js — Validation côté client et interactions des formulaires
 *
 * Ce script s'occupe de trois responsabilités distinctes :
 *  1. setupRecipeFormDropdown() : gestion du formulaire de recette avec
 *     sélection dynamique d'ingrédients et de tags via des listes déroulantes filtrables.
 *  2. setupSimpleForms()        : validation basique des formulaires d'ingrédient,
 *     de tag et de connexion administrateur.
 *  3. setupDeleteConfirmations(): demande de confirmation avant toute suppression.
 *
 * Point d'entrée : DOMContentLoaded (exécuté une fois le DOM entièrement chargé).
 */

document.addEventListener("DOMContentLoaded", function () {
    setupRecipeFormDropdown();
    setupSimpleForms();
    setupDeleteConfirmations();
});

/* ==========================================================================
   1. FORMULAIRE DE RECETTE — Sélection dynamique d'ingrédients et de tags
   ========================================================================== */

/**
 * Initialise le formulaire de création/modification de recette (#recipeForm).
 *
 * Fonctionnement :
 *  - L'utilisateur tape dans un champ de recherche (#ingredientSearch ou #tagSearch).
 *  - Une liste déroulante filtre en temps réel les ingrédients/tags existants
 *    (chargés depuis window.recipeFormData injecté par PHP).
 *  - La sélection ajoute un badge visuel et un <input hidden> au formulaire.
 *  - Si aucun résultat ne correspond, une option "Créer X" redirige vers la page de création.
 */
function setupRecipeFormDropdown() {
    const recipeForm = document.getElementById("recipeForm");

    // Ce formulaire n'existe que sur la page d'ajout/modification de recette
    if (!recipeForm || !window.recipeFormData) return;

    // --- Récupération des champs du formulaire ---
    const title       = document.getElementById("title");
    const description = document.getElementById("description");
    const photo       = document.getElementById("photo");

    // Champs liés aux ingrédients
    const ingredientSearch    = document.getElementById("ingredientSearch");
    const ingredientSelect    = document.getElementById("ingredientSelect");
    const selectedIngredients = document.getElementById("selectedIngredients");
    const ingredientsGroupError = document.getElementById("ingredientsGroupError");

    // Champs liés aux tags
    const tagSearch    = document.getElementById("tagSearch");
    const tagSelect    = document.getElementById("tagSelect");
    const selectedTags = document.getElementById("selectedTags");

    // Données injectées par PHP (listes complètes des ingrédients et tags disponibles)
    const existingIngredients = window.recipeFormData.ingredients || [];
    const existingTags        = window.recipeFormData.tags        || [];

    // Tableaux des éléments sélectionnés par l'utilisateur dans cette session
    let chosenIngredients = [];
    let chosenTags        = [];

    // --- Fonctions utilitaires ---

    /** Normalise une chaîne pour les comparaisons (trim + minuscules) */
    function normalize(text) {
        return text.trim().toLowerCase();
    }

    /** Vérifie si un élément (par nom) est déjà dans la liste sélectionnée */
    function existsInSelected(list, name) {
        return list.some(item => normalize(item) === normalize(name));
    }

    /**
     * Remplit la liste déroulante <select> en filtrant les items selon la requête tapée.
     * Si aucun résultat, propose une option "Créer X" pour rediriger vers la page de création.
     *
     * @param {HTMLSelectElement} select - L'élément <select> à remplir
     * @param {Array}             items  - Tableau d'objets { name } à filtrer
     * @param {string}            query  - Texte saisi par l'utilisateur
     */
    function renderSelect(select, items, query) {
        select.innerHTML = "";
        const cleanQuery = normalize(query);

        // On cache la liste si la recherche est vide
        if (!cleanQuery) {
            select.style.display = "none";
            return;
        }

        // Filtrage : on garde les items dont le nom contient la requête
        const filtered = items.filter(item =>
            normalize(item.name).includes(cleanQuery)
        );

        filtered.forEach(item => {
            const option = document.createElement("option");
            option.value = item.name;
            option.textContent = item.name;
            select.appendChild(option);
        });

        // Aucun résultat : option spéciale pour créer l'élément
        if (filtered.length === 0) {
            const option = document.createElement("option");
            option.value = "__create__";
            option.textContent = `Créer "${query.trim()}"`;
            select.appendChild(option);
        }

        select.style.display = "block";
    }

    /**
     * Affiche les badges des éléments sélectionnés et ajoute les <input hidden>
     * correspondants pour que les valeurs soient soumises avec le formulaire.
     *
     * @param {HTMLElement} container - Conteneur des badges
     * @param {Array}       list      - Tableau des noms sélectionnés
     * @param {string}      inputName - Nom de l'input hidden (ex : 'ingredients' ou 'tags')
     */
    function renderSelected(container, list, inputName) {
        container.innerHTML = "";

        list.forEach((item, index) => {
            const badge = document.createElement("div");
            badge.className = "selected-badge";

            // Libellé du badge
            const label = document.createElement("span");
            label.textContent = item;

            // Bouton de suppression du badge
            const removeBtn = document.createElement("button");
            removeBtn.type = "button";
            removeBtn.className = "selected-badge-remove";
            removeBtn.textContent = "×";
            removeBtn.addEventListener("click", function () {
                list.splice(index, 1); // Supprime l'élément du tableau
                renderSelected(container, list, inputName); // Re-rendu
            });

            // Input caché soumis avec le formulaire
            const hiddenInput = document.createElement("input");
            hiddenInput.type  = "hidden";
            hiddenInput.name  = inputName + "[]";
            hiddenInput.value = item;

            badge.appendChild(label);
            badge.appendChild(removeBtn);
            badge.appendChild(hiddenInput);
            container.appendChild(badge);
        });
    }

    // --- Écouteurs pour le champ de recherche d'ingrédients ---

    // Mise à jour de la liste déroulante à chaque frappe
    ingredientSearch.addEventListener("input", function () {
        renderSelect(ingredientSelect, existingIngredients, ingredientSearch.value);
    });

    // Ajout de l'ingrédient sélectionné dans la liste des choix
    ingredientSelect.addEventListener("change", function () {
        const value      = ingredientSelect.value;
        const typedValue = ingredientSearch.value.trim();

        if (value === "__create__") {
            // L'ingrédient n'existe pas : redirection vers la page de création
            window.location.href = `ingredient_add.php?name=${encodeURIComponent(typedValue)}`;
            return;
        }

        // Ajout uniquement si pas déjà présent dans la sélection
        if (value && !existsInSelected(chosenIngredients, value)) {
            chosenIngredients.push(value);
            renderSelected(selectedIngredients, chosenIngredients, "ingredients");
        }

        // Réinitialisation du champ de recherche et de la liste
        ingredientSearch.value = "";
        ingredientSelect.innerHTML = "";
        ingredientSelect.style.display = "none";
    });

    // --- Écouteurs pour le champ de recherche de tags ---

    tagSearch.addEventListener("input", function () {
        renderSelect(tagSelect, existingTags, tagSearch.value);
    });

    tagSelect.addEventListener("change", function () {
        const value      = tagSelect.value;
        const typedValue = tagSearch.value.trim();

        if (value === "__create__") {
            // Le tag n'existe pas : redirection vers la page de création de tag
            window.location.href = `tag_add.php?name=${encodeURIComponent(typedValue)}`;
            return;
        }

        if (value && !existsInSelected(chosenTags, value)) {
            chosenTags.push(value);
            renderSelected(selectedTags, chosenTags, "tags");
        }

        tagSearch.value = "";
        tagSelect.innerHTML = "";
        tagSelect.style.display = "none";
    });

    // Fermeture des listes déroulantes si l'utilisateur clique en dehors d'un .form-group
    document.addEventListener("click", function (event) {
        if (!event.target.closest(".form-group")) {
            if (ingredientSelect) ingredientSelect.style.display = "none";
            if (tagSelect)        tagSelect.style.display        = "none";
        }
    });

    // --- Validation du formulaire de recette à la soumission ---
    recipeForm.addEventListener("submit", function (event) {
        clearErrors(recipeForm);
        let isValid = true;

        if (!title.value.trim()) {
            showFieldError(title, "Veuillez saisir un titre.");
            isValid = false;
        }

        if (!description.value.trim()) {
            showFieldError(description, "Veuillez saisir une description.");
            isValid = false;
        }

        if (!photo.value.trim()) {
            showFieldError(photo, "Veuillez saisir le chemin de la photo.");
            isValid = false;
        }

        // Au moins un ingrédient doit être sélectionné
        if (chosenIngredients.length === 0) {
            if (ingredientsGroupError) {
                ingredientsGroupError.textContent = "Veuillez ajouter au moins un ingrédient.";
            }
            isValid = false;
        }

        // Annulation de la soumission si le formulaire est invalide
        if (!isValid) {
            event.preventDefault();
        }
    });
}

/* ==========================================================================
   2. FORMULAIRES SIMPLES — Ingrédient, Tag, Connexion
   ========================================================================== */

/**
 * Ajoute une validation côté client aux formulaires simples :
 *  - #ingredientForm : nom et chemin d'image obligatoires
 *  - #tagForm        : nom obligatoire
 *  - .login-form     : login et mot de passe obligatoires
 */
function setupSimpleForms() {

    // --- Formulaire d'ingrédient ---
    const ingredientForm = document.getElementById("ingredientForm");
    if (ingredientForm) {
        ingredientForm.addEventListener("submit", function (event) {
            clearErrors(ingredientForm);
            let isValid = true;

            const name  = ingredientForm.querySelector("#name");
            const image = ingredientForm.querySelector("#image");

            if (!name.value.trim()) {
                showFieldError(name, "Veuillez saisir un nom.");
                isValid = false;
            }

            if (!image.value.trim()) {
                showFieldError(image, "Veuillez saisir le chemin de l'image.");
                isValid = false;
            }

            if (!isValid) event.preventDefault();
        });
    }

    // --- Formulaire de tag ---
    const tagForm = document.getElementById("tagForm");
    if (tagForm) {
        tagForm.addEventListener("submit", function (event) {
            clearErrors(tagForm);
            let isValid = true;

            const name = tagForm.querySelector("#name");

            if (!name.value.trim()) {
                showFieldError(name, "Veuillez saisir un nom de tag.");
                isValid = false;
            }

            if (!isValid) event.preventDefault();
        });
    }

    // --- Formulaire de connexion admin ---
    const loginForm = document.querySelector(".login-form");
    if (loginForm) {
        loginForm.addEventListener("submit", function (event) {
            clearErrors(loginForm);
            let isValid = true;

            const login    = loginForm.querySelector("#login");
            const password = loginForm.querySelector("#password");

            if (!login.value.trim()) {
                showFieldError(login, "Veuillez saisir votre login.");
                isValid = false;
            }

            if (!password.value.trim()) {
                showFieldError(password, "Veuillez saisir votre mot de passe.");
                isValid = false;
            }

            if (!isValid) event.preventDefault();
        });
    }
}

/* ==========================================================================
   3. CONFIRMATIONS DE SUPPRESSION
   ========================================================================== */

/**
 * Affiche une boîte de confirmation native du navigateur avant tout clic
 * sur un bouton portant la classe .delete-confirm.
 * Si l'utilisateur annule, le clic (et donc la soumission) est bloqué.
 */
function setupDeleteConfirmations() {
    const deleteButtons = document.querySelectorAll(".delete-confirm");

    deleteButtons.forEach(function (button) {
        button.addEventListener("click", function (event) {
            const ok = confirm("Voulez-vous vraiment supprimer cet élément ?");
            if (!ok) {
                event.preventDefault(); // Annulation : on ne soumet pas le formulaire
            }
        });
    });
}

/* ==========================================================================
   FONCTIONS UTILITAIRES — Affichage et effacement des erreurs
   ========================================================================== */

/**
 * Affiche un message d'erreur sous un champ de formulaire.
 * Ajoute la classe CSS .input-error sur le champ pour le mettre en rouge.
 *
 * @param {HTMLInputElement|HTMLTextAreaElement} field   - Le champ en erreur
 * @param {string}                               message - Le message à afficher
 */
function showFieldError(field, message) {
    const formGroup = field.closest(".form-group");
    if (!formGroup) return;

    const error = formGroup.querySelector(".error-message");
    field.classList.add("input-error");

    if (error) {
        error.textContent = message;
    }
}

/**
 * Efface tous les messages d'erreur et les styles d'erreur dans un formulaire.
 * Appelé avant chaque validation pour repartir d'un état propre.
 *
 * @param {HTMLFormElement} form - Le formulaire à nettoyer
 */
function clearErrors(form) {
    // Effacement des messages texte
    form.querySelectorAll(".error-message").forEach(function (error) {
        error.textContent = "";
    });

    // Retrait du style d'erreur sur les inputs et textareas
    form.querySelectorAll("input, textarea").forEach(function (input) {
        input.classList.remove("input-error");
    });

    // Effacement des erreurs de groupe (ex : "au moins un ingrédient requis")
    form.querySelectorAll(".group-error").forEach(function (error) {
        error.textContent = "";
    });
}
