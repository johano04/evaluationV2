<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['membre'])) {
    header("Location: login.php");
    exit;
}

if (!isset($_GET['id'])) {
    die("Membre non spécifié.");
}

$id_membre = (int) $_GET['id'];

// Récupérer infos membre
$stmt = $pdo->prepare("SELECT * FROM membre WHERE id_membre = ?");
$stmt->execute([$id_membre]);
$membre = $stmt->fetch();

if (!$membre) {
    die("Membre introuvable.");
}

// Récupérer catégories pour regroupement
$categories = $pdo->query("SELECT * FROM categorie_objet")->fetchAll();

// Récupérer objets du membre, triés par catégorie
$objets = $pdo->prepare("
    SELECT o.*, c.nom_categorie
    FROM objet o
    JOIN categorie_objet c ON o.id_categorie = c.id_categorie
    WHERE o.id_membre = ?
    ORDER BY c.nom_categorie, o.nom_objet
");
$objets->execute([$id_membre]);
$objets_membre = $objets->fetchAll(PDO::FETCH_GROUP|PDO::FETCH_UNIQUE);

// Pour organiser objets par catégorie, on va regrouper manuellement :
$objets_par_categorie = [];
foreach ($objets_membre as $objet) {
    $objets_par_categorie[$objet['nom_categorie']][] = $objet;
}

// Fonction image principale pour objet
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
    <title>Fiche membre - <?= htmlspecialchars($membre['nom']) ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<header>
    <h1>Profil de <?= htmlspecialchars($membre['nom']) ?></h1>
    <a href="accueil.php">← Retour à la liste</a>
</header>

<div class="container fiche-membre">
    <div class="infos-membre">
        <img src="<?= $membre['image_profil'] ?: 'img/default.png' ?>" alt="Photo profil" style="max-width:150px; border-radius:50%; margin-bottom:15px;">
        <p><strong>Email :</strong> <?= htmlspecialchars($membre['email']) ?></p>
        <p><strong>Ville :</strong> <?= htmlspecialchars($membre['ville']) ?></p>
        <p><strong>Genre :</strong> <?= $membre['genre'] === 'H' ? 'Homme' : 'Femme' ?></p>
        <p><strong>Date de naissance :</strong> <?= htmlspecialchars($membre['date_naissance']) ?></p>
    </div>

    <h2>Objets de ce membre</h2>

    <?php if (empty($objets_par_categorie)): ?>
        <p>Ce membre n’a pas d’objets enregistrés.</p>
    <?php else: ?>
        <?php foreach ($objets_par_categorie as $categorie => $objets): ?>
            <section class="categorie-section">
                <h3><?= htmlspecialchars($categorie) ?></h3>
                <div class="objets-list">
                    <?php foreach ($objets as $objet): ?>
                        <div class="card">
                            <img src="<?= getImage($pdo, $objet['id_objet']) ?>" alt="Image objet" style="width:150px; height:150px; object-fit:cover;">
                            <h4><?= htmlspecialchars($objet['nom_objet']) ?></h4>
                            <a href="fiche_objet.php?id=<?= $objet['id_objet'] ?>" class="btn">Voir détails</a>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>
        <?php endforeach; ?>
    <?php endif; ?>
</div>
</body>
</html>
