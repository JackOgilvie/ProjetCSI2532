<?php
session_start();
include '../config/config.php';

if (!isset($_SESSION['email']) || $_SESSION['type'] !== 'employe') {
    header("Location: login.php");
    exit();
}

// Info employé
$email = $_SESSION['email'];
$res_emp = pg_query_params($conn, "SELECT employe_id, hotel_id FROM employe WHERE email = $1", [$email]);
$emp = pg_fetch_assoc($res_emp);
$hotel_id = $emp['hotel_id'];

$error = "";
$success = "";

// Info sur l’hôtel et la chaîne
$sql_info_hotel = "
    SELECT h.nom AS nom_hotel, c.nom AS nom_chaine
    FROM hotel h
    JOIN chaine c ON h.chaine_id = c.chaine_id
    WHERE h.hotel_id = $1
";
$res_info = pg_query_params($conn, $sql_info_hotel, [$hotel_id]);
$info = pg_fetch_assoc($res_info);
$nom_hotel = $info['nom_hotel'] ?? '';
$nom_chaine = $info['nom_chaine'] ?? '';

// Dates du filtre
$date_debut = $_GET['date_debut'] ?? '';
$date_fin = $_GET['date_fin'] ?? '';
$chambres = [];

if ($date_debut && $date_fin) {
    if ($date_debut >= $date_fin) {
        $error = "❌ La date de fin doit être après la date de début.";
    } else {
    // Récupérer les chambres disponibles à l’hôtel de l’employé
    $sql_chambres = "
        SELECT c.*
        FROM chambre c
        WHERE c.hotel_id = $1
        AND NOT EXISTS (
            SELECT 1
            FROM associe a
            JOIN reservation r ON a.reservation_id = r.reservation_id
            WHERE a.chambre_id = c.chambre_id
            AND r.etat IN ('en_attente', 'confirme', 'enregistre')
            AND daterange(r.date_debut, r.date_fin, '[]') && daterange($2, $3, '[]')
        )
        ORDER BY c.chambre_id
    ";
    $res_chambres = pg_query_params($conn, $sql_chambres, [$hotel_id, $date_debut, $date_fin]);
    $chambres = pg_fetch_all($res_chambres);
    }
}

// Créer réservation immédiate
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $chambre_id = $_POST['chambre_id'];
    $nas_client = $_POST['nas_client'];
    $date_debut_post = $_POST['date_debut'];
    $date_fin_post = $_POST['date_fin'];

    if ($date_debut_post >= $date_fin_post) {
        $error = "❌ La date de fin doit être après la date de début.";
    } else {
        $res_client = pg_query_params($conn, "SELECT nas FROM client WHERE nas = $1", [$nas_client]);
        if (!pg_fetch_assoc($res_client)) {
            $error = "❌ Ce client n'existe pas. <a href='register.php'>Créer un compte client</a>";
        } else {
            $sql = "INSERT INTO reservation (date_debut, date_fin, etat, paiement, client_nas)
                    VALUES ($1, $2, 'enregistre', 0, $3) RETURNING reservation_id";
            $res_insert = pg_query_params($conn, $sql, [$date_debut_post, $date_fin_post, $nas_client]);

            if ($row = pg_fetch_assoc($res_insert)) {
                $res_id = $row['reservation_id'];
                pg_query_params($conn, "INSERT INTO associe (chambre_id, reservation_id) VALUES ($1, $2)", [$chambre_id, $res_id]);
                pg_query_params($conn, "INSERT INTO gere (employe_id, reservation_id) VALUES ($1, $2)", [$emp['employe_id'], $res_id]);

                $success = "✅ Location créée avec succès pour le client NAS $nas_client.";
            } else {
                $error = "❌ Erreur lors de la création de la réservation.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Créer une Location Immédiate</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .scroll-table { max-height: 300px; overflow-y: auto; margin-top: 20px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 12px; border: 1px solid #ccc; text-align: center; }
        thead th { position: sticky; top: 0; }
    </style>
</head>
<body>
    <div class="container-register">
        <h3>Créer une location immédiate - <?= htmlspecialchars($nom_hotel) ?> (<?= htmlspecialchars($nom_chaine) ?>)</h3>

        <form method="GET">
            <div class="filter-row">
                <div class="filter-group">
                    <label for="date_debut">Date début :</label>
                    <input type="date" name="date_debut" id="date_debut" value="<?= htmlspecialchars($date_debut) ?>" required>
                </div>
                <div class="filter-group">
                    <label for="date_fin">Date fin :</label>
                    <input type="date" name="date_fin" id="date_fin" value="<?= htmlspecialchars($date_fin) ?>" required>
                </div>
                <div class="filter-group">
                    <label>&nbsp;</label>
                    <button type="submit">Afficher les chambres</button>
                </div>
            </div>
        </form>


        <?php if ($error): ?><p class="error"><?= $error ?></p><?php endif; ?>
        <?php if ($success): ?><p class="success"><?= $success ?></p><?php endif; ?>

        <div class="scroll-table">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Capacité</th>
                        <th>Vue</th>
                        <th>Extensible</th>
                        <th>Commodités</th>
                        <th>NAS Client</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($chambres)): ?>
                        <?php foreach ($chambres as $ch): ?>
                            <tr>
                                <form method="POST">
                                    <td><?= $ch['chambre_id'] ?></td>
                                    <td><?= match ((int)$ch['capacite']) {
                                        1 => "Simple", 2 => "Double", 3 => "Triple", 4 => "Studio", 5 => "Suite", default => $ch['capacite']
                                    } ?></td>
                                    <td><?= htmlspecialchars($ch['vue']) ?></td>
                                    <td><?= $ch['extensible'] === 't' ? 'Oui' : 'Non' ?></td>
                                    <td><?= htmlspecialchars($ch['commodites']) ?></td>
                                    <td>
                                        <input type="hidden" name="chambre_id" value="<?= $ch['chambre_id'] ?>">
                                        <input type="hidden" name="date_debut" value="<?= $date_debut ?>">
                                        <input type="hidden" name="date_fin" value="<?= $date_fin ?>">
                                        <input type="text" name="nas_client" placeholder="NAS" required pattern="\d{9}" maxlength="9" style="width: 100px;">
                                    </td>
                                    <td><button type="submit">📋 Enregistrer</button></td>
                                </form>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="7">Veuillez choisir une plage de dates pour voir les chambres disponibles.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <a href="employe_dashboard.php">← Retour</a>
    </div>
</body>
</html>
