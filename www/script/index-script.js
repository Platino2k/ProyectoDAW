// Alterna entre Login y Registrarse

const login = document.getElementById("login");
const register = document.getElementById("register");

const loginBox = document.getElementById("loginBox");
const registerBox = document.getElementById("registerBox");

login.addEventListener('click', function () {
  
    
  loginBox.style.visibility = "visible";
  registerBox.style.visibility = "hidden";
});



register.addEventListener('click', function () {

  loginBox.style.visibility = "hidden";
  registerBox.style.visibility = "visible";  
});