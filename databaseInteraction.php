<?php
include "homePage.php";
include "customizedEcho.php";
$idNum = $_SESSION['id'];
if (isset($_POST['save'])) {
    $receivedPassword = $_POST['genPassword'];
    $socialAccount = $_POST['socialAccount'];
    $website = $_POST['website'];
    if ($receivedPassword == $GeneratedPassword) {
        $typeOfPassword = 'Generated';
    } else {
        $typeOfPassword = 'Desired';
    }
    $userAccount = new SecurePassword($idNum, $receivedPassword, $conn);
    $encryptedPassword = $userAccount->encryptKey();
    $userAccountMore = new ManageDatabase($idNum, $socialAccount, $website, $typeOfPassword, $encryptedPassword, $conn);
    $rowAffected = $userAccountMore->createSocialAccount();
    if ($rowAffected) {
        echo $MyPasswordSave;
    }
} elseif (isset($_POST['logout'])) {
    if (session_destroy()) {
        echo $testEndSession;
        header('location:logInPage.html');
        exit;
    }
}
