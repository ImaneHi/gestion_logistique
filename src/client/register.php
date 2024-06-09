<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>S'inscrire</title>
  <link rel="stylesheet" href="../../assets/css/client_css/register.css">
  <!-- UIkit CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/uikit@3.21.5/dist/css/uikit.min.css" />

</head>
<body>

<?php
// Démarrer la session
session_start();

// Inclure le fichier de configuration pour se connecter à la base de données
include 'config.php';

// Initialisation des variables d'erreur et de succès
$error = '';
$success = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer et sécuriser les données du formulaire
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $email = $_POST['email'];
    $contact = $_POST['contact'];
    $role = $_POST['role'];
    $pswd = $_POST['password'];
    $psw_repeat = $_POST['psw-repeat'];

    // Vérifier si les mots de passe correspondent
    if ($pswd !== $psw_repeat) {
        $error = "Les mots de passe ne correspondent pas.";
    } else {
        // Hash du mot de passe pour la sécurité
        $password_hashed = password_hash($pswd, PASSWORD_DEFAULT);

        // Connexion à la base de données
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Vérifier la connexion
        if ($conn->connect_error) {
            die("La connexion a échoué : " . $conn->connect_error);
        }

        // Préparer et exécuter la requête d'insertion
        $stmt = $conn->prepare("INSERT INTO clients (nom, prenom, email, contact,role, password) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $nom, $prenom, $email, $contact,$role, $password_hashed);

        if ($stmt->execute()) {
            // Enregistrement réussi, démarrer une session pour l'utilisateur
            $_SESSION['username'] = $nom;
            $success = "Inscription réussie !";
            // Redirection vers une page de bienvenue ou tableau de bord
            header("Location: login.php");
            exit();
        } else {
            $error = "Erreur : " . $stmt->error;
        }

        // Fermer la connexion
        $stmt->close();
        $conn->close();
    }
}
?>

<form class="uk-form-horizontal uk-margin-large" action="" method="POST">

  <div class="container">
    <h1>Inscription</h1>
    <p>Veuillez remplir ce formulaire pour créer un compte.</p>
    <hr>

    <?php if ($error): ?>
      <div class="error"><?php echo $error; ?></div>
    <?php endif; ?>

    <?php if ($success): ?>
      <div class="success"><?php echo $success; ?></div>
    <?php endif; ?>

    


    <div class="uk-margin">
        <label class="uk-form-label" for="form-horizontal-text">Nom :</label>
        <div class="uk-form-controls">
            <input class="uk-input" type="text" placeholder="Enter Nom" name="nom" id="nom" required>
        </div>
    </div>

    <div class="uk-margin">
        <label class="uk-form-label" for="form-horizontal-text">Prenom :</label>
        <div class="uk-form-controls">
            <input class="uk-input" type="text" placeholder="Enter Prenom" name="prenom" id="prenom" required>
        </div>
    </div>

    <div class="uk-margin">
        <label class="uk-form-label" for="form-horizontal-text">Email :</label>
        <div class="uk-form-controls">
            <input class="uk-input" type="text" placeholder="Enter Email" name="email" id="email" required>
        </div>
    </div>

    <div class="uk-margin">
        <label class="uk-form-label" for="form-horizontal-text">Contact :</label>
        <div class="uk-form-controls">
            <input class="uk-input" type="text" placeholder="Enter telephone" name="contact" id="contact" required>
        </div>
    </div>

    
    <div class="uk-margin">
        <div class="uk-form-label">Vous êtes ?</div>
        <div class="uk-margin uk-grid-small uk-child-width-auto uk-grid">
            <label><input class="uk-radio" type="radio" name="role" value="client"> Client</label><br>
            <label><input class="uk-radio" type="radio" name="role" value="fournisseur"> Fournisseur</label>
        </div>
    </div>

    
    <div class="uk-margin">
        <label class="uk-form-label" for="form-horizontal-text">Mot de Passe :</label>
        <div class="uk-form-controls">
            <input class="uk-input" type="password" placeholder="Enter Password" name="password" id="password" required>
        </div>
    </div>
    
    <div class="uk-margin">
        <label class="uk-form-label" for="form-horizontal-text">Confirmer Mot de Passe :</label>
        <div class="uk-form-controls">
            <input class="uk-input" type="password" placeholder="Repeat Password" name="psw-repeat" id="psw-repeat" required>
        </div>
    </div>
    


    <b>By creating an account you agree to our <a href="#">Terms & Privacy</a>.</b><br><br>
    <button type="submit" class="uk-button uk-button-primary uk-width-1-1 uk-margin-small-bottom">Valider</button>
    <div class="">
    <b>Vous avez déjà un compte? <a href="login.php">Se connecter</a>.</b>
  </div>
  </div>

  
</form>

</body>

<!-- UIkit JS -->
<script src="https://cdn.jsdelivr.net/npm/uikit@3.21.5/dist/js/uikit.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/uikit@3.21.5/dist/js/uikit-icons.min.js"></script>
</html>
