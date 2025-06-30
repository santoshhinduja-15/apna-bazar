document.addEventListener("DOMContentLoaded", function () {
  const form = document.getElementById("editProductForm");
  const alertBox = document.getElementById("formAlert");

  form.addEventListener("submit", function (e) {
    const name = form.name.value.trim();
    const price = form.price.value.trim();
    const category = form.category.value;
    const quantity = form.quantity.value.trim();

    const errors = [];

    // Validate product name
    if (!name) {
      errors.push("Product name is required.");
    }

    // Validate price
    const priceValue = parseFloat(price);
    if (!price || isNaN(priceValue) || priceValue <= 0) {
      errors.push("Price must be a valid number greater than 0.");
    }

    // Validate category
    if (!category) {
      errors.push("Please select a category.");
    }

    // Validate quantity
    const quantityValue = parseInt(quantity);
    if (!quantity || isNaN(quantityValue) || quantityValue <= 0) {
      errors.push("Quantity must be at least 1.");
    }

    // Clear previous alert
    alertBox.classList.add("d-none");
    alertBox.innerHTML = "";

    // If errors exist, show them and prevent form submission
    if (errors.length > 0) {
      e.preventDefault();
      alertBox.className = "alert alert-danger alert-dismissible fade show";
      alertBox.setAttribute("role", "alert");

      alertBox.innerHTML = `
        ${errors.map(err => `<div>${err}</div>`).join('')}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      `;
    }
  });
});
