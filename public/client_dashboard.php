<?php
session_start();
include '../config/config.php';

// Rediriger si l'utilisateur n'est pas connecté
if (!isset($_SESSION['email']) || $_SESSION['type'] !== 'client') {
    header("Location: login.php");
    exit();
}

$email = $_SESSION['email'];

// Récupérer les informations du client
$sql_client = "SELECT nas, nom, prenom, email, adresse FROM client WHERE email = $1";
$result_client = pg_query_params($conn, $sql_client, array($email));
$client = pg_fetch_assoc($result_client);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de Bord - Client</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

    <!-- Barre de navigation -->
    <nav class="navbar">
        <div class="navbar-left">
            <img src="images/logo.png" alt="eHotels Logo" class="logo">
            <h2>eHôtels</h2>
        </div>
        <div class="navbar-right">
            <span>Bienvenue, <?= htmlspecialchars($client['prenom']) ?> !</span>
            <a href="logout.php" class="logout-button">Déconnexion</a>
        </div>
    </nav>

    <!-- Contenu principal -->
    <div class="dashboard-container">
        <div class="dashboard-content-box">
            <h3>Prêt à vivre une nouvelle aventure ?<br>
            Consultez vos réservations ou réservez votre prochain séjour dès maintenant!</h3>

            <div class="dashboard-options">
                <a href="view_reservations.php" class="dashboard-card">
                    <h3>Voir mes réservations</h3>
                </a>

                <a href="new_reservation.php" class="dashboard-card">
                    <h3>Faire une nouvelle réservation</h3>
                </a>
            </div>
        </div>
    </div>
</body>
</html>