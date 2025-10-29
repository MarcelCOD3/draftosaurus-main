document.addEventListener("DOMContentLoaded", () => {
  const toggleButtons = document.querySelectorAll(".toggle-password");

  toggleButtons.forEach((btn) => {
    btn.addEventListener("click", () => {
      const targetSelector = btn.dataset.target;
      const input = document.querySelector(targetSelector);
      if (input.type === "password") {
        input.type = "text";
        btn.querySelector("i").classList.remove("fa-eye");
        btn.querySelector("i").classList.add("fa-eye-slash");
      } else {
        input.type = "password";
        btn.querySelector("i").classList.remove("fa-eye-slash");
        btn.querySelector("i").classList.add("fa-eye");
      }
    });
  });
});
