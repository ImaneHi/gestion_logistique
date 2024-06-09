<?php
session_start();

// Vérifier si l'utilisateur est connecté, sinon le rediriger vers la page de login
if (!isset($_SESSION['id']) || !isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Inclure le fichier de configuration pour la connexion à la base de données
include_once 'config.php';

// Récupérer les informations de l'utilisateur depuis la base de données
$user_id = $_SESSION['id'];
$query = "SELECT id, nom, prenom, email, contact FROM clients WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

// Vérifier s'il y a des résultats
if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    $prenom = $user["prenom"];
    $nom = $user["nom"];
    $email = $user["email"];
    $contact = $user["contact"];
} else {
    echo "Erreur : Utilisateur non trouvé.";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom = $_POST["nom"];
    $prenom = $_POST["prenom"];
    $email = $_POST["email"];
    $contact = $_POST["contact"];
    $client_id = $_SESSION["id"];

    // Vérifier si le champ "Mot de passe" est rempli
    if (!empty($_POST['password'])) {
        $password = htmlspecialchars($_POST['password']);
        // Hasher le mot de passe
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        // Mettre à jour le mot de passe dans la base de données
        $stmt = $conn->prepare("UPDATE clients SET nom = ?, prenom = ?, email = ?, contact = ?, password = ? WHERE id = ?");
        $stmt->bind_param("sssssi", $nom, $prenom, $email, $contact, $hashed_password, $client_id);
    } else {
        // Ne pas mettre à jour le mot de passe dans la base de données
        $stmt = $conn->prepare("UPDATE clients SET nom = ?, prenom = ?, email = ?, contact = ? WHERE id = ?");
        $stmt->bind_param("ssssi", $nom, $prenom, $email, $contact, $client_id);
    }

    if ($stmt->execute()) {
        // Mettre à jour les valeurs de session
        $_SESSION['nom'] = $nom;
        $_SESSION['prenom'] = $prenom;
        $_SESSION['email'] = $email;
        $_SESSION['contact'] = $contact;

        $message = "Profil mis à jour avec succès";
    } else {
        $message = "Erreur lors de la mise à jour du profil : " . $stmt->error;
    }

    $stmt->close();
    $conn->close();

    // Rediriger vers la page de profil avec le message
    header("Location: profile.php?message=" . urlencode($message));
    exit();
}
?>








<!DOCTYPE html>
<html dir="ltr" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Fast Delivery</title>
    <link rel="icon" type="image/png" sizes="16x16" href="../../assets/img/favicon.png">
    <link href="../../assets/plugins/chartist/dist/chartist.min.css" rel="stylesheet">
    <link href="../../assets/css/client_css/style.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/uikit@3.21.0/dist/css/uikit.min.css" />

    <style>
    .collapse.first-level {
        display: none;
        list-style: none;
        padding-left: 20px;
    }

    .sidebar-item:hover > .collapse.first-level {
        display: block;
    }
    </style>
</head>

<body>
    <div class="preloader">
        <div class="lds-ripple">
            <div class="lds-pos"></div>
            <div class="lds-pos"></div>
        </div>
    </div>
    <div id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
        data-sidebar-position="absolute" data-header-position="absolute" data-boxed-layout="full">
        <header class="topbar" data-navbarbg="skin6" style="background-image: linear-gradient(to right, #FF4800 , #FF4810);">
            <nav class="navbar top-navbar navbar-expand-md navbar-dark">
                <div class="navbar-header" data-logobg="skin6">
                    <!-- ============================================================== -->
                    <!-- Logo -->
                    <!-- ============================================================== -->
                    <a class="navbar-brand" href="acceuil.php">
                        <!-- Logo icon -->
                        <b class="logo-icon">
                            

                        </b>
                        <!--End Logo icon -->
                        <!-- Logo text -->
                        <span class="logo-text">
                            <!-- dark Logo text -->
                            <img src="../../assets/img/logo-text.png" alt="homepage" class="dark-logo" />

                        </span>
                    </a>
                    <!-- ============================================================== -->
                    <!-- End Logo -->
                    <!-- ============================================================== -->
                    <!-- ============================================================== -->
                    <!-- toggle and nav items -->
                    <!-- ============================================================== -->
                    <a class="nav-toggler waves-effect waves-light text-dark d-block d-md-none"
                        href="javascript:void(0)"><i class="ti-menu ti-close"></i></a>
                </div>
                <!-- ============================================================== -->
                <!-- End Logo -->
                <!-- ============================================================== -->
                <div class="navbar-collapse collapse" id="navbarSupportedContent" data-navbarbg="skin5">
                    
                    <!-- ============================================================== -->
                    <!-- toggle and nav items -->
                    <!-- ============================================================== -->
                    <ul class="navbar-nav me-auto mt-md-0 ">
                        <!-- ============================================================== -->
                        <!-- Search -->
                        <!-- ============================================================== -->

                        <li class="nav-item hidden-sm-down">
                            <form class="app-search ps-3">
                                <input type="text" class="form-control" placeholder="Search for..."> <a
                                    class="srh-btn"><i class="ti-search"></i></a>
                            </form>
                        </li>
                    </ul>

                    <!-- ============================================================== -->
                    <!-- Right side toggle and nav items -->
                    <!-- ============================================================== -->
                    <ul class="navbar-nav">
                        <!-- ============================================================== -->
                        <!-- User profile and search -->
                        <!-- ============================================================== -->
                        <li class="nav-item dropdown">

                        <div class="uk-inline">
                        
    <button class="uk-button uk-button-default" type="button"><?php echo $prenom.' '.$nom ?></button>
    <div uk-dropdown>
        <ul class="uk-nav uk-dropdown-nav">
            <li class="uk-active"><a href="profile.php">Profile</a></li>
            
            <li class="uk-nav-divider"></li>
            <li>
                <form action="logout.php" method="post">
                   <a> <button type="submit" class="border-0 btn btn-outline-secondary" >Se Déconnecter</button></a>
                </form></li>
        </ul>
    </div>
</div>
                            
                        </li>
                    </ul>
                </div>
            </nav>
        </header>
        <aside class="left-sidebar" data-sidebarbg="skin6">
            <div class="scroll-sidebar">
                <nav class="sidebar-nav">
                    <ul id="sidebarnav">
                        <li class="sidebar-item">
                            <a class="sidebar-link waves-effect waves-dark sidebar-link" href="acceuil.php" aria-expanded="false">
                                <i class="me-3 far fa-clock fa-fw" aria-hidden="true"></i>
                                <span class="hide-menu">Acceuil</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a class="sidebar-link waves-effect waves-dark sidebar-link" href="profile.php" aria-expanded="false">
                                <i class="me-3 fa fa-user" aria-hidden="true"></i>
                                <span class="hide-menu">Profile</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
            <a class="sidebar-link waves-effect waves-dark sidebar-link" href="expedition.php" aria-expanded="false">
                <i class="me-3 fa fa-table" aria-hidden="true"></i>
                <span class="hide-menu">Expedition</span>
            </a>
            <ul class="collapse first-level" aria-expanded="false">
                
                <li class="sidebar-item">
                    <a href="suivi_livraison.php" class="sidebar-link">
                        <i class="me-3 fa fa-search" aria-hidden="true"></i>
                        <span class="hide-menu">Rechercher</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="ajout_expedition.php" class="sidebar-link">
                        <i class="me-3 fa fa-plus" aria-hidden="true"></i>
                        <span class="hide-menu">Ajouter</span>
                    </a>
                </li>
            </ul>
        </li>
                        
                        <li class="sidebar-item">
                            <a class="sidebar-link waves-effect waves-dark sidebar-link" href="map-google.html" aria-expanded="false">
                                <i class="me-3 fa fa-globe" aria-hidden="true"></i>
                                <span class="hide-menu">Google Map</span>
                            </a>
                        </li>
                        
                    </ul>
                </nav>
            </div>
        </aside>
        <div class="page-wrapper">
            <div class="page-breadcrumb">
                <div class="row align-items-center">
                    <div class="col-md-6 col-8 align-self-center">
                        <h3 class="page-title mb-0 p-0">Acceuil</h3>
                        <div class="d-flex align-items-center">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Acceuil</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                    <div class="col-md-6 col-4 align-self-center"></div>
                </div>
            </div>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-4 col-xlg-3 col-md-5">
                        <div class="card">
                            <div class="card-body profile-card">
                                <center class="mt-4"> 
                                    <h4 class="card-title mt-2"><?php  echo $_SESSION['username'] ;?></h4>
                                    <h6 class="card-subtitle">Accounts Manager Amix corp</h6>
                                    <div class="row justify-content-center">
                                        <div class="col-4">
                                            <a href="javascript:void(0)" class="link">
                                                <i class="icon-people" aria-hidden="true"></i>
                                                <span class="font-normal">254</span>
                                            </a>
                                        </div>
                                        <div class="col-4">
                                            <a href="javascript:void(0)" class="link">
                                                <i class="icon-picture" aria-hidden="true"></i>
                                                <span class="font-normal">54</span>
                                            </a>
                                        </div>
                                    </div>
                                </center>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-8 col-xlg-9 col-md-7">
                        <div class="card">
                            <div class="card-body">
                            <?php
        // Vérifier si l'utilisateur est connecté
        if (!isset($_SESSION['id'])) {
            echo "<p>Vous devez être connecté pour accéder à cette page.</p>";
        } else {
            // Afficher un message de succès ou d'erreur après la mise à jour
            if (isset($message)) {
                echo "<div class='message'>" . $message . "</div>";
            }}
        ?>
                                <form class="form-horizontal form-material mx-2" method="POST" action="profile.php">
                                    <div class="form-group">
                                        <label class="col-md-12 mb-0">Nom</label>
                                        <div class="col-md-12">
                                            <input type="text" name="nom" value="<?php echo $nom; ?>" class="form-control ps-0 form-control-line">
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class="col-md-12 mb-0">Prenom</label>
                                        <div class="col-md-12">
                                            <input type="text" name="prenom" value="<?php echo $prenom; ?>" class="form-control ps-0 form-control-line">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="example-email" class="col-md-12">Email</label>
                                        <div class="col-md-12">
                                            <input type="email" name="email" value="<?php echo $email; ?>" class="form-control ps-0 form-control-line" id="example-email">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-12 mb-0">Nouveau Mot de Passe</label>
                                        <div class="col-md-12">
                                            <input type="password" name="password" class="form-control ps-0 form-control-line">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-12 mb-0">Phone No</label>
                                        <div class="col-md-12">
                                            <input type="text" name="contact" value="<?php echo $contact; ?>" class="form-control ps-0 form-control-line">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-12 d-flex">
                                            <button class="btn btn-success mx-auto mx-md-0 text-white">Enregistrer</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <footer class="footer text-center">
                © 2024 Fast Delivery by <a href="https://www.wrappixel.com/">fastdelivery.com</a>
            </footer>
        </div>
    </div>
    <script src="../../assets/plugins/jquery/dist/jquery.min.js"></script>
    <script src="../../assets/plugins/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../../assets/js/app-style-switcher.js"></script>
    <script src="../../assets/js/waves.js"></script>
    <script src="../../assets/js/sidebarmenu.js"></script>
    <script src="../../assets/js/custom.js"></script>
    <script src="../../assets/plugins/flot/jquery.flot.js"></script>
    <script src="../../assets/plugins/flot.tooltip/js/jquery.flot.tooltip.min.js"></script>
    <script src="../../assets/js/pages/dashboards/dashboard1.js"></script>
    <!-- UIkit JS -->
<script src="https://cdn.jsdelivr.net/npm/uikit@3.21.0/dist/js/uikit.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/uikit@3.21.0/dist/js/uikit-icons.min.js"></script>
</body>

</html>


