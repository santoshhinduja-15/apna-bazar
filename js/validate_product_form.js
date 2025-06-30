document.addEventListener("DOMContentLoaded", function () {
  const form = document.getElementById("productForm");
  const alertBox = document.getElementById("formAlert");

  form.addEventListener("submit", function (e) {
    let errors = [];

    const name = form.name.value.trim();
    const price = form.price.value.trim();
    const category = form.category.value.trim();
    const image = form.image.value;

    if (name === "") errors.push("⚠️ Product name is required.");
    if (price === "" || isNaN(price) || Number(price) <= 0) {
      errors.push("⚠️ Please enter a valid price.");
    }
    if (category === "") errors.push("⚠️ Please select a category.");
    if (image === "") errors.push("⚠️ Product image is required.");

    if (errors.length > 0) {
      e.preventDefault();
      alertBox.className = "alert alert-danger alert-dismissible fade show";
      alertBox.innerHTML = `
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        ${errors.map(err => `<div>${err}</div>`).join("")}
      `;
      alertBox.classList.remove("d-none");
    } else {
      alertBox.classList.add("d-none");
    }
  });
});
