<?php
session_start();
require_once 'connexion_bdd.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

$message = '';

if (isset($_POST['ajouter_utilisateur'])) {
    $nom_util = $_POST['nom_util'];
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $ddn = $_POST['ddn'];
    $adresse_mail = $_POST['adresse_mail'];
    $mot_de_passe = password_hash($_POST['mot_de_passe'], PASSWORD_BCRYPT);

    $query = $db->prepare("SELECT COUNT(*) FROM utilisateur WHERE nom_util = ?");
    $query->bind_param('s', $nom_util);
    $query->execute();
    $query->bind_result($count);
    $query->fetch();
    $query->close();

    if ($count > 0) {
        $message = "Erreur : Un utilisateur avec ce nom d'utilisateur existe déjà.";
    } else {
        // Préparer la requête SQL pour insérer le nouvel utilisateur dans la base de données
        $insert_query = $db->prepare("INSERT INTO utilisateur (nom_util, nom, prenom, ddn, adresse_mail, mot_de_passe) VALUES (?, ?, ?, ?, ?, ?)");
        $insert_query->bind_param('ssssss', $nom_util, $nom, $prenom, $ddn, $adresse_mail, $mot_de_passe);

        if ($insert_query->execute()) {
            $message = "L'utilisateur a été ajouté avec succès.";
        } else {
            $message = "Erreur lors de l'ajout de l'utilisateur : " . $db->error;
        }
        $insert_query->close();
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
    <title>Ajouter Utilisateur</title>
    <link rel="stylesheet" href="../css/admin.css">
</head>
<body>
    <header>
        <h1>Ajouter un Utilisateur</h1>
        <div class="topnav">
            <a href="ajouter_livre.php">Ajouter un livre</a>
            <a href="supprimer_livre.php">Supprimer un livre</a>
            <a href="supprimer_user.php">Supprimer un utilisateur</a>
            <a href="admin_page.php">Retourner à l'accueil</a>
        </div>
    </header>

    <main>
        <section>
            <h2>Ajouter un Nouvel Utilisateur</h2>
            <?php if ($message): ?>
                <p class="error-message"><?php echo htmlspecialchars($message); ?></p>
            <?php endif; ?>
            <form action="ajouter_user.php" method="post">
                <label for="nom_util">Nom d'utilisateur :</label>
                <input type="text" id="nom_util" name="nom_util" required><br>

                <label for="nom">Nom :</label>
                <input type="text" id="nom" name="nom" required><br>

                <label for="prenom">Prénom :</label>
                <input type="text" id="prenom" name="prenom" required><br>

                <label for="ddn">Date de Naissance :</label>
                <input type="date" id="ddn" name="ddn" required><br>

                <label for="adresse_mail">Adresse Email :</label>
                <input type="email" id="adresse_mail" name="adresse_mail" required><br>

                <label for="mot_de_passe">Mot de Passe :</label>
                <input type="password" id="mot_de_passe" name="mot_de_passe" required><br>

                <input type="submit" name="ajouter_utilisateur" value="Ajouter l'Utilisateur">
            </form>
        </section>

        <section>
            <h2>Liste des Utilisateurs</h2>
            <table>
                <thead>
                    <tr>
                        <th>Nom d'utilisateur</th>
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th>Date de Naissance</th>
                        <th>Adresse Email</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['nom_util']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['nom']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['prenom']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['ddn']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['adresse_mail']) . "</td>";
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
        </section>
    </main>
</body>
</html>
