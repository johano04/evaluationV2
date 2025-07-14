<?php
$host = 'localhost';
$dbname = 'db_s2_ETU004256';
$user = 'root';
$pass = ''; // adapte si besoin

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (Exception $e) {
    die('Erreur connexion : ' . $e->getMessage());
}
?>
