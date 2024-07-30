<?php
include "connect_db.php";
class ManageDatabase
{
    private $id;
    private $encryptedPassword;
    private $social;
    private $website;
    private $type;
    private $conn;
    public function __construct($id, $social, $website = '', $type, $encryptedPassword, $conn)
    {
        $this->id = $id;
        $this->social = $social;
        $this->website = $website;
        $this->type = $type;
        $this->conn = $conn;
        $this->encryptedPassword = $encryptedPassword;
    }
    function createSocialAccount()
    {
        $idValue = $this->id;
        $createdPassword = $this->encryptedPassword;
        $socialStr = $this->social;
        $websiteStr = $this->website;
        $typePassword = $this->type;
        $sql = "INSERT INTO genpasswords (id, social, website, type, password) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(1, $idValue, PDO::PARAM_INT);
        $stmt->bindParam(2, $socialStr, PDO::PARAM_STR);
        $stmt->bindParam(3, $websiteStr, PDO::PARAM_STR);
        $stmt->bindParam(4, $typePassword, PDO::PARAM_STR);
        $stmt->bindParam(5, $createdPassword, PDO::PARAM_STR);
        $stmt->execute();
        $rowCount = $stmt->rowCount();
        return $rowCount;
    }
    function deleteSocialAccount()
    {
        $idValue = $this->id;
        $sql = "DELETE FROM genpasswords WHERE id = ? AND social = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(1, $idValue, PDO::PARAM_INT);
        $stmt->bindValue(2, 'value2', PDO::PARAM_STR);
        $rowCount = $stmt->execute();
        return $rowCount;
    }
    function provideSocialAccount()
    {
        $socialAccount = $this->id;
        $sql = "SELECT social FROM genpasswords WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$socialAccount]);
        $socialAccount = $stmt->fetchAll(PDO::FETCH_COLUMN);
        return $socialAccount;
    }
    function provideWebsite()
    {
        $website = $this->id;
        $sql = "SELECT website FROM genpasswords WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$website]);
        $website = $stmt->fetchAll(PDO::FETCH_COLUMN);
        return $website;
    }
    function providePassworType()
    {
        $passwordType = $this->id;
        $sql = "SELECT type FROM genpasswords WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$passwordType]);
        $passwordType = $stmt->fetchAll(PDO::FETCH_COLUMN);
        return $passwordType;
    }
    function providePassword()
    {
        $id = $this->id;
        $sql = "SELECT password FROM genpasswords WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$id]);
        $passwords = $stmt->fetchAll(PDO::FETCH_COLUMN);
        return $passwords;
    }
    function numberofSocials()
    {
        $idValue = $this->id;
        $sql = "SELECT COUNT(*) FROM genpasswords WHERE id = ? AND social IS NOT NULL";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $idValue);
        $stmt->execute();
        $count = $stmt->fetchColumn();
        return $count;
    }
}
class SecurePassword
{
    private $id;
    private $genPassword;
    private $conn;
    public function __construct($id, $genPassword, $conn)
    {
        $this->id = $id;
        $this->genPassword = $genPassword;
        $this->conn = $conn;
    }
    function currentUserIv()
    {
        $idValue = $this->id;
        $sql1 = "SELECT genIv FROM userdata WHERE id = ?";
        $stmt1 = $this->conn->prepare($sql1);
        $stmt1->execute([$idValue]);
        $iv = $stmt1->fetchColumn();
        return $iv;
    }
    function encryptKey()
    {
        $iv = $this->currentUserIv();
        $genPassword = $this->genPassword;
        $MasterKey = "+&T#t6a[heTRL1%Dt4[%DT+!G]@pCONS";
        $encrypted = openssl_encrypt($genPassword, 'aes-256-cbc', $MasterKey, OPENSSL_RAW_DATA, $iv);
        return base64_encode($encrypted);
    }
    function decryptPassword($encryptedData)
    {
        $iv = $this->currentUserIv();
        $MasterKey = "+&T#t6a[heTRL1%Dt4[%DT+!G]@pCONS";
        $decodedData = base64_decode($encryptedData);
        $decrypted = openssl_decrypt($decodedData, 'aes-256-cbc', $MasterKey, OPENSSL_RAW_DATA, $iv);
        return $decrypted;
    }
}
class GenerateSecurePassword
{
    private $length;
    private $useUpperCase;
    private $useLowerCase;
    private $useNumbers;
    private $useSpecialChars;
    public function __construct($length = 16, $useUpperCase, $useLowerCase, $useNumbers, $useSpecialChars)
    {
        $this->length = $length;
        $this->useUpperCase = $useUpperCase;
        $this->useLowerCase = $useLowerCase;
        $this->useNumbers = $useNumbers;
        $this->useSpecialChars = $useSpecialChars;
    }
    function generateRandomCode()
    {
        $characters = '';
        $code = '';
        if ($this->useUpperCase) {
            $characters .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        }
        if ($this->useLowerCase) {
            $characters .= 'abcdefghijklmnopqrstuvwxyz';
        }
        if ($this->useNumbers) {
            $characters .= '0123456789';
        }
        if ($this->useSpecialChars) {
            $characters .= '!@#$%^&*()-_=+{}[]|;:,.<>?';
        }
        $charLength = strlen($characters);

        for ($i = 0; $i < $this->length; $i++) {
            $code .= $characters[rand(0, $charLength - 1)];
        }
        return $code;
    }
}
