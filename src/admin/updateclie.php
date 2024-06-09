<?php
session_start();
include '../client/config.php';

// Vérification de la session utilisateur
// Assurez-vous que l'utilisateur est connecté avant de permettre l'accès à cette page

// Récupération de l'ID du client depuis l'URL
if (isset($_GET['ID_client'])) {
    $ID_client = $_GET['ID_client'];
    
    // Requête pour récupérer les détails du client spécifique
    $sql = "SELECT * FROM clients WHERE id = ?";
    
    // Préparation de la requête
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $ID_client);
    
    // Exécution de la requête
    $stmt->execute();
    
    // Récupération des résultats
    $result = $stmt->get_result();
    
    // Vérification s'il y a des résultats
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $nom = $row['nom'];
        $prenom = $row['prenom'];
        $email = $row['email'];
        $contact = $row['contact'];
    } else {
        // Si aucun client avec cet ID n'est trouvé, rediriger vers la page des clients
        header("location: clients.php");
        exit();
    }
} else {
    // Si l'ID du client n'est pas spécifié dans l'URL, rediriger vers la page des clients
    header("location: clients.php");
    exit();
}

// Gestion de la modification du client
if (isset($_POST['modifier'])) {
    // Validation et récupération des données du formulaire
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $email = $_POST['email'];
    $contact = $_POST['contact'];

    // Requête SQL pour mettre à jour les détails du client
    $sqlUpdate = "UPDATE clients SET nom=?, prenom=?, email=?, contact=? WHERE id=?";
    $stmtUpdate = $conn->prepare($sqlUpdate);
    $stmtUpdate->bind_param("ssssi", $nom, $prenom, $email, $contact, $ID_client);
    $stmtUpdate->execute();

    // Vérification si la mise à jour a réussi
    if ($stmtUpdate->affected_rows > 0) {
        echo "<script>alert('La modification a été effectuée avec succès.')</script>";
        header("location: clients.php"); // Redirection vers la liste des clients après la modification
        exit();
    } else {
        echo "<script>alert('La modification a échoué.')</script>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Modifier un Client</title>
    <link rel="stylesheet" href="bootstrap11.css">
    <link rel="icon" href="" type="image/x-icon">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js"></script>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>

    <link
      href="https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css"
      rel="stylesheet"
    />
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Raleway:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta2/css/all.min.css" integrity="sha512-0zSxftwnNTfMwYc0b+hGrDT4xmQq3sFOlQKcJQu9l+Ogo99z1My+b/aYClCpO7JGtH+pw/pMIv1CKg5fBg7X/Q==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <link
      href="https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css"
      rel="stylesheet"
    />
    <script src="https://kit.fontawesome.com/7e5a5bb997.js" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Raleway:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta2/css/all.min.css" integrity="sha512-0zSxftwnNTfMwYc0b+hGrDT4xmQq3sFOlQKcJQu9l+Ogo99z1My+b/aYClCpO7JGtH+pw/pMIv1CKg5fBg7X/Q==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="assets/lib/perfect-scrollbar/css/perfect-scrollbar.min.css"/>
    <link rel="stylesheet" type="text/css" href="assets/lib/material-design-icons/css/material-design-iconic-font.min.css"/>
    
<style>
#add_button{
    pointer-events:<?php echo $pointer_eventsAdd; ?>;
    text-decoration:<?php echo $text_decorationAdd; ?>;
}
</style>
</head>
<body>

<div class="container" style="margin-top:30px">
    <div class="card">
        <div class="card-header">
            <h3>Modifier le Client</h3>
        </div>
        <div class="card-body">
            <form method="post" action="<?php echo $_SERVER['PHP_SELF'] . "?ID_client=$ID_client"; ?>">
                <div class="mb-3">
                    <label for="nom" class="form-label">Nom</label>
                    <input type="text" class="form-control" id="nom" name="nom" value="<?php echo $nom; ?>" required>
                </div>
                <div class="mb-3">
                    <label for="prenom" class="form-label">Prenom</label>
                    <input type="text" class="form-control" id="prenom" name="prenom" value="<?php echo $prenom; ?>" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" value="<?php echo $email; ?>" required>
                </div>
                <div class="mb-3">
                    <label for="contact" class="form-label">Contact</label>
                    <input type="number" class="form-control" id="contact" name="contact" value="<?php echo $contact; ?>" required>
                </div>
                <button type="submit" class="btn btn-primary" name="modifier">Modifier</button>
                <a href="clients.php" class="btn btn-secondary">Annuler</a>
            </form>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.
