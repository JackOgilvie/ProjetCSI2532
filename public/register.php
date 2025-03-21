<?php
session_start();
include '../config/config.php';

$error = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nas = trim($_POST['nas']);
    $nom = trim($_POST['nom']);
    $prenom = trim($_POST['prenom']);
    $email = trim($_POST['email']);
    $adresse = trim($_POST['adresse']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);
    $date_enregistrement = date('Y-m-d');

    // Vérifier si les mots de passe correspondent
    if ($password !== $confirm_password) {
        $error = "❌ Les mots de passe ne correspondent pas.";
    } else {
        // Hachage du mot de passe avant stockage
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        if (!empty($nas) && !empty($nom) && !empty($prenom) && !empty($email) && !empty($adresse)) {
            $sql = "INSERT INTO client (nas, nom, prenom, email, adresse, mot_de_passe, date_enregistrement) 
                    VALUES ($1, $2, $3, $4, $5, $6, $7)";
            $params = array($nas, $nom, $prenom, $email, $adresse, $hashed_password, $date_enregistrement);
            
            $result = pg_query_params($conn, $sql, $params);
            
            if ($result) {
                header("Location: login.php");
                exit();
            } else {
                $error = "❌ Erreur lors de l'inscription.";
            }
        } else {
            $error = "❌ Veuillez remplir tous les champs.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription - eHôtels</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <h2>Inscription Client</h2>
        <?php if (!empty($error)) echo "<p class='error'>$error</p>"; ?>
        
        <form method="POST" action="">
            <div class="row">
                <div class="form-group">
                    <input type="text" name="nas" placeholder="NAS" required>
                </div>
                <div class="form-group">
                    <input type="email" name="email" placeholder="Email" required>
                </div>
            </div>

            <div class="row">
                <div class="form-group">
                    <input type="text" name="prenom" placeholder="Prénom" required>
                </div>
                <div class="form-group">
                    <input type="text" name="nom" placeholder="Nom" required>
                </div>
            </div>

            <div class="row">
                <div class="form-group full-width">
                    <input type="text" name="adresse" placeholder="Adresse" required>
            </div>

            </div>

            <div class="row">
                <div class="form-group">
                    <input type="password" name="password" placeholder="Mot de passe" required>
                </div>
                <div class="form-group">
                    <input type="password" name="confirm_password" placeholder="Confirmer le mot de passe" required>
                </div>
            </div>

            <button type="submit">S'inscrire</button>
        </form>

        <p>Déjà un compte ? <a href="login.php">Connectez-vous ici</a></p>
    </div>
</body>
</html>
