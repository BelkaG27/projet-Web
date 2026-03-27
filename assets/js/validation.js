document.addEventListener("DOMContentLoaded", function () {
setupRecipeFormDropdown();
setupSimpleForms();
setupDeleteConfirmations();
});

function setupRecipeFormDropdown() {
const recipeForm = document.getElementById("recipeForm");
if (!recipeForm || !window.recipeFormData) return;

const title = document.getElementById("title");
const description = document.getElementById("description");
const photo = document.getElementById("photo");

const ingredientSearch = document.getElementById("ingredientSearch");
const ingredientSelect = document.getElementById("ingredientSelect");
const selectedIngredients = document.getElementById("selectedIngredients");
const ingredientsGroupError = document.getElementById("ingredientsGroupError");

const tagSearch = document.getElementById("tagSearch");
const tagSelect = document.getElementById("tagSelect");
const selectedTags = document.getElementById("selectedTags");

const existingIngredients = window.recipeFormData.ingredients || [];
const existingTags = window.recipeFormData.tags || [];

let chosenIngredients = [];
let chosenTags = [];

function normalize(text) {
return text.trim().toLowerCase();
}

function existsInSelected(list, name) {
return list.some(item => normalize(item) === normalize(name));
}

function renderSelect(select, items, query) {
select.innerHTML = "";

const cleanQuery = normalize(query);

if (!cleanQuery) {
select.style.display = "none";
return;
}

const filtered = items.filter(item =>
normalize(item.name).includes(cleanQuery)
);

filtered.forEach(item => {
const option = document.createElement("option");
option.value = item.name;
option.textContent = item.name;
select.appendChild(option);
});

if (filtered.length === 0) {
const option = document.createElement("option");
option.value = "__create__";
option.textContent = `Créer "${query.trim()}"`;
select.appendChild(option);
}

select.style.display = "block";
}

function renderSelected(container, list, inputName) {
container.innerHTML = "";

list.forEach((item, index) => {
const badge = document.createElement("div");
badge.className = "selected-badge";

const label = document.createElement("span");
label.textContent = item;

const removeBtn = document.createElement("button");
removeBtn.type = "button";
removeBtn.className = "selected-badge-remove";
removeBtn.textContent = "×";
removeBtn.addEventListener("click", function () {
list.splice(index, 1);
renderSelected(container, list, inputName);
});

const hiddenInput = document.createElement("input");
hiddenInput.type = "hidden";
hiddenInput.name = inputName + "[]";
hiddenInput.value = item;

badge.appendChild(label);
badge.appendChild(removeBtn);
badge.appendChild(hiddenInput);

container.appendChild(badge);
});
}

ingredientSearch.addEventListener("input", function () {
renderSelect(ingredientSelect, existingIngredients, ingredientSearch.value);
});

ingredientSelect.addEventListener("change", function () {
const value = ingredientSelect.value;
const typedValue = ingredientSearch.value.trim();

if (value === "__create__") {
window.location.href = `ingredient_add.php?name=${encodeURIComponent(typedValue)}`;
return;
}

if (value && !existsInSelected(chosenIngredients, value)) {
chosenIngredients.push(value);
renderSelected(selectedIngredients, chosenIngredients, "ingredients");
}

ingredientSearch.value = "";
ingredientSelect.innerHTML = "";
ingredientSelect.style.display = "none";
});

tagSearch.addEventListener("input", function () {
renderSelect(tagSelect, existingTags, tagSearch.value);
});

tagSelect.addEventListener("change", function () {
const value = tagSelect.value;
const typedValue = tagSearch.value.trim();

if (value === "__create__") {
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

document.addEventListener("click", function (event) {
if (!event.target.closest(".form-group")) {
if (ingredientSelect) ingredientSelect.style.display = "none";
if (tagSelect) tagSelect.style.display = "none";
}
});

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

if (chosenIngredients.length === 0) {
if (ingredientsGroupError) {
ingredientsGroupError.textContent = "Veuillez ajouter au moins un ingrédient.";
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

const name = ingredientForm.querySelector("#name");
const image = ingredientForm.querySelector("#image");

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

const name = tagForm.querySelector("#name");

if (!name.value.trim()) {
showFieldError(name, "Veuillez saisir un nom de tag.");
isValid = false;
}

if (!isValid) {
event.preventDefault();
}
});
}

const loginForm = document.querySelector(".login-form");
if (loginForm) {
loginForm.addEventListener("submit", function (event) {
clearErrors(loginForm);
let isValid = true;

const login = loginForm.querySelector("#login");
const password = loginForm.querySelector("#password");

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