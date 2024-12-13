<?php
session_start();
require_once 'connexion_bdd.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

$message = '';

if (isset($_POST['ajouter_livre'])) {

    $titre = $_POST['titre'];
    $auteur = $_POST['auteur'];
    $editeur = $_POST['editeur'];
    $parution = $_POST['parution'];
    $categorie = $_POST['categorie'];
    $stock = $_POST['stock'];
    $chemin_image = $_POST['chemin_image'];

    $query = $db->prepare("SELECT COUNT(*) FROM livres WHERE titre = ?");
    $query->bind_param('s', $titre);
    $query->execute();
    $query->bind_result($count);
    $query->fetch();
    $query->close();

    if ($count > 0) {
        $message = "Erreur : Un livre avec ce titre existe déjà.";
    } else {
        $insert_query = $db->prepare("INSERT INTO livres (titre, auteur, editeur, parution, categorie, stock, chemin_image) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $insert_query->bind_param('sssssis', $titre, $auteur, $editeur, $parution, $categorie, $stock, $chemin_image);

        if ($insert_query->execute()) {
            $message = "Le livre a été ajouté avec succès.";
        } else {
            $message = "Erreur lors de l'ajout du livre : " . $db->error;
        }
        $insert_query->close();
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
    <title>Ajouter Livre</title>
    <link rel="stylesheet" href="../css/admin.css">
</head>
<body>
    <header>
        <h1>Ajouter un Livre</h1>
        <div class="topnav">
                <a href="supprimer_livre.php">supprimer un livre</a>
                <a href="ajouter_user.php">Ajouter un utilisateur</a>
                <a href="supprimer_user.php">Supprimer un utilisateur</a>
                <a href="admin_page.php">Retourner à l'accueil</a>
        </div>
    </header>

    <main>
        <section>
            <h2>Ajouter un Nouveau Livre</h2>
            <?php if ($message): ?>
                <p class="error-message"><?php echo htmlspecialchars($message); ?></p>
            <?php endif; ?>
            <form action="ajouter_livre.php" method="post">
                <label for="titre">Titre :</label>
                <input type="text" id="titre" name="titre" required><br>

                <label for="auteur">Auteur :</label>
                <input type="text" id="auteur" name="auteur" required><br>

                <label for="editeur">Éditeur :</label>
                <input type="text" id="editeur" name="editeur" required><br>

                <label for="parution">Date de Parution :</label>
                <input type="date" id="parution" name="parution" required><br>

                <label for="categorie">Catégorie :</label>
                <input type="text" id="categorie" name="categorie" required><br>

                <label for="stock">Stock :</label>
                <input type="number" id="stock" name="stock" required><br>

                <label for="chemin_image">Chemin de l'Image :</label>
                <input type="text" id="chemin_image" name="chemin_image"><br>

                <input type="submit" name="ajouter_livre" value="Ajouter le Livre">
            </form>
        </section>

        <section>
            <h2>Liste des Livres</h2>
            <table>
                <thead>
                    <tr>
                        <th>Titre</th>
                        <th>Auteur</th>
                        <th>Éditeur</th>
                        <th>Parution</th>
                        <th>Catégorie</th>
                        <th>Stock</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['titre']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['auteur']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['editeur']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['parution']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['categorie']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['stock']) . "</td>";
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
        </section>
    </main>
</body>
</html>
