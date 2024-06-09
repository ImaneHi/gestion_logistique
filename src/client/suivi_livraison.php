
<?php
include_once 'config.php';
session_start();

// Vérifier si l'utilisateur est connecté, sinon le rediriger vers la page de login
if (!isset($_SESSION['id']) || !isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Récupérer les informations de l'utilisateur depuis la base de données
$client_id = $_SESSION['id'];
$query = "SELECT id, nom, prenom, email, contact FROM clients WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $client_id);
$stmt->execute();
$result = $stmt->get_result();

// Vérifier s'il y a des résultats
if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
   
} else {
    echo "Erreur : Utilisateur non trouvé.";
    exit();
}
$prenom = $user["prenom"];
$nom = $user["nom"];



$sql = "SELECT * FROM Expeditions WHERE commande_id IN (SELECT id FROM Commandes WHERE client_id = '$client_id')";
$result = $conn->query($sql);
// Fermer la connexion
$stmt->close();
$conn->close();

?>


        
<!DOCTYPE html>
<html dir="ltr" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="keywords"
        content="wrappixel, admin dashboard, html css dashboard, web dashboard, bootstrap 5 admin, bootstrap 5, css3 dashboard, bootstrap 5 dashboard, Monsterlite admin bootstrap 5 dashboard, frontend, responsive bootstrap 5 admin template, Monster admin lite design, Monster admin lite dashboard bootstrap 5 dashboard template">
    <meta name="description"
        content="Monster Lite is powerful and clean admin dashboard template, inpired from Bootstrap Framework">
    <meta name="robots" content="noindex,nofollow">
    <title>Expedition</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/uikit@3.21.0/dist/css/uikit.min.css" />
    <link rel="icon" type="image/png" sizes="16x16" href="../../assets/img/favicon.png">
    <!-- Custom CSS -->
    <link href="../../assets/css/client_css/style.min.css" rel="stylesheet">
   <style>
    .collapse.first-level {
    display: none; /* Initially hidden */
    list-style: none;
    padding-left: 20px; /* Indent the submenu */
}

.sidebar-item:hover > .collapse.first-level {
    display: block; /* Show the submenu on hover */
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
    <!-- ============================================================== -->
    <!-- Main wrapper - style you can find in pages.scss -->
    <!-- ============================================================== -->
    <div id="main-wrapper" data-layout="vertical" data-navbarbg="skin5" data-sidebartype="full"
        data-sidebar-position="absolute" data-header-position="absolute" data-boxed-layout="full">
        <!-- ============================================================== -->
        <!-- Topbar header - style you can find in pages.scss -->
        <!-- ============================================================== -->
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
        <!-- ============================================================== -->
        <!-- End Topbar header -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Left Sidebar - style you can find in sidebar.scss  -->
        <!-- ============================================================== -->
        <aside class="left-sidebar" data-sidebarbg="skin6">
            <!-- Sidebar scroll-->
            <div class="scroll-sidebar">
                <!-- Sidebar navigation-->
                <nav class="sidebar-nav">
                    <ul id="sidebarnav">
                        <!-- User Profile-->
                        <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link"
                                href="acceuil.php" aria-expanded="false"><i class="me-3 far fa-clock fa-fw"
                                    aria-hidden="true"></i><span class="hide-menu">Dashboard</span></a></li>
                        <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link"
                                href="profile.php" aria-expanded="false">
                                <i class="me-3 fa fa-user" aria-hidden="true"></i><span
                                    class="hide-menu">Profile</span></a>
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
                        
                        <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link"
                                href="map-google.html" aria-expanded="false"><i class="me-3 fa fa-globe"
                                    aria-hidden="true"></i><span class="hide-menu">Google Map</span></a></li>
                        
                        
                    </ul>

                </nav>
                <!-- End Sidebar navigation -->
            </div>
            <!-- End Sidebar scroll-->
        </aside>
        <!-- ============================================================== -->
        <!-- End Left Sidebar - style you can find in sidebar.scss  -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Page wrapper  -->
        <!-- ============================================================== -->
        <div class="page-wrapper">
            <!-- ============================================================== -->
            <!-- Bread crumb and right sidebar toggle -->
            <!-- ============================================================== -->
            <div class="page-breadcrumb">
                <div class="row align-items-center">
                    <div class="col-md-6 col-8 align-self-center">
                        <h3 class="page-title mb-0 p-0">Table</h3>
                        <div class="d-flex align-items-center">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Table</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                    <div class="col-md-6 col-4 align-self-center">
                        <div class="text-end upgrade-btn">
                            <a href="ajout_expedition.php"
                                class="btn btn-warning d-none d-md-inline-block text-white">Ajouter une expedition</a>
                        </div>
                    </div>
                </div>
            </div>
        </aside>

    <div class="container-fluid">
                <!-- ============================================================== -->
                <!-- Start Page Content -->
                <!-- ========================Suivre une expedition====================================== -->
                



                <div class="row">
                    
                <form action="" method="post" class="no-margin">
        <div class="uk-width-expand uk-margin-small uk-flex uk-flex-middle" uk-grid>
            <div class="uk-width-large">
                <h1 class="uk-heading-bullet reduced-margin">Suivre une expédition :</h1>
            </div>
            <div class="uk-width-medium">
                <input type="text" name="numero_suivi" id="numero_suivi" class="uk-input uk-form-width-large reduced-margin">
            </div>
            <div class="uk-width-1-4@m">
                <input class="uk-button uk-button-primary reduced-margin" type="submit" value="Suivre">
            </div>
        </div>
    </form>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        include 'config.php';
        $numero_suivi = htmlspecialchars($_POST['numero_suivi']);
        if (!empty($numero_suivi)) {
            // Rechercher les informations d'expédition dans la base de données
            $query = "SELECT * FROM Expeditions WHERE numero_suivi = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("s", $numero_suivi);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows > 0) {
                $expedition = $result->fetch_assoc();
                echo '<div class="uk-alert-primary reduced-margin" uk-alert>';
                echo '<p>Résultats pour le numéro de suivi : <strong>' . $numero_suivi . '</strong></p>';
                echo '<ul>';
                echo '<li>Statut: ' . $expedition["statut_expedition"] . '</li>';
                echo '<li>Date d\'expédition: ' . $expedition["date_expedition"] . '</li>';
                echo '<li>Infos: ' . $expedition["info_suivi"] . '</li>';
                echo '</ul>';
                echo '</div>';
            } else {
                echo '<div class="uk-alert-danger reduced-margin" uk-alert>';
                echo '<p>Aucune expédition trouvée pour ce numéro de suivi.</p>';
                echo '</div>';
            }
            $stmt->close();
            $conn->close();
        } else {
            echo '<div class="uk-alert-danger reduced-margin" uk-alert>';
            echo '<p>Veuillez entrer un numéro de suivi valide.</p>';
            echo '</div>';
        }
    }
    ?>

                              
                </div>

                <footer class="footer text-center">
                © 2024 Fast Delivery by <a href="https://www.wrappixel.com/">fastdelivery.com</a>
            </footer>
                


                <script src="../../assets/plugins/jquery/dist/jquery.min.js"></script>
    <!-- Bootstrap tether Core JavaScript -->
    <script src="../../assets/plugins/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../../assets/js/app-style-switcher.js"></script>
    <!--Wave Effects -->
    <script src="../../assets/js/waves.js"></script>
    <!--Menu sidebar -->
    <script src="../../assets/js/sidebarmenu.js"></script>
    <!--Custom JavaScript -->
    <script src="../../assets/js/custom.js"></script>
    <!-- UIkit JS -->
<script src="https://cdn.jsdelivr.net/npm/uikit@3.21.0/dist/js/uikit.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/uikit@3.21.0/dist/js/uikit-icons.min.js"></script>


</body>

</html>














