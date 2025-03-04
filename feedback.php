<?php

$host = "localhost";
$dbname = "vitrine";
$username = "felt0007"; 
$password = "RdQKcM9qGT";

var_dump($_SERVER["REQUEST_METHOD"]);


$maxFileSize = 2 * 1024 * 1024;

// Vérifier si le formulaire est soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupération et sécurisation des données du formulaire
    $name = htmlspecialchars($_POST['name']);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $rating = intval($_POST['rating']);
    $feedback = htmlspecialchars($_POST['feedback']);

    // Vérifier si l'email est valide
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Email invalide.");
    }

    // Gestion de l'upload de l'image (si fichier fourni)
    $screenshot_path = NULL;
    if (!empty($_FILES['screenshot']['name'])) {

        if ($_FILES['screenshot']['size'] > $maxFileSize) {
            die("Le fichier est trop volumineux. La taille maximale autorisée est de 2 Mo.");
        }

        $upload_dir = "uploads/";

        // Vérifier si le dossier existe, sinon le créer
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        // Renommer le fichier avec un identifiant unique
        $file_extension = pathinfo($_FILES["screenshot"]["name"], PATHINFO_EXTENSION);
        $filename = uniqid("screenshot_", true) . "." . $file_extension;
        $screenshot_path = $upload_dir . $filename;

        // Vérifier et déplacer le fichier uploadé
        if (!move_uploaded_file($_FILES["screenshot"]["tmp_name"], $screenshot_path)) {
            die("Erreur lors de l'upload du fichier.");
        }
    }

    try {
        // Connexion à la base de données
        $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Insérer les données dans la base
        $stmt = $pdo->prepare("INSERT INTO feedbacks (name, email, rating, feedback, screenshot_path) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$name, $email, $rating, $feedback, $screenshot_path]);

        echo "Merci pour votre retour !";
    } catch (PDOException $e) {
        echo "Erreur : " . $e->getMessage();
    }
}
?>
