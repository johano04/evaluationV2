<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['membre'])) {
    header("Location: login.php");
    exit;
}

$id_membre = $_SESSION['membre']['id_membre'];

// Récupérer les emprunts du membre
$sql = "SELECT e.*, o.nom_objet, o.id_objet, c.nom_categorie
        FROM emprunt e
        JOIN objet o ON e.id_objet = o.id_objet
        JOIN categorie_objet c ON o.id_categorie = c.id_categorie
        WHERE e.id_membre = ?
        ORDER BY e.date_emprunt DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute([$id_membre]);
$emprunts = $stmt->fetchAll();

// Fonction pour obtenir l'image principale
function getImage($pdo, $id_objet) {
    $img = $pdo->prepare("SELECT nom_image FROM images_objet WHERE id_objet = ? ORDER BY id_image ASC LIMIT 1");
    $img->execute([$id_objet]);
    $res = $img->fetch();
    return $res ? $res['nom_image'] : 'img/default.png';
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Tout les objects emprunter</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>Tout les objects emprunter</h1>
        <a href="accueil.php">← Retour à l’accueil</a>
    </header>

    <div class="container">
        <?php if (count($emprunts) === 0): ?>
            <p>Vous n’avez encore emprunté aucun objet.</p>
        <?php else: ?>
            <div class="objet-liste">
                <?php foreach ($emprunts as $emprunt): ?>
                    <div class="card">
                        <img src="<?= getImage($pdo, $emprunt['id_objet']) ?>" alt="Image" width="200">
                        <div class="card-content">
                            <h3><?= htmlspecialchars($emprunt['nom_objet']) ?></h3>
                            <p>Catégorie : <?= htmlspecialchars($emprunt['nom_categorie']) ?></p>
                            <p>Date d’emprunt : <?= $emprunt['date_emprunt'] ?></p>
                            <p>Date de retour : <?= $emprunt['date_retour'] ?? 'Non retourné' ?></p>
                            <a href="fiche_objet.php?id=<?= $emprunt['id_objet'] ?>">Voir fiche</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
