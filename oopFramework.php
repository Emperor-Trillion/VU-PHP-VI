<?php
include "connect_db.php";

class UniversalData
{
    private $Email;
    private $Password;
    private $conn;
    public function __construct($Email, $Password, $conn)
    {
        $this->Email = $Email;
        $this->Password = $Password;
        $this->conn = $conn;
    }
    public function getEmail()
    {
        return $this->Email;
    }
    public function getPassword()
    {
        return $this->Password;
    }
    public function getConn()
    {
        return $this->conn;
    }
    public function checkEmail()
    {
        $sql = "SELECT COUNT(email) FROM userdata WHERE email = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$this->Email]);
        $result = $stmt->fetchColumn();
        return $result;
    }
}
class SignUP extends UniversalData
{
    private $sName;
    private $sEmail;
    private $sPassword;
    private $conn;
    public function __construct($sEmail, $sPassword, $conn, $sName)
    {
        parent::__construct($sEmail, $sPassword, $conn);
        $this->sName = $sName;
        $this->sEmail = $sEmail;
        $this->sPassword = $sPassword;
        $this->conn = $conn;
    }
    public function getsName()
    {
        return $this->sName;
    }
    public function getHashedPassword()
    {
        return password_hash($this->sPassword, PASSWORD_BCRYPT, ['cost' => 12]);
    }
    public function generateUniqueKey()
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!"#$%&/()=?';
        $randomString = '';
        for ($i = 0; $i < 16; $i++) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $randomString;
    }

    public function insertSignUpData()
    {
        $iv = $this->generateUniqueKey();
        $hashedPassword = $this->getHashedPassword();

        $sql = "INSERT INTO userdata (name, email, password, genIv) VALUES (?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(1, $this->sName, PDO::PARAM_STR);
        $stmt->bindParam(2, $this->sEmail, PDO::PARAM_STR);
        $stmt->bindParam(3, $hashedPassword, PDO::PARAM_STR);
        $stmt->bindParam(4, $iv, PDO::PARAM_STR);
        $stmt->execute();

        $rowCount = $stmt->rowCount();
        if ($rowCount > 0) {
            return true;
        } else {
            return false;
        }
    }
}
class LogIn extends UniversalData
{
    private $lEmail;
    private $lPassword;
    private $conn;
    function __construct($lEmail, $lPassword, $conn)
    {
        parent::__construct($lEmail, $lPassword, $conn);
        $this->lEmail = $lEmail;
        $this->lPassword = $lPassword;
        $this->conn = $conn;
    }
    function confrimPasssword()
    {
        $sql = "SELECT password FROM userdata WHERE email= ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$this->lEmail]);
        $result = $stmt->fetch();
        if ($result) {
            return password_verify($this->lPassword, $result['password']);
        } else {
            return false;
        }
    }
    function currentUserID()
    {
        $sql1 = "SELECT id FROM userdata WHERE email = ?";
        $stmt1 = $this->conn->prepare($sql1);
        $stmt1->execute([$this->lEmail]);
        $idValue = $stmt1->fetchColumn();
        return $idValue;
    }
    function startSession()
    {
        session_start();
        $_SESSION['id'] = $this->currentUserID();

        if (isset($_SESSION['id'])) {
            header('Location:homePage.php');
            exit;
        }
    }
}
