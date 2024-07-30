<?php

$signUPSuccesfull = '<script type="text/javascript">
alert("SIGN UP COMPLETE");
alert("You are now being Redirected to Log in Page");
window.location.href = "./logInPage.html";
</script>';

$signUpUnsuccessful = '<script type="text/javascript">
alert("Email Already Exist! Use Another Email!");
window.location.href = "./signUpPage.html";
</script>';

$returnToHomepage = '<script type="text/javascript">
alert("Invalid Email or Password");
window.location.href = "./logInPage.html";
</script>';

$returnToSignUpPage = '<script type="text/javascript">
alert("Invalid Email or Password");
window.location.href = "./signUpPage.html";
</script>';

$logInSuccessful = '<script type="text/javascript">
alert("Log In Successful");
</script>';

$testEndSession = '<script type="text/javascript">
alert("You are Now being Logged Out");
</script>';

$MyPasswordSave = '<script type="text/javascript">
alert("Password Saved Successfully");
window.location.href = "./homePage.php";
</script>';
