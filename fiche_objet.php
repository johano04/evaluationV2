<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['membre'])) {
    header("Location: login.php");
    exit;
}

if (!isset($_GET['id'])) {
    die("Objet non spécifié.");
}

$id_objet = (int) $_GET['id'];

// Récupérer infos objet + catégorie + propriétaire
$stmt = $pdo->prepare("
    SELECT o.*, c.nom_categorie, m.nom AS proprio_nom 
    FROM objet o
    JOIN categorie_objet c ON o.id_categorie = c.id_categorie
    JOIN membre m ON o.id_membre = m.id_membre
    WHERE o.id_objet = ?
");
$stmt->execute([$id_objet]);
$objet = $stmt->fetch();

if (!$objet) {
    die("Objet introuvable.");
}

// Récupérer images
$images = $pdo->prepare("SELECT * FROM images_objet WHERE id_objet = ? ORDER BY id_image ASC");
$images->execute([$id_objet]);
$images_objet = $images->fetchAll();

// Suppression d’une image
if (isset($_GET['delete_image'])) {
    $id_image = (int) $_GET['delete_image'];
    // Récupérer nom fichier
    $q = $pdo->prepare("SELECT nom_image FROM images_objet WHERE id_image = ? AND id_objet = ?");
    $q->execute([$id_image, $id_objet]);
    $img = $q->fetch();

    if ($img) {
        // Supprimer fichier physiquement
        if (file_exists($img['nom_image'])) {
            unlink($img['nom_image']);
        }
        // Supprimer de la BDD
        $del = $pdo->prepare("DELETE FROM images_objet WHERE id_image = ?");
        $del->execute([$id_image]);
        header("Location: fiche_objet.php?id=$id_objet");
        exit;
    }
}

// Historique emprunts
$emprunts = $pdo->prepare("
    SELECT e.*, m.nom AS emprunteur
    FROM emprunt e
    JOIN membre m ON e.id_membre = m.id_membre
    WHERE e.id_objet = ?
    ORDER BY e.date_emprunt DESC
");
$emprunts->execute([$id_objet]);
$historique = $emprunts->fetchAll();

function imagePrincipale($images) {
    return !empty($images) ? $images[0]['nom_image'] : 'img/default.png';
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Fiche objet - <?= htmlspecialchars($objet['nom_objet']) ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1><?= htmlspecialchars($objet['nom_objet']) ?></h1>
        <a href="accueil.php">← Retour à la liste</a>
    </header>

    <div class="container fiche-objet">
        <div class="image-principale">
            <img src="<?= imagePrincipale($images_objet) ?>" alt="Image principale" style="max-width:400px; max-height:400px;">
        </div>

        <div class="galerie-images">
            <?php foreach ($images_objet as $img): ?>
                <div class="miniature">
                    <img src="<?= htmlspecialchars($img['nom_image']) ?>" alt="Image secondaire" style="width:100px; height:100px; object-fit:cover;">
                    <a href="fiche_objet.php?id=<?= $id_objet ?>&delete_image=<?= $img['id_image'] ?>" onclick="return confirm('Supprimer cette image ?');" class="btn-suppr">Supprimer</a>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="infos-objet">
            <p><strong>Catégorie :</strong> <?= htmlspecialchars($objet['nom_categorie']) ?></p>
            <p><strong>Propriétaire :</strong> <?= htmlspecialchars($objet['proprio_nom']) ?></p>
        </div>

        <h2>Historique des emprunts</h2>
        <?php if (count($historique) === 0): ?>
            <p>Aucun emprunt enregistré pour cet objet.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>Emprunteur</th>
                        <th>Date emprunt</th>
                        <th>Date retour</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($historique as $emprunt): ?>
                    <tr>
                        <td><?= htmlspecialchars($emprunt['emprunteur']) ?></td>
                        <td><?= htmlspecialchars($emprunt['date_emprunt']) ?></td>
                        <td><?= $emprunt['date_retour'] ?? 'En cours' ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</body>
</html>
