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
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Inscription</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <form method="post" enctype="multipart/form-data" class="bg-white p-6 rounded shadow w-96">
        <h2 class="text-2xl font-bold mb-4 text-center">Créer un compte</h2>
        <input name="nom" type="text" placeholder="Nom" class="w-full border px-3 py-2 mb-3 rounded" required>
        <input name="email" type="email" placeholder="Email" class="w-full border px-3 py-2 mb-3 rounded" required>
        <input name="mdp" type="password" placeholder="Mot de passe" class="w-full border px-3 py-2 mb-3 rounded" required>
        <input name="ville" type="text" placeholder="Ville" class="w-full border px-3 py-2 mb-3 rounded">
        <input name="naissance" type="date" class="w-full border px-3 py-2 mb-3 rounded" required>
        <select name="genre" class="w-full border px-3 py-2 mb-3 rounded">
            <option value="H">Homme</option>
            <option value="F">Femme</option>
        </select>
        <input type="file" name="image_profil" class="w-full border px-3 py-2 mb-3 rounded">
        <button type="submit" class="w-full bg-green-500 text-white py-2 rounded hover:bg-green-600">S'inscrire</button>
        <p class="text-sm text-center mt-4">Déjà inscrit ? <a href="login.php" class="text-blue-600 underline">Se connecter</a></p>
    </form>
</body>
</html>
