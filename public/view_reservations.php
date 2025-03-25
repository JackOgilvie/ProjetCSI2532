<?php
session_start();
include '../config/config.php';

if (!isset($_SESSION['email']) || $_SESSION['type'] !== 'client') {
    header("Location: login.php");
    exit();
}

$sql_client = "SELECT nas FROM client WHERE email = $1";
$result_client = pg_query_params($conn, $sql_client, [$_SESSION['email']]);

if (!$result_client || pg_num_rows($result_client) === 0) {
    die("❌ Client introuvable.");
}
$client = pg_fetch_assoc($result_client);
$nas = $client['nas'];

// Récupérer les réservations du client
$sql = "
    SELECT r.reservation_id, r.date_debut, r.date_fin, r.paiement, r.etat,
           c.chambre_id, c.commodites, c.vue, c.capacite, c.extensible,
           h.nom AS hotel_nom, h.adresse AS hotel_adresse, h.categorie AS hotel_categorie
    FROM reservation r
    JOIN associe a ON r.reservation_id = a.reservation_id
    JOIN chambre c ON a.chambre_id = c.chambre_id
    JOIN hotel h ON c.hotel_id = h.hotel_id
    WHERE r.client_nas = $1
    ORDER BY r.date_debut DESC
";
$result = pg_query_params($conn, $sql, [$nas]);
$reservations = pg_fetch_all($result);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mes Réservations</title>
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
    </style>
    <script>
        function getCapaciteLabel(cap) {
            switch (parseInt(cap)) {
                case 1: return "Simple";
                case 2: return "Double";
                case 3: return "Triple";
                case 4: return "Studio";
                case 5: return "Suite";
                default: return cap;
            }
        }

        function showInfo(data) {
            const modalContent = `
                <h3>🛏️ Chambre ${data.chambre_id}</h3>
                <p><strong>Commodités :</strong> ${data.commodites}</p>
                <p><strong>Vue :</strong> ${data.vue}</p>
                <p><strong>Capacité :</strong> ${getCapaciteLabel(data.capacite)}</p>
                <p><strong>Extensible :</strong> ${data.extensible === 't' ? 'Oui' : 'Non'}</p>
                <hr>
                <h4>🏨 Hôtel</h4>
                <p><strong>Nom :</strong> ${data.hotel_nom}</p>
                <p><strong>Adresse :</strong> ${data.hotel_adresse}</p>
                <p><strong>Catégorie :</strong> ${data.hotel_categorie} ★</p>
            `;
            document.getElementById("modal-body").innerHTML = modalContent;
            document.getElementById("infoModal").style.display = "block";
        }

        function closeModal() {
            document.getElementById("infoModal").style.display = "none";
        }
    </script>
</head>
<body>
    <div class="container-register">
        <h2>Mes Réservations</h2>
        <table>
            <thead>
                <tr>
                    <th>ID Réservation</th>
                    <th>Hôtel</th>
                    <th>Date de Début</th>
                    <th>Date de Fin</th>
                    <th>État</th>
                    <th>Paiement</th>
                    <th>Info</th>
                    <th>Annuler</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($reservations): ?>
                    <?php foreach ($reservations as $r): ?>
                        <tr>
                            <td><?= htmlspecialchars($r['reservation_id']) ?></td>
                            <td><?= htmlspecialchars($r['hotel_nom']) ?></td>
                            <td><?= htmlspecialchars($r['date_debut']) ?></td>
                            <td><?= htmlspecialchars($r['date_fin']) ?></td>
                            <td>
                                <?php
                                    // Afficher l’état en version lisible
                                    $etat_lisible = match ($r['etat']) {
                                        'en_attente' => 'En attente',
                                        'confirme' => 'Confirmée',
                                        'enregistre' => 'Enregistrée',
                                        'annule' => 'Annulée',
                                        default => ucfirst($r['etat']),
                                    };
                                    echo htmlspecialchars($etat_lisible);
                                ?>
                            </td>
                            <td>
                                <?= in_array($r['etat'], ['confirme', 'enregistre']) ? "✅ Oui" : "❌ Non" ?>
                            </td>
                            <td>
                                <button onclick='showInfo(<?= json_encode($r) ?>)'>Info</button>
                            </td>
                            <td>
                                <?php if ($r['etat'] !== 'annule'): ?>
                                    <form method="POST" action="annuler_reservation.php" onsubmit="return confirm('Êtes-vous sûr de vouloir annuler cette réservation ?');">
                                        <input type="hidden" name="reservation_id" value="<?= $r['reservation_id'] ?>">
                                        <button type="submit">Annuler</button>
                                    </form>
                                <?php else: ?>
                                    ❌
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="7">Aucune réservation trouvée.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
        <a href="client_dashboard.php">← Retour au tableau de bord</a>
    </div>

    <div id="infoModal" style="display:none; position:fixed; top:10%; left:10%; width:80%; background:white; border:1px solid #ccc; padding:20px; box-shadow:0 0 15px rgba(0,0,0,0.5); z-index:1000;">
        <button style="float:right;" onclick="closeModal()">✖</button>
        <div id="modal-body"></div>
    </div>
</body>
</html>
