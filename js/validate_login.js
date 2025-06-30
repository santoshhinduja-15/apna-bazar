document.addEventListener("DOMContentLoaded", function () {
  const form = document.getElementById("loginForm");
  const errorDiv = document.getElementById("formError");
  const errorText = document.getElementById("formErrorText");

  form.addEventListener("submit", function (e) {
    errorDiv.classList.add("d-none");
    errorText.textContent = "";

    const email = form.email.value.trim();
    const password = form.password.value.trim();

    if (email === "" || password === "") {
      showError("Email and password are required.");
      e.preventDefault();
      return;
    }

    if (!validateEmail(email)) {
      showError("Invalid email format.");
      e.preventDefault();
      return;
    }

    if (password.length < 6) {
      showError("Password must be at least 6 characters.");
      e.preventDefault();
      return;
    }
  });

  function showError(message) {
    errorText.textContent = message;
    errorDiv.classList.remove("d-none");
  }

  function validateEmail(email) {
    return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
  }

  const togglePasswordBtn = document.getElementById("togglePassword");
  const passwordInput = document.getElementById("passwordInput");

  togglePasswordBtn.addEventListener("click", function () {
    const isPassword = passwordInput.getAttribute("type") === "password";
    passwordInput.setAttribute("type", isPassword ? "text" : "password");
    togglePasswordBtn.textContent = isPassword ? "Hide Password" : "Show Password";
  });
});
