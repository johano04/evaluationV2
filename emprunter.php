<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['membre'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_objet'], $_POST['duree'])) {
    $id_objet = (int) $_POST['id_objet'];
    $duree = (int) $_POST['duree'];
    $id_membre = $_SESSION['membre']['id_membre'];

    // Vérifie si l’objet est déjà emprunté
    $verif = $pdo->prepare("SELECT * FROM emprunt WHERE id_objet = ? AND date_retour IS NULL");
    $verif->execute([$id_objet]);

    if ($verif->rowCount() > 0) {
        echo "Cet objet est déjà emprunté.";
        exit;
    }

    $date_emprunt = date('Y-m-d');
    $date_retour = date('Y-m-d', strtotime("+$duree days"));

    $stmt = $pdo->prepare("INSERT INTO emprunt (id_objet, id_membre, date_emprunt, date_retour) VALUES (?, ?, ?, ?)");
    $stmt->execute([$id_objet, $id_membre, $date_emprunt, $date_retour]);

    // Retour à la liste
    header("Location: accueil.php");
    exit;
} else {
    echo "Erreur : données manquantes ou méthode non autorisée.";
}
