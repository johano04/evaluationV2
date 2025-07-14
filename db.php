<?php
$host = '172.60.0.11';
$dbname = 'db_s2_ETU004121';
$user = 'ETU004121';
$pass = 'qKB9KBNc';



try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (Exception $e) {
    die('Erreur connexion : ' . $e->getMessage());
}
?>
