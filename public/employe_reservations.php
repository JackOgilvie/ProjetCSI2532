<?php
session_start();
include '../config/config.php';

if (!isset($_SESSION['email']) || $_SESSION['type'] !== 'employe') {
    header("Location: login.php");
    exit();
}

// Récupération des réservations
$sql = "
    SELECT r.reservation_id, r.date_debut, r.date_fin, r.etat, r.paiement,
           c.chambre_id, h.nom AS hotel_nom, cl.prenom, cl.nom AS client_nom
    FROM reservation r
    JOIN associe a ON r.reservation_id = a.reservation_id
    JOIN chambre c ON a.chambre_id = c.chambre_id
    JOIN hotel h ON c.hotel_id = h.hotel_id
    JOIN client cl ON r.client_nas = cl.nas
    ORDER BY r.date_debut DESC
";
$result = pg_query($conn, $sql);
$reservations = pg_fetch_all($result);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Réservations Clients</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
        }
        th, td {
            padding: 12px;
            border: 1px solid #ccc;
            text-align: center;
        }
        .btn-action {
            padding: 6px 10px;
            margin: 2px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .btn-action.confirm {
            background-color: #28a745;
            color: white;
        }
        .btn-action.register {
            background-color: #007bff;
            color: white;
        }
        .btn-action:hover {
            opacity: 0.9;
        }
    </style>
</head>
<body>
<div class="container-register">
    <h2>📋 Réservations des Clients</h2>
    
    <?php if (isset($_SESSION['reservation_error'])): ?>
        <p style="color:red; font-weight:bold;">
            <?= $_SESSION['reservation_error']; unset($_SESSION['reservation_error']); ?>
        </p>
    <?php endif; ?>

    <table>
        <thead>
            <tr>
                <th>Client</th>
                <th>Hôtel</th>
                <th>Date Début</th>
                <th>Date Fin</th>
                <th>État</th>
                <th>Paiement</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($reservations)): ?>
                <tr>
                    <td colspan="6">Aucune réservation en ce moment.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($reservations as $r): ?>
                    <tr>
                        <td><?= htmlspecialchars($r['prenom'] . ' ' . $r['client_nom']) ?></td>
                        <td><?= htmlspecialchars($r['hotel_nom']) ?></td>
                        <td><?= htmlspecialchars($r['date_debut']) ?></td>
                        <td><?= htmlspecialchars($r['date_fin']) ?></td>
                        <td>
                            <?= match ($r['etat']) {
                                'en_attente' => 'En attente',
                                'confirme' => 'Confirmée',
                                'enregistre' => 'Enregistrée',
                                'annule' => 'Annulée',
                                default => ucfirst($r['etat']),
                            } ?>
                        </td>
                        <td>
                            <?= in_array($r['etat'], ['confirme', 'enregistre']) ? "✅ Oui" : "❌ Non" ?>
                        </td>
                        <td>
                            <?php if ($r['etat'] === 'en_attente'): ?>
                                <form method="POST" action="update_etat_reservation.php" style="display:inline;">
                                    <input type="hidden" name="reservation_id" value="<?= $r['reservation_id'] ?>">
                                    <input type="hidden" name="new_etat" value="confirme">
                                    <button type="submit">✔️ Paiement</button>
                                </form>
                                <form method="POST" action="update_etat_reservation.php" style="display:inline;">
                                    <input type="hidden" name="reservation_id" value="<?= $r['reservation_id'] ?>">
                                    <input type="hidden" name="new_etat" value="enregistre">
                                    <button type="submit">🧾 Enregistrer</button>
                                </form>
                            <?php elseif ($r['etat'] === 'confirme'): ?>
                                <form method="POST" action="update_etat_reservation.php" style="display:inline;">
                                    <input type="hidden" name="reservation_id" value="<?= $r['reservation_id'] ?>">
                                    <input type="hidden" name="new_etat" value="enregistre">
                                    <button type="submit">🧾 Enregistrer</button>
                                </form>
                            <?php else: ?>
                                ✅ Déjà enregistrée ou annulée
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>

    <a href="employe_dashboard.php">← Retour au tableau de bord</a>
</div>
</body>
</html>
