<?php
include "oopFramework.php";
include "customizedEcho.php";

if (isset($_POST['signUp'])) {
    $sName = $_POST['sName'];
    $sEmail = $_POST['sEmail'];
    $sPassword = $_POST['sPassword'];

    $userNew = new SignUP($sEmail, $sPassword, $conn, $sName);
    $checkresult = $userNew->checkEmail();
    if ($checkresult == 0) {
        $performSignUp = $userNew->insertSignUpData();
        if ($performSignUp) {
            echo $signUPSuccesfull;
        } else {
            echo $signUpUnsuccessful;
        }
    } elseif ($checkresult > 0) {
        echo $signUpUnsuccessful;
    }
} elseif (isset($_POST['logIn'])) {
    $lEmail = $_POST['lEmail'];
    $lPassword = $_POST['lPassword'];

    $user = new LogIn($lEmail, $lPassword, $conn);
    $checkEmail = $user->checkEmail();
    if ($checkEmail > 0) {
        $checkPassword = $user->confrimPasssword();
        echo $checkPassword;
        if ($checkPassword) {
            echo $logInSuccessful;
            $user->startSession();
        }
    } else {
        echo $returnToHomepage;
    }
}
