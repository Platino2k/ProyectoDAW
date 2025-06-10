// Alterna entre Login y Registrarse

const login = document.getElementById("login");
const register = document.getElementById("register");

const loginBox = document.getElementById("loginBox");
const registerBox = document.getElementById("registerBox");

login.addEventListener('click', function () {  
    document.getElementById('loginBox').style.display = 'flex';
    document.getElementById('loginBox').style.display.flexDirection = 'column';
    document.getElementById('registerBox').style.display = 'none';
});



register.addEventListener('click', function () {
    document.getElementById('loginBox').style.display = 'none';
    document.getElementById('registerBox').style.display = 'block';
});

