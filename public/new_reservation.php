<?php
session_start();
include '../config/config.php';

// Vérification de la session
if (!isset($_SESSION['email']) || $_SESSION['type'] !== 'client') {
    header("Location: login.php");
    exit();
}

// Récupérer le nas du client
$sql_client = "SELECT nas FROM client WHERE email = $1";
$result_client = pg_query_params($conn, $sql_client, array($_SESSION['email']));

if ($result_client && pg_num_rows($result_client) > 0) {
    $client = pg_fetch_assoc($result_client);
    $client_nas = $client['nas'];
} else {
    die("❌ Erreur: Client non trouvé.");
}

// Gestion du formulaire de réservation
$error = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $chambre_ID = $_POST['chambre_ID'];
    $date_debut = $_POST['date_debut'];
    $date_fin = $_POST['date_fin'];

    if ($date_debut >= $date_fin) {
        $error = "❌ La date de fin doit être après la date de début.";
    } else {
        $sql_insert = "INSERT INTO reservation (date_debut, date_fin, etat, paiement, client_nas) 
                       VALUES ($1, $2, 'En attente', 0, $3) RETURNING reservation_ID";
        $params = array($date_debut, $date_fin, $client_nas);
        $result_insert = pg_query_params($conn, $sql_insert, $params);

        if ($row = pg_fetch_assoc($result_insert)) {
            $reservation_ID = $row['reservation_ID'];

            // Associer la chambre
            $sql_associe = "INSERT INTO associe (chambre_ID, reservation_ID) VALUES ($1, $2)";
            pg_query_params($conn, $sql_associe, array($chambre_ID, $reservation_ID));

            header("Location: view_reservations.php");
            exit();
        } else {
            $error = "❌ Erreur lors de la création de la réservation.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Nouvelle Réservation - eHôtels</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .selected-room {
            background-color: #ffd379;
            color: black;
            font-weight: bold;
        }
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
        function applyFilter() {
            const date_debut = document.getElementById('date_debut').value;
            const date_fin = document.getElementById('date_fin').value;
            const capacite = document.getElementById('capacite').value;
            const chaine = document.getElementById('chaine').value;
            const categorie = document.getElementById('categorie').value;
            const prix = document.getElementById('prix').value;
            const vue = document.getElementById('vue').value;
            const extensible = document.getElementById('extensible').value;

            if (!date_debut || !date_fin || date_debut >= date_fin) {
                alert("❌ Veuillez entrer une plage de dates valide.");
                return;
            }

            const url = `get_rooms.php?date_debut=${date_debut}&date_fin=${date_fin}&capacite=${capacite}&chaine=${chaine}&categorie=${categorie}&prix=${prix}&vue=${vue}&extensible=${extensible}`;

            fetch(url)
                .then(res => res.json())
                .then(data => {
                    const tbody = document.getElementById('chambre-body');
                    tbody.innerHTML = "";

                    if (!Array.isArray(data) || data.length === 0) {
                        tbody.innerHTML = "<tr><td colspan='5'>Aucune chambre trouvée</td></tr>";
                        return;
                    }

                    data.forEach(chambre => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td class="wrap-text">${chambre.nom_hotel}</td>
                            <td class="star-column">${chambre.categorie_hotel} ★</td>
                            <td>${chambre.commodites}</td>
                            <td>${chambre.prix} $</td>
                            <td>${getCapaciteLabel(chambre.capacite)}</td>
                            <td>${chambre.vue}</td>
                            <td>${chambre.extensible === 't' ? 'Oui' : 'Non'}</td>
                            <td>
                                <button type="button" onclick="selectRoom(${chambre.chambre_id}, this)">Réserver</button>
                                <button type="button" onclick="showInfo(${chambre.chambre_id})">Info</button>
                            </td>
                        `;
                        tbody.appendChild(row);
                    });
                })
                .catch(error => console.error("❌ Erreur fetch:", error));
        }


        function selectRoom(chambre_ID, btn) {
            const date_debut = document.getElementById('date_debut').value;
            const date_fin = document.getElementById('date_fin').value;

            if (!date_debut || !date_fin || date_debut >= date_fin) {
                alert("❌ Veuillez entrer une plage de dates valide.");
                return;
            }

            document.getElementById('selectedRoom').value = chambre_ID;
            document.getElementById('selectedDateDebut').value = date_debut;
            document.getElementById('selectedDateFin').value = date_fin;

            document.getElementById('reservationForm').submit();
        }


        function showInfo(chambre_id) {
            fetch(`get_room_details.php?chambre_id=${chambre_id}`)
                .then(res => res.json())
                .then(data => {
                    if (data.error) {
                        alert("❌ " + data.error);
                        return;
                    }

                    const modalContent = `
                        <h3>🛏️ Chambre ${chambre_id}</h3>
                        <p><strong>Commodités :</strong> ${data.commodites}</p>
                        <p><strong>Prix :</strong> ${data.prix} $</p>
                        <p><strong>Capacité :</strong> ${getCapaciteLabel(data.capacite)}</p>
                        <p><strong>Vue :</strong> ${data.vue}</p>
                        <p><strong>Extensible :</strong> ${data.extensible == 't' ? 'Oui' : 'Non'}</p>
                        <p><strong>Dommages :</strong> ${data.problemes ? data.problemes : 'Aucun'}</p>
                        <hr>
                        <h4>🏨 <Hôtel</h4>
                        <p><strong>Nom :</strong> ${data.hotel_nom}</p>
                        <p><strong>Adresse :</strong> ${data.hotel_adresse}</p>
                        <p><strong>Catégorie :</strong> ${data.hotel_categorie} ★</p>
                    `;

                    document.getElementById("modal-body").innerHTML = modalContent;
                    document.getElementById("infoModal").style.display = "block";
                })
                .catch(error => console.error("❌ Erreur lors de la récupération des infos:", error));
        }

        function closeModal() {
            document.getElementById("infoModal").style.display = "none";
        }
    </script>
</head>
<body>
    <div class="container-register">
        <h3>Faire une Nouvelle Réservation</h3>

        <?php if ($error): ?>
            <p style="color: red;"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>

        <!-- Filtres -->
        <form onsubmit="applyFilter(); return false;">
            <div class="filter-container">
                <!-- Ligne 1 -->
                <div class="filter-row">
                <div class="filter-group">
                    <label for="date_debut">Date début :</label>
                    <input type="date" id="date_debut">
                </div>
                <div class="filter-group">
                    <label for="date_fin">Date fin :</label>
                    <input type="date" id="date_fin">
                </div>
                <div class="filter-group">
                    <label for="chaine">Chaîne :</label>
                    <select id="chaine">
                    <option value="">Toutes</option>
                    <?php
                    $result = pg_query($conn, "SELECT chaine_id, nom FROM chaine");
                    while ($row = pg_fetch_assoc($result)) {
                        echo "<option value='{$row['chaine_id']}'>" . htmlspecialchars($row['nom']) . "</option>";
                    }
                    ?>
                    </select>
                </div>
                <div class="filter-group">
                    <label for="categorie">Catégorie :</label>
                    <select id="categorie">
                        <option value="">Toutes</option>
                        <option value="1">1 ★</option>
                        <option value="2">2 ★★</option>
                        <option value="3">3 ★★★</option>
                        <option value="4">4 ★★★★</option>
                        <option value="5">5 ★★★★★</option>
                    </select>
                </div>
                </div>

                <!-- Ligne 2 -->
                <div class="filter-row">
                <div class="filter-group">
                    <label for="prix">Prix Max :</label>
                    <input type="number" id="prix" style="width: 100px;">
                </div>
                <div class="filter-group">
                    <label for="capacite">Capacité :</label>
                    <select id="capacite">
                    <option value="">Tous</option>
                    <option value="1">Simple</option>
                    <option value="2">Double</option>
                    <option value="3">Triple</option>
                    <option value="4">Studio</option>
                    <option value="5">Suite</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label for="vue">Vue :</label>
                    <select id="vue">
                    <option value="">Toutes</option>
                    <option value="Vue sur mer">Mer</option>
                    <option value="Vue sur lac">Lac</option>
                    <option value="Vue sur jardin">Jardin</option>
                    <option value="Vue sur ville">Ville</option>
                    <option value="Vue sur montagne">Montagne</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label for="extensible">Extensible :</label>
                    <select id="extensible">
                        <option value="">--</option>
                        <option value="1">Oui</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label>&nbsp;</label>
                    <button type="submit">Appliquer</button>
                </div>
                </div>
            </div>
        </form>

        <!-- Tableau -->
        <div class="scroll-table">
            <table>
                <thead>
                    <tr>
                        <th>Hôtel</th>
                        <th class="star-column">Catégorie</th>
                        <th>Commodités</th>
                        <th>Prix</th>
                        <th>Capacité</th>
                        <th>Vue</th>
                        <th>Extensible</th>
                        <th>Réserver</th>
                    </tr>
                </thead>
                <tbody id="chambre-body">
                    <tr><td colspan="8">Veuillez appliquer un filtre pour afficher les chambres disponibles.</td></tr>
                </tbody>
            </table>
        </div>


        <a href="client_dashboard.php">← Retour au tableau de bord</a>
    </div>

    <form id="reservationForm" method="POST" action="create_reservation.php">
        <input type="hidden" name="chambre_ID" id="selectedRoom">
        <input type="hidden" name="date_debut" id="selectedDateDebut">
        <input type="hidden" name="date_fin" id="selectedDateFin">
    </form>


    <div id="infoModal" style="display:none; position:fixed; top:10%; left:10%; width:80%; background:white; border:1px solid #ccc; padding:20px; box-shadow:0 0 15px rgba(0,0,0,0.5); z-index:1000;">
        <button style="float:right;" onclick="closeModal()">✖</button>
        <div id="modal-body"></div>
    </div>

</body>
</html>
