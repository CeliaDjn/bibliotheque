<?php
session_start();
require_once 'connexion_bdd.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);
$message="";


if (isset($_GET['livre_id'])) {

    $livre_id = $_GET['livre_id'];
    $delete_query = $db->prepare("DELETE FROM livres WHERE titre = ?");
    $delete_query->bind_param('s', $livre_id);

    try {
        $result = $delete_query->execute();

        if ($result) {
            header("Location: supprimer_livre.php");
            exit();
        } else {
            echo "Erreur lors de la suppression du livre.";
        }
    } catch (mysqli_sql_exception $e) {
        if ($e->getCode() === 1451) {
            $message = "Le livre ne peut pas être supprimé car il est emprunté actuellement.";
        } else {
            $message = "Une erreur s'est produite : " . $e->getMessage();
        }
    }
}

$query = "SELECT * FROM livres";
$result = mysqli_query($db, $query);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supprimer Livre</title>
    <link rel="stylesheet" href="../css/admin.css">
</head>
<body>
    <header>
        <h1>Supprimer un Livre</h1>
        <div class="topnav">
                <a href="ajouter_livre.php">Ajouter un livre</a>
                <a href="ajouter_user.php">Ajouter un utilisateur</a>
                <a href="supprimer_user.php">Supprimer un utilisateur</a>
                <a href="admin_page.php">Retourner à l'accueil</a>
        </div>
    </header>

    <main>
        <section>
        <?php if ($message): ?>
                <p class="error-message"><?php echo htmlspecialchars($message); ?></p>
            <?php endif; ?>
            <h2>Liste des Livres</h2>
            <table>
                <thead>
                    <tr>
                        <th>Titre</th>
                        <th>Auteur</th>
                        <th>Année de Publication</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['titre']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['auteur']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['stock']) . "</td>";
                        echo "<td><a href='supprimer_livre.php?livre_id=" . urlencode($row['titre']) . "' onclick='return confirm(\"Êtes-vous sûr de vouloir supprimer ce livre ?\");'>Supprimer</a></td>";
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
        </section>
    </main>
</body>
</html>
