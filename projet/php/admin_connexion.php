<?php
session_start();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion Admin</title>
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
    </style>
</head>
<body>
    <header>
        <h1>Connexion Administrateur</h1>
    </header>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $username = $_POST['username'];
        $password = $_POST['password'];

       require_once 'connexion_bdd.php';

        $sql = "SELECT id_admin FROM admin WHERE nom_admin = ? AND mot_de_passe_admin = ?";
        $stmt = mysqli_prepare($db, $sql);
        mysqli_stmt_bind_param($stmt, "ss", $username, $password);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);

        if (mysqli_stmt_num_rows($stmt) == 1) {
            $_SESSION['admin'] = $username;
            header("Location: admin_page.php");
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
            <h2>Connexion</h2>
            <div id="u">
                <label for="username"></label><br>
                <input type="text" id="username" name="username" placeholder="Nom d'admin" required><br>
            </div>
            <div id="pass">
                <label for="password"></label><br>
                <input type="password" id="password" name="password" placeholder="Mot de passe" required><br><br>
            </div>
            <input type="submit" id="submit" value="Se connecter">
             <?php if (!empty($errorMessage)): ?>
             <p class="error-message"><?php echo $errorMessage; ?></p>
            <?php endif; ?>

        </form>
    </div>

</body>
</html>
