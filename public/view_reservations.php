<?php
session_start();
include '../config/config.php'; // Database connection

// Redirect to login if not authenticated
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

// Check if the user is a client or employee
$user_type = $_SESSION['type'];
$user_email = $_SESSION['email'];

// SQL query to fetch reservations
if ($user_type === 'client') {
    // Clients can only see their own reservations
    $sql = "SELECT r.reservation_ID, r.date_debut, r.date_fin, r.etat, r.paiement, h.nom AS hotel_nom 
            FROM reservation r
            JOIN associe a ON r.reservation_ID = a.reservation_ID
            JOIN chambre c ON a.chambre_ID = c.chambre_ID
            JOIN hotel h ON c.hotel_ID = h.hotel_ID
            JOIN client cl ON r.client_NAS = cl.NAS
            WHERE cl.email = $1";
    $params = array($user_email);
} else {
    // Employees can see all reservations
    $sql = "SELECT r.reservation_ID, r.date_debut, r.date_fin, r.etat, r.paiement, h.nom AS hotel_nom 
            FROM reservation r
            JOIN associe a ON r.reservation_ID = a.reservation_ID
            JOIN chambre c ON a.chambre_ID = c.chambre_ID
            JOIN hotel h ON c.hotel_ID = h.hotel_ID";
    $params = array();
}

$result = pg_query_params($conn, $sql, $params);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes Réservations - eHôtels</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <h2>Mes Réservations</h2>

        <?php if (pg_num_rows($result) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>ID Réservation</th>
                        <th>Hôtel</th>
                        <th>Date de Début</th>
                        <th>Date de Fin</th>
                        <th>État</th>
                        <th>Paiement (€)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = pg_fetch_assoc($result)): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['reservation_ID']); ?></td>
                            <td><?php echo htmlspecialchars($row['hotel_nom']); ?></td>
                            <td><?php echo htmlspecialchars($row['date_debut']); ?></td>
                            <td><?php echo htmlspecialchars($row['date_fin']); ?></td>
                            <td><?php echo htmlspecialchars($row['etat']); ?></td>
                            <td><?php echo htmlspecialchars($row['paiement']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Aucune réservation trouvée.</p>
        <?php endif; ?>

        <a href="client_dashboard.php">Retour au tableau de bord</a>
    </div>
</body>
</html>
