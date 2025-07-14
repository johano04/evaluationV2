<?php
session_start();
require_once 'db.php';

// Vérifie si l'utilisateur est connecté
if (!isset($_SESSION['membre'])) {
    header("Location: login.php");
    exit;
}

// Récupérer les catégories
$categories = $pdo->query("SELECT * FROM categorie_objet")->fetchAll();

// Filtres
$filtre_cat = isset($_GET['categorie']) ? $_GET['categorie'] : '';
$filtre_nom = isset($_GET['nom']) ? trim($_GET['nom']) : '';
$filtre_dispo = isset($_GET['disponible']) ? true : false;

// Requête de base
$sql = "SELECT o.*, c.nom_categorie, m.nom AS proprio,
           (SELECT date_retour FROM emprunt WHERE id_objet = o.id_objet ORDER BY date_emprunt DESC LIMIT 1) AS date_retour
        FROM objet o
        JOIN categorie_objet c ON o.id_categorie = c.id_categorie
        JOIN membre m ON o.id_membre = m.id_membre
        WHERE 1 ";

$params = [];

// Ajout des filtres
if ($filtre_cat) {
    $sql .= " AND o.id_categorie = ? ";
    $params[] = $filtre_cat;
}

if ($filtre_nom) {
    $sql .= " AND o.nom_objet LIKE ? ";
    $params[] = "%$filtre_nom%";
}

if ($filtre_dispo) {
    $sql .= " AND o.id_objet NOT IN (
        SELECT id_objet FROM emprunt WHERE date_retour IS NULL
    )";
}

$sql .= " ORDER BY o.id_objet DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$objets = $stmt->fetchAll();

// Fonction image principale
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
    <title>Liste des objets</title>
    <link rel="stylesheet" href="style.css"> <!-- tu ajouteras plus tard -->
</head>
<body>
    <header>
        <h1>Bienvenue, <?= $_SESSION['membre']['nom'] ?></h1>
        <a href="index.php">Déconnexion</a>
    </header>

    <div class="container">
        <h2>Objets disponibles</h2>

        <form method="get">
            <label>Catégorie :
                <select name="categorie">
                    <option value="">-- Toutes --</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= $cat['id_categorie'] ?>" <?= ($filtre_cat == $cat['id_categorie']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($cat['nom_categorie']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </label>

            <label>Nom de l’objet :
                <input type="text" name="nom" value="<?= htmlspecialchars($filtre_nom) ?>">
            </label>

            <label>
                <input type="checkbox" name="disponible" <?= $filtre_dispo ? 'checked' : '' ?>>
                Disponible uniquement
            </label>

            <button type="submit">Filtrer</button>
        </form>

        <div class="objet-liste">
            <?php foreach ($objets as $objet): ?>
                <div class="card">
                    <img src="<?= getImage($pdo, $objet['id_objet']) ?>" alt="Image">
                    <div class="card-content">
                        <h3><?= htmlspecialchars($objet['nom_objet']) ?></h3>
                        <p>Catégorie : <?= htmlspecialchars($objet['nom_categorie']) ?></p>
                        <p>Propriétaire : <?= htmlspecialchars($objet['proprio']) ?></p>
                        <?php if ($objet['date_retour'] === null): ?>
                            <p style="color:red;">Actuellement emprunté</p>
                        <?php else: ?>
                            <p>Disponible</p>
                        <?php endif; ?>
                        <a class="btn" href="fiche_objet.php?id=<?= $objet['id_objet'] ?>">Voir détails</a>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <a href="ajouter_objet.php">Ajouter Objet</a>
    </div>
    
</body>
</html>
