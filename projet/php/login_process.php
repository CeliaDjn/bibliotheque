<?php
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test de connexion à la base de données</title>
</head>
<body>
<?php
if (isset($_POST["username"], $_POST["prenom"], $_POST["nom"], $_POST["ddn"], $_POST["email"], $_POST["password"]) &&
    !empty($_POST["username"]) && !empty($_POST["prenom"]) && !empty($_POST["nom"]) &&
    !empty($_POST["ddn"]) && !empty($_POST["email"]) && !empty($_POST["password"])) {

    $username = $_POST["username"];
    $prenom = $_POST["prenom"];
    $nom = $_POST["nom"];
    $ddn = $_POST["ddn"];
    $email = $_POST["email"];
    $password = $_POST["password"];

    require_once 'connexion_bdd.php';

    $requete_notification = "INSERT INTO notifications (message) VALUES (?)";
    $stmt_notification = mysqli_prepare($db, $requete_notification);

    if ($stmt_notification) {
        $message = "Demande d'ajout de -nom: $username - prenom: $prenom - date de naissance: $ddn - email: $email - mot de passe: $password";

        mysqli_stmt_bind_param($stmt_notification, "s", $message);
        if (mysqli_stmt_execute($stmt_notification)) {
            $_SESSION['success_message'] = "Votre demande d'ajout a été envoyée à l'administrateur.";

            header("Location: user_connexion.php");
            exit();
        } else {
            echo "Erreur : L'ajout de la notification a échoué. Veuillez réessayer.";
        }
        mysqli_stmt_close($stmt_notification);
    } else {
        echo "Erreur : Requête préparée incorrecte.";
    }

    mysqli_close($db);
} else {
    echo "Erreur : Tous les champs du formulaire doivent être remplis.";
}
?>
</body>
</html>
