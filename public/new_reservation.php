<?php
session_start();
include '../config/config.php';

// Rediriger si l'utilisateur n'est pas connecté
if (!isset($_SESSION['email']) || $_SESSION['type'] !== 'client') {
    header("Location: login.php");
    exit();
}

$email = $_SESSION['email'];

// Récupérer les informations du client
$sql_client = "SELECT nas FROM client WHERE email = $1";
$result_client = pg_query_params($conn, $sql_client, array($_SESSION['email']));

if ($result_client && pg_num_rows($result_client) > 0) {
    $client = pg_fetch_assoc($result_client);
    
    // Debug output
    echo "<pre>";
    print_r($client);
    echo "</pre>";

    if (isset($client['nas'])) {
        $client_NAS = $client['nas'];
        echo "✅ NAS récupéré: " . htmlspecialchars($client_NAS); // Debugging output
    } else {
        die("❌ Erreur: Clé 'nas' non trouvée dans la réponse SQL.");
    }
} else {
    die("❌ Erreur: Aucun client trouvé avec cet email.");
}






// Récupérer la liste des hôtels
$sql_hotels = "SELECT hotel_ID, nom FROM hotel";
$result_hotels = pg_query($conn, $sql_hotels);

// Gestion du formulaire
$error = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $hotel_ID = $_POST['hotel_ID'];
    $chambre_ID = $_POST['chambre_ID'];
    $date_debut = $_POST['date_debut'];
    $date_fin = $_POST['date_fin'];

    // Vérifier que les dates sont valides
    if ($date_debut >= $date_fin) {
        $error = "❌ La date de fin doit être après la date de début.";
    } else {
        // Insérer la réservation
        $sql_insert = "INSERT INTO reservation (date_debut, date_fin, etat, paiement, client_NAS) 
                       VALUES ($1, $2, 'En attente', 0, $3) RETURNING reservation_ID";
        $params = array($date_debut, $date_fin, $client_NAS);
        $result_insert = pg_query_params($conn, $sql_insert, $params);

        if ($row = pg_fetch_assoc($result_insert)) {
            $reservation_ID = $row['reservation_ID'];

            // Associer la réservation à la chambre choisie
            $sql_associe = "INSERT INTO associe (chambre_ID, reservation_ID) VALUES ($1, $2)";
            pg_query_params($conn, $sql_associe, array($chambre_ID, $reservation_ID));

            header("Location: view_reservations.php");
            exit();
        } else {
            $error = "❌ Erreur lors de la réservation.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nouvelle Réservation - eHôtels</title>
    <link rel="stylesheet" href="css/style.css">
    <script>
        function loadRooms(hotelID) {
            if (hotelID === "") {
                document.getElementById("roomSelect").innerHTML = "<option value=''>Sélectionnez un hôtel d'abord</option>";
                return;
            }
            
            fetch("get_rooms.php?hotel_ID=" + hotelID)
                .then(response => response.text())
                .then(data => {
                    document.getElementById("roomSelect").innerHTML = data;
                });
        }
    </script>
</head>
<body>
    <div class="container">
        <h2>Faire une Nouvelle Réservation</h2>
        <?php if (!empty($error)) echo "<p class='error'>$error</p>"; ?>

        <form method="POST" action="">
            <label for="hotel">Choisir un Hôtel :</label>
            <select name="hotel_ID" id="hotel" onchange="loadRooms(this.value)" required>
                <option value="">Sélectionnez un hôtel</option>
                <?php while ($hotel = pg_fetch_assoc($result_hotels)): ?>
                    <option value="<?= $hotel['hotel_ID'] ?>"><?= htmlspecialchars($hotel['nom']) ?></option>
                <?php endwhile; ?>
            </select>

            <label for="roomSelect">Choisir une Chambre :</label>
            <select name="chambre_ID" id="roomSelect" required>
                <option value="">Sélectionnez un hôtel d'abord</option>
            </select>

            <label for="date_debut">Date de Début :</label>
            <input type="date" name="date_debut" required>

            <label for="date_fin">Date de Fin :</label>
            <input type="date" name="date_fin" required>

            <button type="submit">Réserver</button>
        </form>

        <a href="client_dashboard.php">Retour au tableau de bord</a>
    </div>
</body>
</html>
