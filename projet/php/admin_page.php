<?php
session_start();
require_once 'connexion_bdd.php';

// Récupérer les notifications
$query = "SELECT * FROM notifications ORDER BY created_at DESC";
$result = mysqli_query($db, $query);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Page</title>
    <link rel="stylesheet" href="../css/admin.css">
</head>
<body>
    <header>
        <h1>Page d'administration</h1>
        <div class="topnav">
                <a href="ajouter_livre.php">Ajouter un livre</a>
                <a href="supprimer_livre.php">Supprimer un livre</a>
                <a href="ajouter_user.php">Ajouter un utilisateur</a>
                <a href="supprimer_user.php">Supprimer un utilisateur</a>
                <a href="../acceuil.php">Se déconnecter</a>

        </div>
    </header>

    <main>
        <section>
            <h2>Notifications</h2>
            <div class="container">
            <?php
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<div class='notification " . $row['status'] . "'>";
                echo "<p>" . $row['message'] . "</p>";
                echo "<p class='date'>" . $row['created_at'] . "</p>";
                echo "<button><a href='supprime_notif.php?id=" . $row['id'] . "'>Supprimer</a></button>";
                echo "</div>";
            }
            ?>
            </div>
        </section>
    </main>


</body>
</html>
