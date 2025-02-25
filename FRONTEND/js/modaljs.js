  // Open the signup modal when the signup button is clicked.
  document
    .getElementById("signupButton")
    .addEventListener("click", function () {
      document.getElementById("signupModal").style.display = "flex";
    });

  // Open the login modal when the login button is clicked.
  document
    .getElementById("loginButton")
    .addEventListener("click", function () {
      document.getElementById("loginModal").style.display = "flex";
    });

  // Close the signup modal.
  document
    .getElementById("closeSignup")
    .addEventListener("click", function () {
      document.getElementById("signupModal").style.display = "none";
    });

  // Close the login modal.
  document
    .getElementById("closeLogin")
    .addEventListener("click", function () {
      document.getElementById("loginModal").style.display = "none";
    });

  // Optional: Close modal when clicking outside the modal box.
  window.addEventListener("click", function (e) {
    if (e.target.id === "signupModal") {
      document.getElementById("signupModal").style.display = "none";
    }
    if (e.target.id === "loginModal") {
      document.getElementById("loginModal").style.display = "none";
    }
  });