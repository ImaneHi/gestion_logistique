<?php
session_start();
include '../client/config.php';

// Suppression d'une ligne si l'ID est spécifié dans l'URL
if (isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];

    $sql = "DELETE FROM clients WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        echo "<script>alert('La ligne a été supprimée avec succès.')</script>";
    } else {
        echo "<script>alert('Erreur lors de la suppression de la ligne : " . $stmt->error . "')</script>";
    }
    $stmt->close();
}

// Ajout d'un client à la base de données
if (isset($_POST['valider'])) {
    $Nom = $_POST['nom'];
    $Prenom = $_POST['prenom'];
    $Email = $_POST['email'];
    $Contact = $_POST['contact'];

    $sql = "INSERT INTO clients (nom, prenom, email, contact) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $Nom, $Prenom, $Email, $Contact);
    if ($stmt->execute()) {
        echo "<script>alert('Client ajouté avec succès.')</script>";
        header("location: clients.php");
        exit();
    } else {
        echo "<script>alert('Erreur lors de l'ajout du client : " . $stmt->error . "')</script>";
    }
    $stmt->close();
}

// Récupération des données de la table clients
$sql = "SELECT * FROM clients";
$result = mysqli_query($conn, $sql);
if (!$result) {
    die(mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste des Clients</title>
    <link rel="stylesheet" href="bootstrap11.css">
    <link rel="icon" href="" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta2/css/all.min.css" integrity="sha512-0zSxftwnNTfMwYc0b+hGrDT4xmQq3sFOlQKcJQu9l+Ogo99z1My+b/aYClCpO7JGtH+pw/pMIv1CKg5fBg7X/Q==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/7e5a5bb997.js" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Raleway:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta2/css/all.min.css" integrity="sha512-0zSxftwnNTfMwYc0b+hGrDT4xmQq3sFOlQKcJQu9l+Ogo99z1My+b/aYClCpO7JGtH+pw/pMIv1CKg5fBg7X/Q==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="assets/lib/perfect-scrollbar/css/perfect-scrollbar.min.css"/>
    <link rel="stylesheet" type="text/css" href="assets/lib/material-design-icons/css/material-design-iconic-font.min.css"/>
    
    <style>
        #add_button {
            pointer-events: <?php echo $pointer_eventsAdd; ?>;
            text-decoration: <?php echo $text_decorationAdd; ?>;
        }
    </style>
</head>
<body>

<!-- Modal Ajouter un Client -->
<div class="modal" id="add_button" tabindex="-1" aria-labelledby="addbuttonLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form method="post" action="clients.php">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="addbuttonLabel">Ajouter un Client</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Nom</label>
                        <input type="text" pattern="[A-Za-zàâçéèêëîïìôöùûüÿĀāĒēĪīŌōŪūǖŶŷ  ]+" placeholder="Nom" id="Nom" name="nom" class="form-control input-xs parsley-error" required="required">
                    </div>
                    <div class="form-group">
                        <label>Prénom</label>
                        <input type="text" pattern="[A-Za-zàâçéèêëîïìôöùûüÿĀāĒēĪīŌōŪūǖŶŷ  ]+" placeholder="Prénom" id="Prenom" name="prenom" class="form-control input-xs parsley-error" required="required">
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" placeholder="Email" id="Email" name="email" class="form-control input-xs parsley-error" required="required">
                    </div>
                    <div class="form-group">
                        <label>Contact</label>
                        <input type="text" placeholder="Contact" id="Contact" name="contact" class="form-control input-xs parsley-error" required="required">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary" name="valider">Enregistrer</button>
                </div>
            </div>
        </form>
    </div>
</div>
<!-- Fin du Modal Ajouter un Client -->

<div class="container" style="margin-top:30px">
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-md-9">Liste des Clients</div>
                <div class="col-md-3" align="right">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add_button">Ajouter</button>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-bordered" id="client_table">
                    <thead>
                        <tr>
                            <th>ID Client</th>
                            <th>Nom</th>
                            <th>Prénom</th>
                            <th>Email</th>
                            <th>Contact</th>
                            <th>Modifier</th>
                            <th>Supprimer</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                            <tr>
                                <td><?php echo $row['id']; ?></td>
                                <td><?php echo $row['nom'];
['prenom']; ?></td>
<td><?php echo $row['prenom']; ?></td>
<td><?php echo $row['email']; ?></td>
<td><?php echo $row['contact']; ?></td>
<td align="center">
    <a href="updateclie.php?ID_client=<?php echo $row['id']; ?>" class="btn btn-primary">Modifier</a>
</td>
<td align="center">
    <a href="?delete_id=<?php echo $row['id']; ?>" class="btn btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce client ?')">Supprimer</a>
</td>
</tr>  
<?php } ?>
</tbody>
</table>
</div>
</div>
</div>
<button type="button" id="report_button" class="btn btn-danger btn-sm"><a href="dashboard.php" style="text-decoration:none; color:white; font-weight:bold;">Retour à l'accueil</a></button>
</div>

<!-- Include jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Include DataTables JS and CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/dataTables.bootstrap4.min.css">
<script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.3/js/dataTables.bootstrap4.min.js"></script>

<!-- DataTables script initialization -->
<script>
$(document).ready(function() {
$('#client_table').DataTable({
"language": {
"url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/French.json" // Adapté pour le français
}
});
});
</script>

</body>
</html>
