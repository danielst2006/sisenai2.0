document.addEventListener('DOMContentLoaded', function() {
    const senhaInput = document.getElementById("senha");
    const toggleIcon = document.querySelector(".toggle-password");

    toggleIcon.addEventListener('click', function() {
        if (senhaInput.type === "password") {
            senhaInput.type = "text";
            toggleIcon.classList.remove("fa-eye");
            toggleIcon.classList.add("fa-eye-slash");
        } else {
            senhaInput.type = "password";
            toggleIcon.classList.remove("fa-eye-slash");
            toggleIcon.classList.add("fa-eye");
        }
    });
});