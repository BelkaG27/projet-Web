document.addEventListener("DOMContentLoaded", function () {
    setupRecipeForm();
    setupSimpleForms();
    setupDeleteConfirmations();
});

function setupRecipeForm() {
    const recipeForm = document.getElementById("recipeForm");
    if (!recipeForm) return;

    const addIngredientBtn = document.getElementById("addIngredientBtn");
    const addTagBtn = document.getElementById("addTagBtn");
    const newIngredientsContainer = document.getElementById("newIngredientsContainer");
    const newTagsContainer = document.getElementById("newTagsContainer");
    const ingredientsGroupError = document.getElementById("ingredientsGroupError");

    if (addIngredientBtn && newIngredientsContainer) {
        addIngredientBtn.addEventListener("click", function () {
            const row = document.createElement("div");
            row.className = "dynamic-row ingredient-row";
            row.innerHTML = `
                <input type="text" name="new_ingredients[]" placeholder="Nom du nouvel ingrédient">
                <button type="button" class="btn-danger remove-row-btn">Supprimer</button>
            `;
            newIngredientsContainer.appendChild(row);
        });
    }

    if (addTagBtn && newTagsContainer) {
        addTagBtn.addEventListener("click", function () {
            const row = document.createElement("div");
            row.className = "dynamic-row tag-row";
            row.innerHTML = `
                <input type="text" name="new_tags[]" placeholder="Nom du nouveau tag">
                <button type="button" class="btn-danger remove-row-btn">Supprimer</button>
            `;
            newTagsContainer.appendChild(row);
        });
    }

    document.addEventListener("click", function (event) {
        if (event.target.classList.contains("remove-row-btn")) {
            const row = event.target.closest(".dynamic-row");
            if (!row) return;

            const parent = row.parentElement;
            const allRows = parent.querySelectorAll(".dynamic-row");

            if (allRows.length > 1) {
                row.remove();
            } else {
                const input = row.querySelector("input");
                if (input) input.value = "";
            }
        }
    });

    recipeForm.addEventListener("submit", function (event) {
        clearErrors(recipeForm);

        let isValid = true;

        const title = document.getElementById("title");
        const description = document.getElementById("description");
        const photo = document.getElementById("photo");

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

        const checkedIngredients = recipeForm.querySelectorAll('input[name="existing_ingredients[]"]:checked');
        const newIngredients = recipeForm.querySelectorAll('input[name="new_ingredients[]"]');

        let hasNewIngredient = false;
        newIngredients.forEach(function (input) {
            if (input.value.trim() !== "") {
                hasNewIngredient = true;
            }
        });

        if (checkedIngredients.length === 0 && !hasNewIngredient) {
            if (ingredientsGroupError) {
                ingredientsGroupError.textContent = "Veuillez sélectionner ou ajouter au moins un ingrédient.";
            }
            isValid = false;
        }

        if (!isValid) {
            event.preventDefault();
        }
    });
}

function setupSimpleForms() {
    const ingredientForm = document.getElementById("ingredientForm");
    if (ingredientForm) {
        ingredientForm.addEventListener("submit", function (event) {
            clearErrors(ingredientForm);
            let isValid = true;

            const name = ingredientForm.querySelector('#name');
            const image = ingredientForm.querySelector('#image');

            if (!name.value.trim()) {
                showFieldError(name, "Veuillez saisir un nom.");
                isValid = false;
            }

            if (!image.value.trim()) {
                showFieldError(image, "Veuillez saisir le chemin de l’image.");
                isValid = false;
            }

            if (!isValid) {
                event.preventDefault();
            }
        });
    }

    const tagForm = document.getElementById("tagForm");
    if (tagForm) {
        tagForm.addEventListener("submit", function (event) {
            clearErrors(tagForm);
            let isValid = true;

            const name = tagForm.querySelector('#name');

            if (!name.value.trim()) {
                showFieldError(name, "Veuillez saisir un nom de tag.");
                isValid = false;
            }

            if (!isValid) {
                event.preventDefault();
            }
        });
    }

    const loginForm = document.querySelector('.login-form');
    if (loginForm) {
        loginForm.addEventListener("submit", function (event) {
            clearErrors(loginForm);
            let isValid = true;

            const login = loginForm.querySelector('#login');
            const password = loginForm.querySelector('#password');

            if (!login.value.trim()) {
                showFieldError(login, "Veuillez saisir votre login.");
                isValid = false;
            }

            if (!password.value.trim()) {
                showFieldError(password, "Veuillez saisir votre mot de passe.");
                isValid = false;
            }

            if (!isValid) {
                event.preventDefault();
            }
        });
    }
}

function setupDeleteConfirmations() {
    const deleteButtons = document.querySelectorAll(".delete-confirm");

    deleteButtons.forEach(function (button) {
        button.addEventListener("click", function (event) {
            const ok = confirm("Voulez-vous vraiment supprimer cet élément ?");
            if (!ok) {
                event.preventDefault();
            }
        });
    });
}

function showFieldError(field, message) {
    const formGroup = field.closest(".form-group");
    if (!formGroup) return;

    const error = formGroup.querySelector(".error-message");
    field.classList.add("input-error");

    if (error) {
        error.textContent = message;
    }
}

function clearErrors(form) {
    const errors = form.querySelectorAll(".error-message");
    errors.forEach(function (error) {
        error.textContent = "";
    });

    const inputs = form.querySelectorAll("input, textarea");
    inputs.forEach(function (input) {
        input.classList.remove("input-error");
    });

    const groupErrors = form.querySelectorAll(".group-error");
    groupErrors.forEach(function (error) {
        error.textContent = "";
    });
}