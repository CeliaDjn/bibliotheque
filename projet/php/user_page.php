<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'connexion_bdd.php';

if (!isset($_SESSION['utilisateur_id'])) {
    echo "Erreur : Utilisateur non connecté.";
    exit();
}

$utilisateur_id = $_SESSION['utilisateur_id'];
$query = "
    SELECT emprunts.*, livres.chemin_image
    FROM emprunts
    INNER JOIN livres ON emprunts.titre = livres.titre
    WHERE emprunts.nom_util = '$utilisateur_id'
";
$result = mysqli_query($db, $query);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon profil</title>
    <link rel="stylesheet" href="../css/biblio.css">
</head>
<body>
    <header>
        <h1>Profil de <?php echo htmlspecialchars($utilisateur_id); ?></h1>
        <div class="topnav">
            <form action="recherche_livre.php" method="post" style="display:inline;">
                <input type="hidden" name="search" value="">
                <button type="submit" class="catalogue-button">Voir Catalogue</button>
            </form>
            <a href="../acceuil.php">Se déconnecter</a>
        </div>
        <form id="searchForm" action="recherche_livre.php" method="post">
            <label for="search"></label>
            <input type="search" id="search" name="search" placeholder="Rechercher un livre">
        </form>
    </header>
    <h1>Mes livres empruntés</h1>

    <main>

        <section>
            <div class="container">
            <?php
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<div class='livre'>";
                echo "<h4>" . htmlspecialchars($row['titre']) . "</h4>";
                echo "<p>Date d'emprunt : " . htmlspecialchars($row['date_emprunt']) . "</p>";
                echo "<p>Date de rendu : " . htmlspecialchars($row['date_rendu']) . "</p>";
                $titre_livre_escaped = urlencode($row['titre']);
                $cheminImage = !empty($row['chemin_image']) ? '../' . htmlspecialchars($row['chemin_image']) : '../default_image_path.jpg';
                echo '<img src="' . $cheminImage . '" alt="Image du livre">';
                echo "<div class='buttons-container'>";
                echo "<button><a href='rendre_livre.php?titre=" . $titre_livre_escaped . "'>Rendre</a></button>";
                echo "<button><a href='renouveler_livre.php?titre=" . $titre_livre_escaped . "'>Renouveler</a></button>";
                echo "</div>";
                echo "</div>";
            }
            ?>
            </div>
        </section>
    </main>

    
</body>
</html>
