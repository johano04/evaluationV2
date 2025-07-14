<?php
session_start();
require_once 'db.php';

// Rediriger si non connecté
if (!isset($_SESSION['membre'])) {
    header("Location: login.php");
    exit;
}

$id_membre = $_SESSION['membre']['id_membre'];

// Récupérer les catégories
$categories = $pdo->query("SELECT * FROM categorie_objet")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom_objet = $_POST['nom_objet'];
    $id_categorie = $_POST['id_categorie'];

    // Insertion dans la table objet
    $stmt = $pdo->prepare("INSERT INTO objet (nom_objet, id_categorie, id_membre) VALUES (?, ?, ?)");
    $stmt->execute([$nom_objet, $id_categorie, $id_membre]);

    $id_objet = $pdo->lastInsertId();

    // Gestion des images (si présentes)
    if (!empty($_FILES['images']['name'][0])) {
        foreach ($_FILES['images']['tmp_name'] as $key => $tmp_name) {
            if ($_FILES['images']['error'][$key] === UPLOAD_ERR_OK) {
                $name = basename($_FILES['images']['name'][$key]);
                $newName = uniqid() . '_' . $name;
                $destination = 'uploads/' . $newName;

                move_uploaded_file($tmp_name, $destination);

                $insert = $pdo->prepare("INSERT INTO images_objet (id_objet, nom_image) VALUES (?, ?)");
                $insert->execute([$id_objet, $destination]);
            }
        }
    }

    header("Location: accueil.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Ajouter un objet</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>Ajouter un objet</h1>
        <a href="accueil.php">← Retour</a>
    </header>

    <div class="container">
        <form method="post" enctype="multipart/form-data">
            <label for="nom_objet">Nom de l’objet :</label>
            <input type="text" name="nom_objet" id="nom_objet" required>

            <label for="id_categorie">Catégorie :</label>
            <select name="id_categorie" id="id_categorie" required>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?= $cat['id_categorie'] ?>"><?= htmlspecialchars($cat['nom_categorie']) ?></option>
                <?php endforeach; ?>
            </select>

            <label for="images">Images (vous pouvez en choisir plusieurs) :</label>
            <input type="file" name="images[]" id="images" multiple accept="image/*">

            <button type="submit" class="btn">Ajouter l’objet</button>
        </form>
    </div>
</body>
</html>
