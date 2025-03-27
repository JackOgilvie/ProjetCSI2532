<?php
session_start();
include '../config/config.php';

if (!isset($_SESSION['email']) || $_SESSION['type'] !== 'employe') {
    header("Location: login.php");
    exit();
}

$reservation_id = $_POST['reservation_id'];
$new_etat = $_POST['new_etat'];

// Si l'employé tente d'enregistrer une réservation sans paiement, bloquer
if ($new_etat === 'enregistre') {
    $check_sql = "SELECT paiement FROM reservation WHERE reservation_id = $1";
    $res = pg_query_params($conn, $check_sql, [$reservation_id]);
    $row = pg_fetch_assoc($res);

    if (!$row || $row['paiement'] <= 0) {
        $_SESSION['reservation_error'] = "❌ Effectuer le paiement avant d’enregistrer la réservation.";
        header("Location: employe_reservations.php");
        exit();
    }
}

// Si on confirme, il faut calculer le paiement
if ($new_etat === 'confirme') {
    $sql_total = "
        SELECT SUM(c.prix) AS total
        FROM associe a
        JOIN chambre c ON a.chambre_id = c.chambre_id
        WHERE a.reservation_id = $1
    ";
    $res_total = pg_query_params($conn, $sql_total, [$reservation_id]);
    $row = pg_fetch_assoc($res_total);
    $montant_total = $row['total'] ?? 0;

    if ($montant_total > 0) {
        pg_query_params($conn, "UPDATE reservation SET paiement = $1 WHERE reservation_id = $2", [$montant_total, $reservation_id]);
    } else {
        $_SESSION['reservation_error'] = "❌ Montant invalide pour la réservation.";
        header("Location: employe_reservations.php");
        exit();
    }
}

// Mise à jour de l’état
pg_query_params($conn, "UPDATE reservation SET etat = $1 WHERE reservation_id = $2", [$new_etat, $reservation_id]);

header("Location: employe_reservations.php");
exit();
?>
