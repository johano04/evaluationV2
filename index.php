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
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <div class="container">
        <form method="post">
            <h2>Connexion</h2>

            <?php if ($message): ?>
                <p style="color: red;"><?= $message ?></p>
            <?php endif; ?>

            <label>Email :</label>
            <input name="email" type="email" placeholder="Email" required>

            <label>Mot de passe :</label>
            <input name="mdp" type="password" placeholder="Mot de passe" required>

            <button type="submit" class="btn">Se connecter</button>

            <p style="margin-top: 10px;">Pas encore inscrit ? <a href="inscription.php">Créer un compte</a></p>
        </form>
    </div>

</body>
</html>
