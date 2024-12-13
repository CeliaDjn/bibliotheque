<?php
session_start();

if (isset($_SESSION['success_message'])) {
    $successMessage = $_SESSION['success_message'];
    unset($_SESSION['success_message']);
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bibliothèque</title>
    <link rel="stylesheet" href="../css/acceuil.css">

    <style>
        .error-message {
            color: red;
            font-weight: bold;
            margin-top: 10px;
            background-color: white;
            padding:10px;
            border-radius:20px;

        }

        .success-message {
            background-color: white;
            color: green;
            padding:10px;
            border-radius:20px;
            font-weight: bold;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <header>
        <h1>Bienvenue à notre bibliothèque. Veuillez vous connecter ou vous inscrire pour accéder à notre catalogue de livres.</h1>
    </header>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $username = $_POST['username'];
        $password = $_POST['password'];

        require_once 'connexion_bdd.php';

        $sql = "SELECT nom_util, nom FROM utilisateur WHERE nom_util = ? AND mot_de_passe = ?";

        $stmt = mysqli_prepare($db, $sql);
        mysqli_stmt_bind_param($stmt, "ss", $username, $password);
        mysqli_stmt_execute($stmt);

        mysqli_stmt_store_result($stmt);
        if (mysqli_stmt_num_rows($stmt) == 1) {

            mysqli_stmt_bind_result($stmt, $id_utilisateur, $nom_utilisateur);
            mysqli_stmt_fetch($stmt);

            $_SESSION['utilisateur_id'] = $id_utilisateur;

            header("Location: user_page.php");
            exit();
        } else {
            $errorMessage = 'Nom d\'utilisateur ou mot de passe incorrect.';
        }

        mysqli_stmt_close($stmt);
        mysqli_close($db);
    }
    ?>

    <div id="form">
        <form id="loginForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">

        <h1>Connexion</h1>
            <div id="u">
                <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                    <path fill-rule="evenodd" d="M12 4a4 4 0 1 0 0 8 4 4 0 0 0 0-8Zm-2 9a4 4 0 0 0-4 4v1a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2v-1a4 4 0 0 0-4-4h-4Zm2-3a2 2 0 1 1 4 0v3h-4V7Zm2 6a1 1 0 0 1 1 1v3a1 1 0 1 1-2 0v-3a1 1 0 0 1 1-1Z" clip-rule="evenodd"/>
                </svg>

                <label for="username"></label><br>
                <input type="text" id="username" name="username" placeholder="Nom d'utilisateur" required ><br>
            </div>
            <div id="pass">
                <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                    <path fill-rule="evenodd" d="M8 10V7a4 4 0 1 1 8 0v3h1a2 2 0 0 1 2 2v7a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2v-7a2 2 0 0 1 2-2h1Zm2-3a2 2 0 1 1 4 0v3h-4V7Zm2 6a1 1 0 0 1 1 1v3a1 1 0 1 1-2 0v-3a1 1 0 0 1 1-1Z" clip-rule="evenodd"/>
                </svg>

                <label for="password"></label><br>
                <input type="password" id="password" name="password" placeholder="Mot de passe" required><br><br>
            </div>
            <input type="submit" id="submit" value="Se connecter">
            <?php if (!empty($errorMessage)): ?>
            <p class="error-message"><?php echo $errorMessage; ?></p>
           <?php endif; ?>

    <?php
    if (isset($successMessage)): ?>
        <p class="success-message"><?php echo $successMessage; ?></p>
    <?php endif; ?>
        </form>
        <p>Vous êtes nouveau ici ? <a href="../inscription.html" id="but_ins">S'inscrire</a></p>
        <p><a href="admin_connexion.php" id="but_ins">Se connecter en tant qu'admin</a></p>
    </div>


</body>
</html>
