const signInButton=document.getElementById('signInButton');
const signInForm=document.getElementById('signIn');
const signUpButton=document.getElementById('signUpButton');
const signUpForm=document.getElementById('signUp');
const Login=document.getElementById('Login');
const getStarted=document.getElementById('getStarted');
const front_page = document.getElementById('front_page');
const navbar=document.getElementById('navbar');
const about=document.getElementById('about');
const container = document.getElementById("container");
const registerBtn = document.getElementById("register");
const loginBtn = document.getElementById("login");
const form_container = document.getElementById("form_container");

Login.addEventListener('click',function(){
    signInForm.style.display="block";
    signUpForm.style.display="none";
    front_page.style.display="none";
    navbar.style.display="none";
    document.getElementById('about').style.display="none";
})
getStarted.addEventListener('click',function(){
    signInForm.style.display="block";
    signUpForm.style.display="none";
    front_page.style.display="none";
    navbar.style.display="none";
    document.getElementById('about').style.display="none";
})
signInButton.addEventListener('click', function(){
    signUpForm.style.display="none";
    signInForm.style.display="block";
})
signUpButton.addEventListener('click',function(){
    signInForm.style.display="none";
    signUpForm.style.display="block";
})

function closesignInForm(){
    signInForm.style.display="none";
    front_page.style.display="block";
    navbar.style.display="";
    document.getElementById('about').style.display="none";
    document.getElementById('second_page').style.display="block";
    document.getElementById('contact').style.display="grid";
}
function closesignUpForm(){
    signUpForm.style.display="none";
    front_page.style.display="block";
    navbar.style.display="";
    document.getElementById('about').style.display="none";
    document.getElementById('second_page').style.display="block";
    document.getElementById('contact').style.display="grid";
}
function showabout(){
    document.getElementById('about').style.display="flex";
}
window.onclick = function(event) {
    if (event.target == about) {
        about.style.display = "none";
    }
}
document.getElementById("password").addEventListener("input", function() {
    let password = this.value;
    let strengthText = document.getElementById("strength-text");

    // Define regex patterns
    let lengthCheck = password.length >= 8;
    let uppercaseCheck = /[A-Z]/.test(password);
    let lowercaseCheck = /[a-z]/.test(password);
    let numberCheck = /[0-9]/.test(password);
    let specialCheck = /[@$!%*?&]/.test(password);

    // Show error if any condition is not met


    // Calculate strength level
    let strength = 0;
    if (lengthCheck) strength++;
    if (uppercaseCheck) strength++;
    if (lowercaseCheck) strength++;
    if (numberCheck) strength++;
    if (specialCheck) strength++;

    // Update strength bar and text
    if (strength === 0) {
        
        strengthText.textContent = "";
        
    } else if (strength <= 2) {
        
        strengthText.textContent = "Your password is too weak";
    } else if (strength === 3 || strength === 4) {
        
        strengthText.textContent = "You password is average, consider adding more complexities";
    } else if(strength === 5){
        
        strengthText.textContent = "Your password is Strong";
    }
});
// const passwordInput = document.getElementById("password");
const togglePassword = document.getElementById("togglePassword");

togglePassword.addEventListener("change", function() {
    passwordInput.type = this.checked ? "text" : "password";
});
        
function validateform1(){
    var name = document.getElementById("name").value;
    var email = document.getElementById("email").value;
    var password = document.getElementById("password").value;
    var cpassword = document.getElementById("cpassword").value;
    var strengthText = document.getElementById("strength-text").textContent;
    const regex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;

    if(name.trim()==="" && email.trim()==="" && password.trim()==="" && cpassword.trim()===""){
        alert("All fields are required");
        return false;
    }
    else if(name.trim()===""){
        alert("The name is empty");
        return false;
    }
    else if(email.trim()===""){
        alert("The email is empty");
        return false;
    }
    else if(!regex.test(email)){
        alert("Please enter a valid email address");
        return false;
    }
    else if(password.trim()===""){
        alert("The password is empty");
        return false;
    }
    else if(password.length < 8){
        alert("Password must be at least 8 characters long");
        return false;
    }
    else if (strengthText !== "Your password is Strong") {
        alert("Your password must be strong to proceed.");
        return false;
    }
    else if(cpassword!=password)
        {
            alert("The confirm password is not matching with the password");
            return false;
        }
    else{
        return true;
    }
}
function validateform2(){
    var email = document.getElementById("email2").value;
    var password = document.getElementById("password2").value;

    if(email.trim()===""){
        alert("The email is empty");
        return false;
    }
    else if(email.indexOf("@")==-1) 
        {
            alert("The email is invalid; @ is not present.");
            return false;
        }
        else if(email.indexOf(".") == -1){
            alert("The email is invalid; dot (.) is not present.");
            return false;
        }
        //@ and . can't be present in the beginning of an email 
        else if(email.charAt(0)=="@" || email.charAt(0)==".")
        {
            alert("The email is invalid; @ and. can't come at the begining."); 
            return false;
        }
        // @and. can't be present in the end of an email
        else if(email.charAt(email.length-1)=="@" || email.charAt(email.length-
        1)==".")
        {
            alert("The email is invalid; @ and. can't come at the end."); 
            return false;
        }
        //@ and. can't come together
        else if(email.indexOf("@")==email.indexOf(".")-1){
            alert("The email is invalid; @ and. can't come together."); 
            return false;
        }     
    else if(password.trim()===""){
        alert("The password is empty");
        return false;
    }
    else{
        return true;
    }
}

