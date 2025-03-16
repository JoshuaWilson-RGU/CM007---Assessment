document.addEventListener('DOMContentLoaded', () => {
  const signupButton = document.getElementById('signupButton');
  const loginButton = document.getElementById('loginButton');
  const signupModal = document.getElementById('signupModal');
  const loginModal = document.getElementById('loginModal');

  console.log('signupButton:', signupButton);
  console.log('loginButton:', loginButton);

  signupButton.addEventListener('click', () => {
      console.log('Sign-up button clicked');
      if (signupModal) {
          signupModal.style.display = 'flex'; 
      } else {
          console.log('signupModal not found');
      }
  });

  loginButton.addEventListener('click', () => {
      console.log('Log-in button clicked');
      if (loginModal) {
          loginModal.style.display = 'flex'; 
      } else {
          console.log('loginModal not found');
      }
  });
});