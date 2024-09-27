const signInButton=document.getElementById('signInButton');
const signInForm=document.getElementById('signIn');
const signUpButton=document.getElementById('signUpButton');
const signUpForm=document.getElementById('signup');
const Login=document.getElementById('Login');
const getStarted=document.getElementById('getStarted');
const front_page = document.getElementById('front_page');
const navbar=document.getElementById('navbar');
const about=document.getElementById('about');

signInButton.addEventListener('click', function(){
    signUpForm.style.display="none";
    signInForm.style.display="block";
})
signUpButton.addEventListener('click',function(){
    signInForm.style.display="none";
    signUpForm.style.display="block";
})
Login.addEventListener('click',function(){
    signInForm.style.display="block";
    signUpForm.style.display="none";
    front_page.style.display="none";
    navbar.style.display="none";
    document.getElementById('about').style.display="none";
    document.getElementById('second_page').style.display="none";
    document.getElementById('contact').style.display="none";
})
getStarted.addEventListener('click',function(){
    signInForm.style.display="block";
    signUpForm.style.display="none";
    front_page.style.display="none";
    navbar.style.display="none";
    document.getElementById('about').style.display="none";
    document.getElementById('second_page').style.display="none";
    document.getElementById('contact').style.display="none";
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
function validate()
{
    var email=document.myForm.email.value;
    //Blank validation of First Name
    if(document.myForm.fName.value=="")
    {
        //Message to the User
        alert("The First Name is Empty.");
        //Set input focus to the invalid textbox
        document.myForm.fName.focus();
        /* Return false so that the form will not be submitted. */
        return false;
    }
    //Blank validation of Last Name 
    else if(document.myForm.lName.value=="")
    {
        alert("The Last Name is Empty."); 
        document.myForm.lName.focus();
        return false;
    }
    //Blank Validation of Email
    else if(document.myForm.email.value=="")
    {
        alert("The Email field is empty."); 
        document.myForm.email.focus();
        return false;
    }
   /*Refer to the methods of string object we studied earlier which are very important to validate an email address. */
    
    //@ and . must be present in an email address 
    else if(email.indexOf("@")==-1) 
    {
        alert("The email is invalid; @ is not present.");
        document.myForm.email.focus();
        return false;
    }
    else if(email.indexOf(".") == -1){
        alert("The email is invalid; dot (.) is not present.");
        document.myForm.email.focus();
        return false;
    }
    //@ and . can't be present in the beginning of an email 
    else if(email.charAt(0)=="@" || email.charAt(0)==".")
    {
        alert("The email is invalid; @ and. can't come at the begining."); 
        document.myForm.email.focus();
        return false;
    }
    // @and. can't be present in the end of an email
    else if(email.charAt(email.length-1)=="@" || email.charAt(email.length-
    1)==".")
    {
        alert("The email is invalid; @ and. can't come at the end."); 
        document.myForm.email.focus();
        return false;
    }
    //@ and. can't come together
    else if(email.indexOf("@")==email.indexOf(".")-1){
        alert("The email is invalid; @ and. can't come together."); 
        document.myForm.email.focus();
        return false;
    }        
}

const text = "Need Help?";
let index = 0;
const speed = 100; // Adjust the speed of typing in milliseconds

function typeWriter() {
  if (index < text.length) {
    document.getElementById("typewriter").innerHTML += text.charAt(index);
    index++;
    setTimeout(typeWriter, speed);
  }
}

// window.onscroll = typeWriter; // Start typing when the page loads

window.addEventListener('scroll', function() {
    if (document.body.scrollTop > 500 || document.documentElement.scrollTop > 500) {
      typeWriter();
    }
  });