document.addEventListener("DOMContentLoaded", function () {
  const form = document.querySelector("form");
  const passwordInput = document.getElementById("passwordInput");
  const toggleBtn = document.getElementById("togglePassword");

  // Toggle password visibility
  toggleBtn.addEventListener("click", function () {
    const isHidden = passwordInput.type === "password";
    passwordInput.type = isHidden ? "text" : "password";
    toggleBtn.textContent = isHidden ? "Hide Password" : "Show Password";
  });

  form.addEventListener("submit", function (e) {
    let valid = true;

    const name = form.name.value.trim();
    const email = form.email.value.trim();
    const mobile = form.mobile.value.trim();
    const password = form.password.value.trim();
    const role = form.role.value;

    // Clear previous errors
    document.querySelectorAll(".js-error").forEach((el) => el.remove());

    // Name validation
    if (name.length < 3) {
      showError(form.name, "Name must be at least 3 characters long.");
      valid = false;
    }

    // Email validation
    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailPattern.test(email)) {
      showError(form.email, "Enter a valid email address.");
      valid = false;
    }

    // Mobile validation
    const mobilePattern = /^[0-9]{10}$/;
    if (!mobilePattern.test(mobile)) {
      showError(form.mobile, "Enter a valid 10-digit mobile number.");
      valid = false;
    }

    // Password validation
    if (password.length < 6) {
      showError(form.password, "Password must be at least 6 characters long.");
      valid = false;
    }

    // Role validation
    if (!role) {
      showError(form.role, "Please select a role.");
      valid = false;
    }

    if (!valid) e.preventDefault();
  });

  function showError(inputElement, message) {
    const error = document.createElement("div");
    error.className = "alert alert-warning alert-dismissible fade show mt-2 js-error";
    error.setAttribute("role", "alert");
    error.innerHTML = `
      ${message}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    `;
    inputElement.parentNode.appendChild(error);
  }
});
