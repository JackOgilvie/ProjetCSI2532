<?php
session_start(); // Démarrer la session
include '../config/config.php'; // Connexion à PostgreSQL

$error = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nas = trim($_POST['nas']);
    $nom = trim($_POST['nom']);
    $prenom = trim($_POST['prenom']);
    $email = trim($_POST['email']);
    $adresse = trim($_POST['adresse']);
    $date_enregistrement = date('Y-m-d'); // Date automatique d'inscription

    if (!empty($nas) && !empty($nom) && !empty($prenom) && !empty($email) && !empty($adresse)) {
        $sql = "INSERT INTO client (NAS, nom, prenom, email, adresse, date_enregistrement) VALUES ($1, $2, $3, $4, $5, $6)";
        $params = array($nas, $nom, $prenom, $email, $adresse, $date_enregistrement);
        
        $result = pg_query_params($conn, $sql, $params);
        
        if ($result) {
            // Redirection vers la page de connexion
            header("Location: login.php");
            exit();
        } else {
            $error = "❌ Erreur lors de l'inscription.";
        }
    } else {
        $error = "❌ Veuillez remplir tous les champs.";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription - eHotels</title>
    <link rel="stylesheet" href="../public/css/style.css">
</head>
<body>
    <div class="container">
        <h2>Inscription Client</h2>
        <?php if (!empty($error)) echo "<p class='error'>$error</p>"; ?>
        <form method="POST" action="">
            <input type="text" name="nas" placeholder="NAS" required>
            <input type="text" name="nom" placeholder="Nom" required>
            <input type="text" name="prenom" placeholder="Prénom" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="text" name="adresse" placeholder="Adresse" required>
            <button type="submit">S'inscrire</button>
        </form>
        <p>Déjà un compte ? <a href="login.php">Connectez-vous ici</a></p>
    </div>
</body>
</html>