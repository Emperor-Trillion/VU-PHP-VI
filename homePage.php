<?php
session_start();
include "connect_db.php";
include "manageUserActions.php";
$GeneratedPassword = null;

if (empty($_SESSION)) {
    echo '<script type="text/javascript">';
    echo 'window.location.href = "./logInPage.html";';
    echo '</script>';
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['generatePassword'])) {
    $passwordLength = isset($_POST['passwordlength']) ? (int)$_POST['passwordlength'] : 12;
    $uppercase = isset($_POST['uppercase']);
    $lowercase = isset($_POST['lowercase']);
    $numbers = isset($_POST['numbers']);
    $specialcharacters = isset($_POST['specialcharacters']);

    $GenPasswordInstance = new GenerateSecurePassword($passwordLength, $uppercase, $lowercase, $numbers, $specialcharacters);
    $GeneratedPassword = $GenPasswordInstance->generateRandomCode();
    $NotifyGenPassword = '<script type="text/javascript">
    alert("Automatically Generated Password is: ' . $GeneratedPassword . '");
    </script>';
    echo $NotifyGenPassword;
}
?>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>HOME PAGE</title>
</head>

<body>
    <h1 align='center'>PASSWORD MANAGER</h1>
    <h3 align='center'> By Sunday Emmanuel Sanni</h3>
    <hr />

    <?php
    try {
        $idNum = $_SESSION['id'];
        $sql = "SELECT COUNT(*) FROM genpasswords WHERE id = :id AND social IS NOT NULL";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $idNum, PDO::PARAM_INT);
        $stmt->execute();
        $count = $stmt->fetchColumn();

        if ($count > 0) {
            echo "<div align='center' style='font-size: 20px;'>
            <table border='1px'>
                <thead>
                    <tr>
                        <th scope='col'>S/N</th>
                        <th scope='col'>Social Account</th>
                        <th scope='col'>Password</th>
                        <th scope='col'>Password Type</th>
                        <th scope='col'>Website</th>
                        <th scope='col'>Time Stamp</th>
                    </tr>
                </thead>
                <tbody>";





            // Fetch the data from database and populate the table rows
            $sql = "SELECT social, password, type, website, TimeStamp FROM genpasswords WHERE id = :id AND social IS NOT NULL";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':id', $idNum, PDO::PARAM_INT);
            $stmt->execute();
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $sn = 1;
            foreach ($results as $row) {
                $decrypt = new SecurePassword($idNum, "empty", $conn);
                $decrypted = $decrypt->decryptPassword($row['password']);
                echo "<tr>
                    <td>{$sn}</td>
                    <td>{$row['social']}</td>
                    <td>    $decrypted  </td>
                    <td>{$row['type']}</td>
                    <td>{$row['website']}</td>
                    <td>{$row['TimeStamp']}</td>
                  </tr>";
                $sn++;
            }

            echo "</tbody>
            </table>
        </div>";
        }
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
    ?>

    <div align='center' style='font-size: 20px;'>
        <p>
        <form method="POST" action="./databaseInteraction.php">
            <input type="text" name="genPassword" placeholder="Password" required /><br><br>
            <input type="text" name="socialAccount" placeholder="Social Account" required /><br><br>
            <input type="url" name="website" placeholder="Website" required /><br><br>
            <input type="submit" name="save" value="SAVE" /><br>
        </form>
        </p>

        <form method="POST" action="./homePage.php">
            <label for="passwordlength">Length of Password</label>
            <input type="number" name="passwordlength" required /><br><br>

            <label for="uppercase">UPPER CASE(A,B,C...Z)</label>
            <input type="checkbox" name="uppercase" checked /><br><br>

            <label for="lowercase">lower case(a,b,c...z)</label>
            <input type="checkbox" name="lowercase" checked /><br><br>

            <label for="numbers">Numbers(1,2,3,...9)</label>
            <input type="checkbox" name="numbers" checked /><br><br>

            <label for="specialcharacters">Special Characters(!&=?#[]{}...)</label>
            <input type="checkbox" name="specialcharacters" checked /><br><br>

            <input type="submit" name="generatePassword" value="GENERATE PASSWORD" /><br><br>
        </form>

        <form method="POST" action="./databaseInteraction.php">
            <input type="submit" name="logout" value="LOGOUT" /><br>
        </form>
    </div>
</body>

</html>