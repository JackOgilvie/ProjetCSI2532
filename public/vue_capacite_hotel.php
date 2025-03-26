<?php
include '../config/config.php';

$chaine_id = $_GET['chaine_id'] ?? null;
$hotel_id = $_GET['hotel_id'] ?? null;

$result = null;
$filters_valid = is_numeric($chaine_id);

// Charger toutes les chaînes
$res_chaines = pg_query($conn, "SELECT chaine_id, nom FROM chaine");

// Charger les hôtels de la chaîne sélectionnée
$hotels = [];
if ($filters_valid) {
    $res_hotels = pg_query_params($conn, "SELECT hotel_id, nom FROM hotel WHERE chaine_id = $1", [$chaine_id]);
    $hotels = pg_fetch_all($res_hotels);
}

// Trouver les chambres et capacités
if ($filters_valid) {
    $sql = "
        SELECT h.nom AS nom_hotel, c.chambre_id, c.capacite
        FROM chambre c
        JOIN hotel h ON c.hotel_id = h.hotel_id
        WHERE h.chaine_id = $1
    ";
    $params = [$chaine_id];

    if (!empty($hotel_id) && is_numeric($hotel_id)) {
        $sql .= " AND h.hotel_id = $2";
        $params[] = $hotel_id;
    }

    $sql .= " ORDER BY h.nom, c.chambre_id";
    $result = pg_query_params($conn, $sql, $params);
}
?>



<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Capacités des Chambres par Hôtel</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .scroll-table thead th {
            position: sticky;
            top: 0;
            z-index: 1;
        }

        .filter-row {
            display: flex;
            gap: 20px;
            margin-bottom: 20px;
            align-items: end;
        }

        .filter-group label {
            display: block;
            margin-bottom: 5px;
        }

        .scroll-table {
            max-height: 300px;
            overflow-y: auto;
            margin-top: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 12px;
            border: 1px solid #ccc;
            text-align: center;
        }

        button {
            padding: 8px 16px;
            background-color: #007b5e;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background-color: #005f48;
        }
    </style>
</head>
<body>
    <div class="container-register">
        <h3>Capacités des Chambres par Hôtel</h3>

        <form method="GET">
            <div class="filter-row">
                <!-- Filtre chaîne -->
                <div class="filter-group">
                    <label for="chaine_id">Chaîne d'hôtel :</label>
                    <select name="chaine_id" id="chaine_id" required>
                        <option value="">-- Choisir --</option>
                        <?php while ($row = pg_fetch_assoc($res_chaines)): ?>
                            <option value="<?= $row['chaine_id'] ?>" <?= $row['chaine_id'] == $chaine_id ? 'selected' : '' ?>>
                                <?= htmlspecialchars($row['nom']) ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <!-- Filtre hôtel (si chaîne choisie) -->
                <?php if (!empty($hotels)): ?>
                    <div class="filter-group">
                        <label for="hotel_id">Hôtel :</label>
                        <select name="hotel_id" id="hotel_id">
                            <option value="">Tous</option>
                            <?php foreach ($hotels as $h): ?>
                                <option value="<?= $h['hotel_id'] ?>" <?= $h['hotel_id'] == $hotel_id ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($h['nom']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                <?php endif; ?>

                <!-- Bouton appliquer -->
                <div class="filter-group">
                    <button type="submit">Appliquer</button>
                </div>
            </div>
        </form>

        <div class="scroll-table">
            <table>
                <thead>
                    <tr>
                        <th>Nom de l'Hôtel</th>
                        <th>ID Chambre</th>
                        <th>Capacité</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result && pg_num_rows($result) > 0): ?>
                        <?php while ($row = pg_fetch_assoc($result)): ?>
                            <tr>
                                <td><?= htmlspecialchars($row['nom_hotel']) ?></td>
                                <td><?= $row['chambre_id'] ?></td>
                                <td>
                                    <?php
                                        switch ((int)$row['capacite']) {
                                            case 1: echo "Simple"; break;
                                            case 2: echo "Double"; break;
                                            case 3: echo "Triple"; break;
                                            case 4: echo "Studio"; break;
                                            case 5: echo "Suite"; break;
                                            default: echo htmlspecialchars($row['capacite']);
                                        }
                                    ?>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="3">Veuillez sélectionner une chaîne et/ou un hôtel pour voir les résultats.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <a href="new_reservation.php">← Retour</a>
    </div>

    <script>
        // Quand la chaîne change, on remet "Tous" pour l'hôtel
        document.getElementById("chaine_id").addEventListener("change", function () {
            const hotelSelect = document.getElementById("hotel_id");
            if (hotelSelect) {
                hotelSelect.selectedIndex = 0;
            }
        });
    </script>

</body>
</html>
