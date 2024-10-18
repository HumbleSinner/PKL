const LoginButtonButton=document.getElementById('signUpButton');
const signInButton=document.getElementById('signInButton');
const signInForm=document.getElementById('login');
const signUpForm=document.getElementById('signup');

signUpButton.addEventListener('click',function(){
    signInForm.style.display="none";
    signUpForm.style.display="block";
})
LoginButton.addEventListener('click', function(){
    signInForm.style.display="block";
    signUpForm.style.display="none";
})