<?php
session_start();
include '../config/config.php';

if (!isset($_SESSION['email']) || $_SESSION['type'] !== 'client') {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reservation_id'])) {
    $reservation_id = $_POST['reservation_id'];

    // Vérifier que la réservation appartient bien au client
    $sql_check = "
        SELECT r.reservation_id
        FROM reservation r
        JOIN client c ON r.client_nas = c.nas
        WHERE r.reservation_id = $1 AND c.email = $2
    ";
    $res_check = pg_query_params($conn, $sql_check, [$reservation_id, $_SESSION['email']]);

    if (pg_num_rows($res_check) === 1) {
        $sql_annule = "UPDATE reservation SET etat = 'annule' WHERE reservation_id = $1";
        pg_query_params($conn, $sql_annule, [$reservation_id]);
    }

    header("Location: view_reservations.php");
    exit();
}
?>
