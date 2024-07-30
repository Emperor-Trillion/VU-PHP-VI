<?php
//connection to DB
include "config.php";

try {
    $conn = new PDO("mysql:host=$address;dbname=$database", $username, $passwordDB);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $error) {
    echo "Connection Error: " . $error->getMessage();
}
