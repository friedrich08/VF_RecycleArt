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

// Vérifier si un produit est en cours de modification
$edit_mode = false;
$product_id = null;
$product = null;

// Récupérer tous les produits pour la liste déroulante
$sql = "SELECT id_produit, nom_produit FROM produit";
$result = $conn->query($sql);
$products = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
}

// Si un produit est sélectionné pour modification
if (isset($_GET['edit'])) {
    $edit_mode = true;
    $product_id = intval($_GET['edit']);

    // Récupérer les informations du produit à modifier
    $sql = "SELECT * FROM produit WHERE id_produit = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();
    } else {
        $message = "<div class='alert alert-danger'>Produit non trouvé.</div>";
        $edit_mode = false;
    }
    $stmt->close();
}

// Traitement du formulaire lorsqu'il est soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer les données du formulaire
    $nom = htmlspecialchars(trim($_POST['nom']));
    $prix = floatval(trim($_POST['prix']));
    $stock = intval(trim($_POST['stock']));
    $categorie = intval(trim($_POST['categorie']));
    $product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : null;

    // Gestion de l'upload de l'image
    $target_dir = "uploads/"; // Dossier où les images seront stockées
    $image_uploaded = !empty($_FILES["image"]["name"]);

    if ($image_uploaded) {
        $imageFileType = strtolower(pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION));
        $target_file = $target_dir . uniqid() . "." . $imageFileType; // Nom de fichier unique
    }

    // Valider les données
    if (empty($nom) || empty($prix) || empty($stock) || empty($categorie)) {
        $message = "<div class='alert alert-danger'>Tous les champs sont obligatoires.</div>";
    } else {
        if ($image_uploaded) {
            // Vérifier si le fichier est une image
            $check = getimagesize($_FILES["image"]["tmp_name"]);
            if ($check === false) {
                $message = "<div class='alert alert-danger'>Le fichier n'est pas une image.</div>";
            } else {
                // Vérifier la taille du fichier (5 Mo maximum)
                if ($_FILES["image"]["size"] > 5000000) {
                    $message = "<div class='alert alert-danger'>Désolé, votre fichier est trop volumineux.</div>";
                } else {
                    // Autoriser certains formats de fichier
                    if (!in_array($imageFileType, ["jpg", "jpeg", "png", "gif"])) {
                        $message = "<div class='alert alert-danger'>Désolé, seuls les fichiers JPG, JPEG, PNG et GIF sont autorisés.</div>";
                    }
                }
            }
        }

        if (empty($message)) {
            if ($edit_mode && $product_id) {
                // Mode édition : mettre à jour le produit
                if ($image_uploaded) {
                    // Déplacer le fichier uploadé vers le dossier cible
                    if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                        // Supprimer l'ancienne image si elle existe
                        if (!empty($product['image']) && file_exists($product['image'])) {
                            unlink($product['image']);
                        }
                        // Mettre à jour le produit avec la nouvelle image
                        $sql = "UPDATE produit SET nom_produit = ?, prix_produit = ?, stock = ?, id_categorie = ?, image = ? WHERE id_produit = ?";
                        $stmt = $conn->prepare($sql);
                        $stmt->bind_param("sdiisi", $nom, $prix, $stock, $categorie, $target_file, $product_id);
                    } else {
                        $message = "<div class='alert alert-danger'>Une erreur s'est produite lors du téléchargement de l'image.</div>";
                    }
                } else {
                    // Mettre à jour le produit sans changer l'image
                    $sql = "UPDATE produit SET nom_produit = ?, prix_produit = ?, stock = ?, id_categorie = ? WHERE id_produit = ?";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("sdiii", $nom, $prix, $stock, $categorie, $product_id);
                }

                if (isset($stmt) && $stmt->execute()) {
                    $message = "<div class='alert alert-success'>Produit mis à jour avec succès !</div>";
                } else {
                    $message = "<div class='alert alert-danger'>Erreur lors de la mise à jour du produit : " . $stmt->error . "</div>";
                }
                if (isset($stmt)) $stmt->close();
            } else {
                // Mode ajout : insérer un nouveau produit
                if ($image_uploaded) {
                    // Déplacer le fichier uploadé vers le dossier cible
                    if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                        // Insérer le nouveau produit
                        $sql = "INSERT INTO produit (nom_produit, prix_produit, stock, id_categorie, image) VALUES (?, ?, ?, ?, ?)";
                        $stmt = $conn->prepare($sql);
                        $stmt->bind_param("sdiis", $nom, $prix, $stock, $categorie, $target_file);
                    } else {
                        $message = "<div class='alert alert-danger'>Une erreur s'est produite lors du téléchargement de l'image.</div>";
                    }
                } else {
                    $message = "<div class='alert alert-danger'>Veuillez sélectionner une image.</div>";
                }

                if (isset($stmt) && $stmt->execute()) {
                    $message = "<div class='alert alert-success'>Produit ajouté avec succès !</div>";
                } else {
                    $message = "<div class='alert alert-danger'>Erreur lors de l'ajout du produit : " . $stmt->error . "</div>";
                }
                if (isset($stmt)) $stmt->close();
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $edit_mode ? 'Modifier un article' : 'Ajouter un article'; ?></title>
    <style>
        /* Styles ici... */
        body {
            background-color: #f8f9fa;
        }
        .form-container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            color: #ff6f00;
            margin-bottom: 20px;
        }
        .form-label {
            font-weight: bold;
            color: #333;
        }
        .form-control {
            border-radius: 5px;
            border: 1px solid #ddd;
            padding: 10px;
        }
        .form-control:focus {
            border-color: #ff6f00;
            box-shadow: 0 0 5px rgba(255, 111, 0, 0.5);
        }
        .btn-primary {
            background-color: #ff6f00;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            border-radius: 5px;
            width: 100%;
        }
        .btn-primary:hover {
            background-color: #e65a00;
        }
        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #ff6f00;
            padding: 10px 20px;
        }
        .navbar a {
            color: white;
            text-decoration: none;
            margin: 0 10px;
            font-size: 16px;
            font-weight: bold;
        }
        .navbar .logo {
            font-size: 20px;
            font-weight: bold;
            color: white;
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
    <div class="form-container">
        <h1><?php echo $edit_mode ? 'Modifier un article' : 'Ajouter un article'; ?></h1>
        <!-- Afficher les messages de succès ou d'erreur -->
        <?php if (!empty($message)) : ?>
            <div class="mb-3">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <!-- Liste déroulante pour sélectionner un produit -->
        <form action="" method="GET" class="mb-4">
            <div class="mb-3">
                <label for="edit" class="form-label">Sélectionner un produit à modifier :</label>
                <select class="form-control" id="edit" name="edit" onchange="this.form.submit()">
                    <option value="">-- Sélectionnez un produit --</option>
                    <?php foreach ($products as $prod) : ?>
                        <option value="<?php echo $prod['id_produit']; ?>" <?php echo ($edit_mode && $product_id == $prod['id_produit']) ? 'selected' : ''; ?>>
                            <?php echo $prod['nom_produit']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </form>

        <!-- Formulaire de modification/ajout -->
        <form action="" method="POST" enctype="multipart/form-data">
            <?php if ($edit_mode) : ?>
                <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
            <?php endif; ?>
            <div class="mb-3">
                <label for="nom" class="form-label">Nom de l'article :</label>
                <input type="text" class="form-control" id="nom" name="nom" value="<?php echo $edit_mode ? $product['nom_produit'] : ''; ?>" required>
            </div>
            <div class="mb-3">
                <label for="prix" class="form-label">Prix :</label>
                <input type="number" class="form-control" id="prix" name="prix" step="0.01" value="<?php echo $edit_mode ? $product['prix_produit'] : ''; ?>" required>
            </div>
            <div class="mb-3">
                <label for="stock" class="form-label">Stock :</label>
                <input type="number" class="form-control" id="stock" name="stock" value="<?php echo $edit_mode ? $product['stock'] : ''; ?>" required>
            </div>
            <div class="mb-3">
                <label for="categorie" class="form-label">Catégorie :</label>
                <select class="form-control" id="categorie" name="categorie" required>
                    <?php
                    // Récupérer les catégories depuis la base de données
                    $sql = "SELECT id_categorie, nom FROM categorie";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            $selected = ($edit_mode && $row['id_categorie'] == $product['id_categorie']) ? 'selected' : '';
                            echo "<option value='" . $row['id_categorie'] . "' $selected>" . $row['nom'] . "</option>";
                        }
                    } else {
                        echo "<option value=''>Aucune catégorie disponible</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="image" class="form-label">Image de l'article :</label>
                <input type="file" class="form-control" id="image" name="image" accept="image/*" <?php echo $edit_mode ? '' : 'required'; ?>>
                <?php if ($edit_mode && !empty($product['image'])) : ?>
                    <small>Image actuelle : <a href="<?php echo $product['image']; ?>" target="_blank">Voir l'image</a></small>
                <?php endif; ?>
                <button type="submit" class="btn btn-primary"><?php echo $edit_mode ? 'Mettre à jour' : 'Ajouter l\'article'; ?></button>
            </div>
           
        </form>
    </div>
</body>
</html>
<?php
// Fermer la connexion à la base de données
$conn->close();
?>