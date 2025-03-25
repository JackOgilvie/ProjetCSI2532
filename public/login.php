<?php
session_start(); // Démarrer la session
include '../config/config.php'; // Connexion à PostgreSQL

$error = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (!empty($email) && !empty($password)) {
        // Vérifier si l'utilisateur est un client
        $sql_client = "SELECT mot_de_passe FROM client WHERE email = $1";
        $result_client = pg_query_params($conn, $sql_client, array($email));

        if ($row = pg_fetch_assoc($result_client)) {
            // Vérification du mot de passe haché
            if (password_verify($password, $row['mot_de_passe'])) {
                $_SESSION['email'] = $email;
                $_SESSION['type'] = 'client';
                header("Location: client_dashboard.php"); // Rediriger le client
                exit();
            } else {
                $error = "❌ Mot de passe incorrect.";
            }
        } else {
            // Vérifier si l'utilisateur est un employé
            $sql_employe = "SELECT mot_de_passe, position FROM employe WHERE email = $1";
            $result_employe = pg_query_params($conn, $sql_employe, array($email));

            if ($row = pg_fetch_assoc($result_employe)) {
                // Vérification du mot de passe haché
                if ($password === "Manager123") {
                    $_SESSION['email'] = $email;
                    $_SESSION['type'] = 'employe';
                    $_SESSION['position'] = $row['position'];
                    header("Location: employe_dashboard.php"); // Rediriger l'employé
                    exit();
                } else {
                    $error = "❌ Mot de passe incorrect.";
                }
            } else {
                $error = "❌ Aucun compte trouvé avec cet email.";
            }
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
    <title>Connexion - eHôtels</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <h2>Connexion</h2>
        <?php if (!empty($error)) echo "<p class='error'>$error</p>"; ?>
        <form method="POST" action="">
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Mot de passe" required>
            <button type="submit">Se connecter</button>
        </form>
        <p>Pas encore de compte ? <a href="register.php">Inscrivez-vous ici</a></p>
    </div>
</body>
</html>
