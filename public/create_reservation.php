<?php
session_start();
include '../config/config.php';

if (!isset($_SESSION['email']) || $_SESSION['type'] !== 'client') {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $chambre_ID = $_POST['chambre_ID'];
    $date_debut = $_POST['date_debut'];
    $date_fin = $_POST['date_fin'];

    // Get client NAS
    $sql_client = "SELECT nas FROM client WHERE email = $1";
    $res_client = pg_query_params($conn, $sql_client, [$_SESSION['email']]);
    if ($row = pg_fetch_assoc($res_client)) {
        $client_nas = $row['nas'];

        if ($date_debut >= $date_fin) {
            die("❌ Dates invalides.");
        }

        // Create reservation
        $sql_insert = "INSERT INTO reservation (date_debut, date_fin, etat, paiement, client_nas) 
                       VALUES ($1, $2, 'En attente', 0, $3) RETURNING reservation_ID";
        $res_insert = pg_query_params($conn, $sql_insert, [$date_debut, $date_fin, $client_nas]);

        if ($row_insert = pg_fetch_assoc($res_insert)) {
            $reservation_ID = $row_insert['reservation_id'];

            // Associate room
            $sql_assoc = "INSERT INTO associe (chambre_ID, reservation_ID) VALUES ($1, $2)";
            pg_query_params($conn, $sql_assoc, [$chambre_ID, $reservation_ID]);

            // ✅ Redirect to payment
            header("Location: payment.php?reservation_id=" . $reservation_ID);
            exit();
        } else {
            die("❌ Erreur lors de la réservation.");
        }
    } else {
        die("❌ Client non trouvé.");
    }
}
?>
