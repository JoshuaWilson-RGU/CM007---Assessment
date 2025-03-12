document.addEventListener("DOMContentLoaded", () => {
  function setupModal(buttonId, modalId, closeId, displayStyle) {
    const button = document.getElementById(buttonId);
    const modal = document.getElementById(modalId);
    const close = document.getElementById(closeId);

    if (button && modal && close) {
      button.addEventListener("click", () => {
        modal.style.display = displayStyle;
      });
      close.addEventListener("click", () => {
        modal.style.display = "none";
      });
    }
  }

  const modals = [
    { buttonId: "signupButton", modalId: "signupModal", closeId: "closeSignup", displayStyle: "flex" },
    { buttonId: "loginButton", modalId: "loginModal", closeId: "closeLogin", displayStyle: "flex" },
    { buttonId: "logoutButton", modalId: "logoutModal", closeId: "closeLogout", displayStyle: "flex" }
  ];

  modals.forEach(({ buttonId, modalId, closeId, displayStyle }) => {
    if (document.getElementById(buttonId)) {
      setupModal(buttonId, modalId, closeId, displayStyle);
    }
  });

  window.addEventListener("click", (e) => {
    const modalIds = ["signupModal", "loginModal", "logoutModal"];
    if (modalIds.includes(e.target.id)) {
      document.getElementById(e.target.id).style.display = "none";
    }
  });
});