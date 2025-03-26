<?php
include '../config/config.php';
$result = pg_query($conn, "SELECT * FROM chambres_disponibles_par_zone");
$rows = pg_fetch_all($result);

// Diviser en deux moitiés
$half = ceil(count($rows) / 2);
$left = array_slice($rows, 0, $half);
$right = array_slice($rows, $half);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Chambres Disponibles par Zone</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .tables-wrapper {
            display: flex;
            justify-content: center;
            gap: 40px;
            margin-top: 20px;
        }
        table {
            border-collapse: collapse;
            width: 300px;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <h3>Chambres Disponibles par Zone</h3>
        <div class="tables-wrapper">
            <!-- Tableau de gauche -->
            <table>
                <tr>
                    <th>Zone</th>
                    <th>Chambres</th>
                </tr>
                <?php foreach ($left as $row): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['zone']) ?></td>
                        <td><?= $row['chambres_disponibles'] ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>

            <!-- Tableau de droite -->
            <table>
                <tr>
                    <th>Zone</th>
                    <th>Chambres</th>
                </tr>
                <?php foreach ($right as $row): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['zone']) ?></td>
                        <td><?= $row['chambres_disponibles'] ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>
        <br>
        <a href="new_reservation.php">← Retour</a>
    </div>
</body>
</html>
