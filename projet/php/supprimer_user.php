<?php
session_start();
require_once 'connexion_bdd.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);
$message="";

if (isset($_GET['user_id'])) {

    $user_id = $_GET['user_id'];

    $delete_query = $db->prepare("DELETE FROM utilisateur WHERE nom_util = ?");
    $delete_query->bind_param('s', $user_id);

    try {
        $result = $delete_query->execute();

        if ($result) {
            header("Location: supprimer_user.php");
            exit();
        } else {
            echo "Erreur lors de la suppression de l'utilisateur.";
        }
    } catch (mysqli_sql_exception $e) {
        if ($e->getCode() === 1451) {
            $message = "L'utilisateur ne peut pas être supprimé car il a des emprunts actifs.";
        } else {
            $message = "Une erreur s'est produite : " . $e->getMessage();
        }
    }
}

$query = "SELECT * FROM utilisateur";
$result = mysqli_query($db, $query);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supprimer Utilisateur</title>
    <link rel="stylesheet" href="../css/admin.css">
</head>
<body>
    <header>
        <h1>Supprimer un Utilisateur</h1>
        <div class="topnav">
                <a href="ajouter_livre.php">Ajouter un livre</a>
                <a href="ajouter_user.php">Ajouter un utilisateur</a>
                <a href="supprimer_livre.php">Supprimer un livre</a>
                <a href="admin_page.php">Retourner à l'accueil</a>
        </div>
    </header>

    <main>
        <section>
        <?php if ($message): ?>
                <p class="error-message"><?php echo htmlspecialchars($message); ?></p>
            <?php endif; ?>
            <h2>Liste des Utilisateurs</h2>
            <table>
                <thead>
                    <tr>
                        <th>Nom d'utilisateur</th>
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th>Email</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['nom_util']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['nom']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['prenom']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['adresse_mail']) . "</td>";
                        echo "<td><a href='supprimer_user.php?user_id=" . urlencode($row['nom_util']) . "' onclick='return confirm(\"Êtes-vous sûr de vouloir supprimer cet utilisateur ?\");'>Supprimer</a></td>";
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
        </section>
    </main>

</body>
</html>
