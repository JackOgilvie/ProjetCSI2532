<?php
session_start(); // Démarrer la session
include '../config/config.php'; // Connexion à PostgreSQL

$error = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    
    if (!empty($email)) {
        // Vérifier si l'utilisateur est un client
        $sql = "SELECT NAS, nom, prenom FROM client WHERE email = $1";
        $result = pg_query_params($conn, $sql, array($email));
        
        if ($result && pg_num_rows($result) > 0) {
            $user = pg_fetch_assoc($result);
            $_SESSION['user_id'] = $user['nas'];
            $_SESSION['user_name'] = $user['nom'] . ' ' . $user['prenom'];
            $_SESSION['role'] = 'client';
            
            // Redirection vers le tableau de bord client
            header("Location: client_dashboard.php");
            exit();
        } else {
            $error = "❌ Email incorrect ou utilisateur non trouvé.";
        }
    } else {
        $error = "❌ Veuillez remplir le champ email.";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - eHotels</title>
    <link rel="stylesheet" href="../public/css/style.css">
</head>
<body>
    <div class="container">
        <h2>Connexion</h2>
        <?php if (!empty($error)) echo "<p class='error'>$error</p>"; ?>
        <form method="POST" action="">
            <input type="email" name="email" placeholder="Email" required>
            <button type="submit">Se connecter</button>
        </form>
        <p>Pas encore de compte ? <a href="register.php">Inscrivez-vous ici</a></p>
    </div>
</body>
</html>
