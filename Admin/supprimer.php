<?php
// Début du script PHP
session_start();

// Connexion à la base de données
$conn = new mysqli('127.0.0.1', 'root', '', 'myshop');

// Vérifier la connexion
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Variable pour stocker les messages de succès ou d'erreur
$message = "";

// Traitement de la suppression lorsqu'un produit est sélectionné
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_product'])) {
    $product_id = intval($_POST['product_id']);

    // Préparer la requête SQL pour supprimer l'article
    $sql = "DELETE FROM produit WHERE id_produit = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        $message = "<div class='alert alert-danger'>Erreur de préparation de la requête : " . $conn->error . "</div>";
    } else {
        // Lier le paramètre à la requête
        $stmt->bind_param("i", $product_id);

        // Exécuter la requête
        if ($stmt->execute()) {
            $message = "<div class='alert alert-success'>Article supprimé avec succès !</div>";

            // Supprimer l'image associée si elle existe
            $image_sql = "SELECT image FROM produit WHERE id_produit = ?";
            $image_stmt = $conn->prepare($image_sql);
            $image_stmt->bind_param("i", $product_id);
            $image_stmt->execute();
            $image_result = $image_stmt->get_result();

            if ($image_result->num_rows > 0) {
                $image_data = $image_result->fetch_assoc();
                $image_path = $image_data['image'];
                if (!empty($image_path) && file_exists($image_path)) {
                    unlink($image_path); // Supprimer le fichier image
                }
            }

            $image_stmt->close();
        } else {
            $message = "<div class='alert alert-danger'>Erreur lors de la suppression de l'article : " . $stmt->error . "</div>";
        }

        // Fermer la requête préparée
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supprimer un article</title>
    <style>
        /* Variables globales */
        :root {
            --primary-color: #ff6f00;
            --secondary-color: #ffffff;
            --background-color: #f5f5f5;
            --text-color: #333333;
            --border-radius: 8px;
            --box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        /* Réinitialisation de base */
        body, h1, p, a, input, select, button {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            background-color: var(--background-color);
            color: var(--text-color);
            line-height: 1.6;
        }

        /* Navbar */
        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: var(--primary-color);
            padding: 10px 20px;
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .navbar .logo {
            font-size: 24px;
            font-weight: bold;
            color: var(--secondary-color);
            text-transform: uppercase;
        }

        .navbar .links {
            display: flex;
            gap: 20px;
        }

        .navbar a {
            color: var(--secondary-color);
            text-decoration: none;
            font-size: 16px;
            font-weight: bold;
            transition: all 0.3s ease;
        }

        .navbar a:hover {
            transform: scale(1.1);
            text-decoration: underline;
        }

        /* Conteneur principal */
        .form-container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background-color: var(--secondary-color);
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
        }

        .form-container h1 {
            text-align: center;
            color: var(--primary-color);
            margin-bottom: 20px;
            font-size: 28px;
        }

        /* Messages d'alerte */
        .alert {
            padding: 10px;
            margin-bottom: 20px;
            border-radius: var(--border-radius);
            color: white;
            font-weight: bold;
        }

        .alert-success {
            background-color: #28a745;
        }

        .alert-danger {
            background-color: #dc3545;
        }

        /* Formulaire */
        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .form-label {
            font-weight: bold;
            margin-bottom: 5px;
        }

        .form-control {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: var(--border-radius);
            font-size: 16px;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 5px rgba(255, 111, 0, 0.5);
        }

        button[type="submit"] {
            background-color: #dc3545;
            color: var(--secondary-color);
            padding: 10px 20px;
            border: none;
            border-radius: var(--border-radius);
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        button[type="submit"]:hover {
            background-color: #c82333;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .navbar {
                flex-direction: column;
                align-items: center;
                gap: 10px;
            }

            .navbar .links {
                flex-direction: column;
                gap: 10px;
            }

            .form-container {
                padding: 15px;
            }
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <div class="navbar">
        <div class="logo">MyShop</div>
        <div class="links">
            <a href="ajout.php">Ajouter</a>
            <a href="modifier.php">Modifier</a>
            <a href="supprimer.php">Supprimer</a>
        </div>
    </div>

    <!-- Formulaire -->
    <div class="form-container">
        <h1>Supprimer un article</h1>

        <!-- Afficher les messages de succès ou d'erreur -->
        <?php if (!empty($message)) : ?>
            <div class="mb-3">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <form action="" method="POST">
            <!-- Sélectionner un produit à supprimer -->
            <div class="mb-3">
                <label for="product_id" class="form-label">Sélectionner l'article à supprimer :</label>
                <select class="form-control" id="product_id" name="product_id" required>
                    <!-- Les options de produit seront dynamiquement chargées depuis la base de données -->
                    <?php
                    // Récupérer les produits depuis la base de données
                    $sql = "SELECT id_produit, nom_produit FROM produit";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<option value='" . htmlspecialchars($row['id_produit']) . "'>" . htmlspecialchars($row['nom_produit']) . "</option>";
                        }
                    } else {
                        echo "<option value='' disabled>Aucun produit disponible</option>";
                    }
                    ?>
                </select>
            </div>

            <!-- Bouton de suppression -->
            <button type="submit" name="delete_product" class="btn btn-danger">Supprimer l'article</button>
        </form>
    </div>
</body>
</html>

<?php
// Fermer la connexion à la base de données
$conn->close();
?>