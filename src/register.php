<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom = htmlspecialchars($_POST['nom']);
    $email = htmlspecialchars($_POST['email']);
    echo "<p class='message'>✅ Inscription réussie pour $nom ($email)</p>";
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Inscription - eHotels</title>
    <link rel="stylesheet" type="text/css" href="css/style.css">
</head>
<body>
    <div class="container">
        <h2>Inscription</h2>
        <form method="POST">
            <input type="text" name="nom" placeholder="Nom" required><br>
            <input type="email" name="email" placeholder="Email" required><br>
            <input type="password" name="password" placeholder="Mot de passe" required><br>
            <button type="submit">S'inscrire</button>
        </form>
        <p><a href="login.php">Déjà inscrit ? Connectez-vous</a></p>
        <p><a href="index.php">Retour à l'accueil</a></p>
    </div>
</body>
</html>
