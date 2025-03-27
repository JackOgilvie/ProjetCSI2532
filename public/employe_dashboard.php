<?php
session_start();
include '../config/config.php';

// Rediriger si l’utilisateur n’est pas connecté ou pas employé
if (!isset($_SESSION['email']) || $_SESSION['type'] !== 'employe') {
    header("Location: login.php");
    exit();
}

// Récupérer les infos de l'employé connecté
$email = $_SESSION['email'];
$res_emp = pg_query_params($conn, "SELECT employe_id, hotel_id FROM employe WHERE email = $1", [$email]);
$emp = pg_fetch_assoc($res_emp);
$hotel_id = $emp['hotel_id'];

$error = "";
$success = "";

// Info sur la chaine et l'hotel
$sql_info_hotel = "
    SELECT h.nom AS nom_hotel, c.nom AS nom_chaine
    FROM hotel h
    JOIN chaine c ON h.chaine_id = c.chaine_id
    WHERE h.hotel_id = $1
";
$res_info = pg_query_params($conn, $sql_info_hotel, [$hotel_id]);
$info = pg_fetch_assoc($res_info);

$nom_hotel = $info['nom_hotel'];
$nom_chaine = $info['nom_chaine'];

$email = $_SESSION['email'];
$sql_employe = "SELECT nom, prenom FROM employe WHERE email = $1";
$res = pg_query_params($conn, $sql_employe, [$email]);
$employe = pg_fetch_assoc($res);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Tableau de bord - Employé</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <nav class="navbar">
        <div class="navbar-left">
            <img src="images/logo.png" alt="eHotels Logo" class="logo">
            <h2>eHôtels</h2>
        </div>
        <div class="navbar-right">
            <span>Bonjour, <?= htmlspecialchars($employe['prenom']) ?> !</span>
            <a href="logout.php" class="logout-button">Déconnexion</a>
        </div>
    </nav>

    <div class="dashboard-container">
        <div class="dashboard-content-box">
            <h2><?= htmlspecialchars($nom_hotel) ?> (<?= htmlspecialchars($nom_chaine) ?>)</h2>
            <h3>Gérez les réservations des clients facilement!</h3>


            <div class="dashboard-options">
                <a href="employe_reservations.php" class="dashboard-card">
                    <h3>Voir les réservations</h3>
                </a>

                <a href="creer_location.php" class="dashboard-card">
                    <h3>Créer une location immédiate</h3>
                </a>
            </div>
        </div>
    </div>
</body>
</html>
