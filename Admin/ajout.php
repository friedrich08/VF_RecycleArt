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

// Récupérer les catégories depuis la base de données
function getCategories($conn) {
    $categories = [];
    $sql = "SELECT id_categorie, nom FROM categorie";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $categories[] = ["id" => $row['id_categorie'], "name" => $row['nom']];
        }
    }
    return $categories;
}

// Traitement du formulaire lorsqu'il est soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer les données du formulaire
    $nom = htmlspecialchars($_POST['nom']);
    $prix = floatval($_POST['prix']);
    $stock = intval($_POST['stock']);
    $categorie = isset($_POST['categorie']) ? intval($_POST['categorie']) : null;

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
    } elseif ($image_uploaded && !getimagesize($_FILES["image"]["tmp_name"])) {
        $message = "<div class='alert alert-danger'>Le fichier n'est pas une image valide.</div>";
    } elseif ($image_uploaded && $_FILES["image"]["size"] > 5000000) {
        $message = "<div class='alert alert-danger'>Désolé, votre fichier est trop volumineux (maximum 5 Mo).</div>";
    } elseif ($image_uploaded && !in_array($imageFileType, ["jpg", "jpeg", "png", "gif"])) {
        $message = "<div class='alert alert-danger'>Désolé, seuls les fichiers JPG, JPEG, PNG et GIF sont autorisés.</div>";
    } else {
        try {
            if ($image_uploaded) {
                // Déplacer le fichier uploadé vers le dossier cible
                if (!move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                    throw new Exception("Une erreur s'est produite lors du téléchargement de l'image.");
                }
            }

            // Insérer le produit dans la base de données
            $sql = "INSERT INTO produit (nom_produit, prix_produit, stock, id_categorie, image) VALUES (?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $imagePath = $image_uploaded ? $target_file : null;
            $stmt->bind_param("sdiis", $nom, $prix, $stock, $categorie, $imagePath);

            if ($stmt->execute()) {
                $message = "<div class='alert alert-success'>Article ajouté avec succès !</div>";
            } else {
                throw new Exception("Erreur lors de l'ajout de l'article : " . $stmt->error);
            }

            $stmt->close();
        } catch (Exception $e) {
            $message = "<div class='alert alert-danger'>" . htmlspecialchars($e->getMessage()) . "</div>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un article</title>
    <link rel="stylesheet" href="ajout.css">
</head>
<body>
    <!-- Navbar -->
    <div class="navbar">
        <div class="logo">MyShop</div>
        <div class="links">
            <a href="ajouter.php">Ajouter</a>
            <a href="modifier.php">Modifier</a>
            <a href="supprimer.php">Supprimer</a>
        </div>
    </div>

    <!-- Formulaire -->
    <div class="form-container">
        <h1>Ajouter un nouvel article</h1>

        <!-- Afficher les messages de succès ou d'erreur -->
        <?php if (!empty($message)) : ?>
            <div class="mb-3">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <form action="" method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="nom" class="form-label">Nom de l'article :</label>
                <input type="text" class="form-control" id="nom" name="nom" required>
            </div>
            <div class="mb-3">
                <label for="prix" class="form-label">Prix :</label>
                <input type="number" step="0.01" class="form-control" id="prix" name="prix" required>
            </div>
            <div class="mb-3">
                <label for="stock" class="form-label">Stock :</label>
                <input type="number" class="form-control" id="stock" name="stock" required>
            </div>
            <div class="mb-3">
                <label for="categorie" class="form-label">Catégorie :</label>
                <select class="form-control" id="categorie" name="categorie" required>
                    <?php foreach (getCategories($conn) as $cat) : ?>
                        <option value="<?php echo $cat['id']; ?>"><?php echo $cat['name']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="image" class="form-label">Image de l'article :</label>
                <input type="file" class="form-control" id="image" name="image" accept="image/*" required>
            </div>
            <button type="submit" class="btn btn-primary">Ajouter l'article</button>
        </form>
    </div>
</body>
</html>

<?php
// Fermer la connexion à la base de données
$conn->close();
?>