// Function to safely add event listener
function addListener(elementId, event, callback) {
  const element = document.getElementById(elementId);
  if (element) {
      element.addEventListener(event, callback);
  }
}

// Signup modal
addListener("signupButton", "click", function () {
  document.getElementById("signupModal").style.display = "flex";
});
addListener("closeSignup", "click", function () {
  document.getElementById("signupModal").style.display = "none";
});

// Login modal
addListener("loginButton", "click", function () {
  document.getElementById("loginModal").style.display = "flex";
});
addListener("closeLogin", "click", function () {
  document.getElementById("loginModal").style.display = "none";
});

// Logout modal
addListener("logoutButton", "click", function () {
  document.getElementById("logoutModal").style.display = "block";
});
addListener("closeLogout", "click", function () {
  document.getElementById("logoutModal").style.display = "none";
});

// Close modals on outside click
window.addEventListener("click", function (e) {
  if (e.target.id === "signupModal") {
      document.getElementById("signupModal").style.display = "none";
  }
  if (e.target.id === "loginModal") {
      document.getElementById("loginModal").style.display = "none";
  }
  if (e.target.id === "logoutModal") {
      document.getElementById("logoutModal").style.display = "none";
  }
});