<?php
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = $_POST['nom'];
    $email = $_POST['email'];
    $mdp = $_POST['mdp'];
    $ville = $_POST['ville'];
    $genre = $_POST['genre'];
    $naissance = $_POST['naissance'];
    $img = NULL;

    if (!empty($_FILES['image_profil']['name'])) {
        $img = 'uploads/' . uniqid() . '_' . $_FILES['image_profil']['name'];
        move_uploaded_file($_FILES['image_profil']['tmp_name'], $img);
    }

    $stmt = $pdo->prepare("INSERT INTO membre (nom, date_naissance, genre, email, ville, mdp, image_profil) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$nom, $naissance, $genre, $email, $ville, $mdp, $img]);
    header('Location: index.php');
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Inscription</title>
    <link rel="stylesheet" href="style.css">

</head>
<body>

    <h2>Créer un compte</h2>

    <form method="post" enctype="multipart/form-data">
Nom :<input name="nom" type="text" required><br><br>
Email :<input name="email" type="email" required><br><br>
Mot de passe :<input name="mdp" type="password" required><br><br>
Ville :<input name="ville" type="text"><br><br>
Date de naissance :<input name="naissance" type="date" required><br><br>
Genre :<select name="genre">
            <option value="H">Homme</option>
            <option value="F">Femme</option>
        </select><br><br>

        <label>Image de profil :</label><br>
        <input type="file" name="image_profil"><br><br>

        <button type="submit">S'inscrire</button>
    </form>

    <p>Déjà inscrit ? <a href="index.php">Se connecter</a></p>

</body>
</html>
