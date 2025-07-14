<?php
session_start();
require_once 'db.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $mdp = $_POST['mdp'];

    $stmt = $pdo->prepare("SELECT * FROM membre WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && $mdp === $user['mdp']) {
        $_SESSION['membre'] = $user;
        header('Location: accueil.php');
        exit;
    } else {
        $message = "Identifiants incorrects.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Connexion</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <form method="post" class="bg-white p-6 rounded shadow w-96">
        <h2 class="text-2xl font-bold mb-4 text-center">Connexion</h2>
        <?php if ($message): ?>
            <p class="text-red-500 text-sm mb-4"><?= $message ?></p>
        <?php endif; ?>
        <input name="email" type="email" placeholder="Email" class="w-full border px-3 py-2 mb-4 rounded" required>
        <input name="mdp" type="password" placeholder="Mot de passe" class="w-full border px-3 py-2 mb-4 rounded" required>
        <button type="submit" class="w-full bg-blue-500 text-white py-2 rounded hover:bg-blue-600">Se connecter</button>
        <p class="text-sm text-center mt-4">Pas encore inscrit ? <a href="inscription.php" class="text-blue-600 underline">Créer un compte</a></p>
    </form>
</body>
</html>
