<?php
session_start();

if (isset($_GET['cart'])) {
    $cartData = json_decode($_GET['cart'], true);
    $_SESSION['cart'] = $cartData;
}

if (isset($_GET['remove'])) {
    $indexToRemove = $_GET['remove'];
    if (isset($_SESSION['cart'][$indexToRemove])) {
        unset($_SESSION['cart'][$indexToRemove]);
        // Réindexe le tableau pour éviter les trous dans les indices
        $_SESSION['cart'] = array_values($_SESSION['cart']);
    }
}

$utilisateur_id = $_SESSION['utilisateur_id'];
$error = isset($_GET['error']) ? $_GET['error'] : '';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panier</title>
    <link rel="stylesheet" href="../css/biblio.css">
</head>
<body>
<header>
    <h1>Panier de <?php echo htmlspecialchars($utilisateur_id); ?></h1>
    <div class="topnav">
        <a href="user_page.php" onclick="return confirmAbandon()">Retour au profil</a>
        <a href="recherche_livre.php">Retour à la recherche</a>
        <a href="../acceuil.php">Se déconnecter</a>
    </div>
</header>

<main>
      <?php if ($error): ?>
        <div class="error">
            <p><?php echo htmlspecialchars($error); ?></p>
        </div>
    <?php endif; ?>
    <div class="container">
        <?php
        if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
            foreach ($_SESSION['cart'] as $index => $item) {
                $cheminImage = !empty($item['image']) ? htmlspecialchars($item['image']) : '../default_image_path.jpg';
                ?>
                <div class="livre">
                    <h3><strong>Titre:</strong> <?php echo htmlspecialchars($item['titre']); ?></h3>
                    <img src="<?php echo $cheminImage; ?>" alt="Image de <?php echo htmlspecialchars($item['titre']); ?>">
                    <p><strong>Quantité:</strong> <?php echo htmlspecialchars($item['quantity']); ?></p>
                    <form action="panier.php" method="get">
                        <input type="hidden" name="remove" value="<?php echo $index; ?>">
                        <button type="submit">Supprimer</button>
                    </form>
                </div>
                <?php
            }
        } else {
            echo "<p>Le panier est vide</p>";
        }
        ?>
    </div>
    <div class="validpanier">
        <form action="traitement_panier.php" method="post">
            <button type="submit">Valider le panier</button>
        </form>
    </div>
</main>

<script>
    function confirmAbandon() {
        return confirm("Voulez-vous vraiment abandonner le panier et retourner à la page d'accueil ?");
    }
</script>

</body>
</html>
