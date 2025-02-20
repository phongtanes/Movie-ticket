document.addEventListener("DOMContentLoaded", function () {
    document.getElementById("loginForm").addEventListener("keypress", function (event) {
        if (event.key === "Enter") {
            event.preventDefault();
            document.getElementById("loginBtn").click();
        }
    });

    document.getElementById("registerForm").addEventListener("keypress", function (event) {
        if (event.key === "Enter") {
            event.preventDefault();
            document.getElementById("registerBtn").click();
        }
    });
});
