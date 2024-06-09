
<?php
// Database connection (assuming connection details are correct)
session_start();
include '../client/config.php';

// Validate and sanitize user input
$code_produit = isset($_GET['code']) ; // Check if code exists, then convert to integer


$sql = "SELECT * FROM produits WHERE code_produit = ?"; // Prepared statement

// Prepare and execute the statement
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $code_produit); // Bind the sanitized code_produit
$stmt->execute();
$result = $stmt->get_result(); // Get the result set
$user = $result->fetch_assoc();

// Handle case where no product is found
if ($result->num_rows === 0) {
  echo "Error: Product with code $code_produit not found.";
  echo "<br>";
  echo "<a href='produits.php'>Retourner à la liste des produits</a>"; // Offer a link back to the product list
}


// Code for retrieving list of suppliers (assuming a table named 'fournisseurs')
$sqlfourn = "SELECT * FROM fournisseurs";
$resultfourn = $conn->query($sqlfourn);
if (!$resultfourn) {
  die(mysqli_error($conn));
}

// If the "modifier" button is pressed
if (isset($_REQUEST['modifier'])) {
    // Sanitize user input (important for security)
    $code_produit = intval($_REQUEST['code_produit']); // Use intval to convert to integer and prevent SQL injection
    $Nom             = $conn->real_escape_string($_REQUEST['Nom']);
    $Description     = $conn->real_escape_string($_REQUEST['Description']);
    $Prix            = $conn->real_escape_string($_REQUEST['Prix']);
    $code_fournisseur = $conn->real_escape_string($_REQUEST['code_fournisseur']);

    // Update the record in the database using prepared statements
    $sqlUpdate = "UPDATE produits SET Nom=?, Description=?, Prix=?, code_fournisseur=? WHERE code_produit=?";
    $stmtUpdate = $conn->prepare($sqlUpdate);
    $stmtUpdate->bind_param("ssssi", $Nom, $Description, $Prix, $code_fournisseur, $code_produit);
    $stmtUpdate->execute();

    if ($stmtUpdate->affected_rows > 0) {
        echo "<script>alert('La modification a été effectuée avec succès.')</script>";
        header("location: produits.php"); // Redirect to product list page
    } else {
        echo "<script>alert('La modification a échoué.')</script>";
    }
} ?>

<!DOCTYPE html>
<html lang="fr" dir="ltr">
  <head>
    <meta charset="UTF-8" />
    <title>Modifier Produit </title>
    <link rel="icon" href="" type="image/x-icon">
    <link href="https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css" rel="stylesheet"/>
    <style>
 /* General styling */
input,
textarea
{
  margin-bottom: 15px; /* Increased spacing for readability */
  box-sizing: border-box;
  width: 100%; /* Adjust width as needed */
  padding: 10px 15px; /* Increased padding for comfort */
  border-radius: 5px;
  border: 1px solid #ddd; /* Lighter border for better contrast */
  font-size: 16px; /* Improved readability */
}
select {
  margin-bottom: 15px; /* Increased spacing for readability */
  box-sizing: border-box;
  width: 35%; /* Adjust width as needed */
  padding: 10px 15px; /* Increased padding for comfort */
  border-radius: 5px;
  border: 1px solid #ddd; /* Lighter border for better contrast */
  font-size: 16px; /* Improved readability */
}
.radio {
  margin-bottom: 15px;
  width: 20px;
}

button {
  background-color: rgb(6, 29, 233); /* Primary color */
  color: white;
  width: 110px; /* Adjusted width for better balance */
  height: 40px; /* Increased height for emphasis */
  border: none; /* Remove border for cleaner look */
  font-size: 16px; /* Match input font size */
  font-weight: bold;
  border-radius: 5px;
  cursor: pointer;
  margin: 0 10px; /* Increased margin for spacing */
  transition: background-color 0.2s ease; /* Smooth hover effect */
}

button:hover {
  background-color: rgb(0, 12, 186); /* Darken on hover */
}

label {
  display: block;
  margin-bottom: 5px; /* Consistent spacing */
  font-weight: bold; /* Emphasize labels */
}

/* Centering the form */
#form-etu {
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  display: flex;
  justify-content: center; /* Center buttons horizontally */
}

/* Enhanced box styling */
.box {
  width: 600px; /* Increased width for a larger box */
  border: 2px solid #00ccff; /* Primary color border */
  position: relative;
  background-color: #fff;
  padding: 20px; /* Increased padding for comfort */
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); /* Softer shadow */
  border-radius: 5px; /* Consistent rounded corners */
}


.box::before {
  content: "";
  position: absolute;
  top: -2px;
  left: -2px;
  width: calc(100% + 4px);
  height: calc(100% + 4px);
  background: linear-gradient(120deg, #00ccff, #ee00ff); /* Primary color gradient */
  z-index: -1;
  animation: rotate_border 6s linear infinite;
}

@keyframes rotate_border {
  0% {
    transform: rotate(0deg);
  }
  100% {
    transform: rotate(360deg);
  }
}


</style>
  </head>
  <body>
    <section class="home-section" id="form-etu"> 
        <div class="home-content">
            <div class="overview-boxes">
                <div class="box" style="width:50%">
                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">  
                
                    <label for="ID">Code Produit </label>
                      <input type="text" name="code_produit" id="ID" value="<?php echo $user['code_produit'];?>" autocomplete="off">
                      <label for="Nom">Nom</label>
                      <input type="text" name="Nom" id="Nom" value="<?php echo $user['Nom'];?>" autocomplete="off">
                      <label for="Description">Description</label>
                      <input type="text" name="Description" id="Description"value="<?php echo $user['Description'];?>"autocomplete="off">
                      <label for="Prix">Prix</label>
                      <input type="text" name="Prix" id="Prix" value="<?php echo $user['Prix'];?>" autocomplete="off">
                      <label>Code Fournisseur</label>
          <select name="code_fournisseur" id="code_fournisseur" style="width:460px; height:39px">
            <?php
            
            // Récupérer les données de la base de données
            if ($resultfourn->num_rows > 0) 
            {
                while ($rowfourn = $resultfourn->fetch_assoc()) {
                    echo '<option size=20 value="' . $rowfourn['code_fournisseur'] . '">' . $rowfourn['code_fournisseur'] . '</option>';
                }
            }
            ?>
            </select>
                      <button type="submit" name="modifier"> Modifier</button>
                      <button type="reset" class="btn btn-secondary pull-right"> <a href="produits.php" style="text-decoration:none; color:white;">Annuler</a></button>
                      
                    </form>
                    
                </div>
            </div>
        </div>
    </section>
</body>
</html>
