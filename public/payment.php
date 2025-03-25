<?php
session_start();
include '../config/config.php';

if (!isset($_GET['reservation_id'])) {
    header("Location: client_dashboard.php");
    exit();
}

$reservation_id = $_GET['reservation_id'];

// Récupérer le montant total à payer
$sql_total = "
    SELECT SUM(c.prix) AS total
    FROM associe a
    JOIN chambre c ON a.chambre_id = c.chambre_id
    WHERE a.reservation_id = $1
";
$res_total = pg_query_params($conn, $sql_total, [$reservation_id]);
$row = pg_fetch_assoc($res_total);
$montant_total = $row['total'] ?? 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($_POST['mode'] === 'carte') {
        if ($montant_total > 0) {
            // Étape 1 : Mettre à jour le paiement SEULEMENT
            $sql_update_paiement = "UPDATE reservation SET paiement = $1 WHERE reservation_id = $2";
            $res1 = pg_query_params($conn, $sql_update_paiement, [$montant_total, $reservation_id]);

            // Étape 2 : Puis mettre à jour l'état
            $sql_update_etat = "UPDATE reservation SET etat = 'confirme' WHERE reservation_id = $1";
            $res2 = pg_query_params($conn, $sql_update_etat, [$reservation_id]);

            if (!$res1 || !$res2) {
                error_log("❌ Erreur SQL dans l'une des étapes : " . pg_last_error($conn));
                die("❌ Erreur lors du paiement.");
            }
        } else {
            die("❌ Impossible de confirmer : montant invalide.");
        }
    } else {
        // Paiement à l'hôtel
        $sql_update = "UPDATE reservation SET paiement = 0, etat = 'en_attente' WHERE reservation_id = $1";
        $res_update = pg_query_params($conn, $sql_update, [$reservation_id]);

        if (!$res_update) {
            error_log("❌ Erreur SQL paiement sur place : " . pg_last_error($conn));
            die("❌ Erreur lors de l'enregistrement.");
        }
    }

    header("Location: view_reservations.php");
    exit();
}
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Paiement - eHôtels</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <h2>Paiement de la Réservation</h2>
        <p><strong>Montant total :</strong> <?= number_format($montant_total, 2) ?> $</p>

        <form method="POST">
            <div>
                <h3>Option 1 : Carte de crédit</h3>
                <input type="hidden" name="mode" value="carte">
                <label for="carte">Numéro de carte :</label>
                <input type="text" id="carte" name="carte" placeholder="1234 5678 9012 3456" pattern="\d{16}" maxlength="16" required>
                <button type="submit">Payer maintenant</button>
            </div>
        </form>

        <hr>

        <form method="POST">
            <div>
                <h3>Option 2 : Payer à l'hôtel</h3>
                <input type="hidden" name="mode" value="hotel">
                <button type="submit">Choisir cette option</button>
            </div>
        </form>
    </div>
</body>
</html>
