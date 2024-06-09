<?php 
// Connexion à la base de données
session_start();
include '../client/config.php';
/************/
if(isset($_GET['code_fournisseur'])) 
{
   $CodeFournisseur =$_GET['code_fournisseur'];

    $sql = "SELECT * FROM fournisseurs WHERE code_fournisseur=$CodeFournisseur";
    $result = mysqli_query($conn, $sql);
    $row = $result->fetch_assoc();


}


// If the "modifier" button is pressed
if(isset($_REQUEST['modifier'])) {
// Validate user input
$CodeFournisseur = $_REQUEST['code_fournisseur']; // Use intval to convert to integer and prevent SQL injection
 
$Nom = $conn->real_escape_string($_REQUEST['Nom']);
$Adresse= $conn->real_escape_string($_REQUEST['Adresse']);
$Contact = $conn->real_escape_string($_REQUEST['Contact']);

// Update the record in the database using prepared statements
$stmt = $conn->prepare("UPDATE fournisseurs SET  Nom=?, Adresse=?, Contact=?,  WHERE code_fournisseur=?");
$stmt->bind_param("ssssi",  $Nom, $Adresse, $Contact);
$stmt->execute();

if($stmt->affected_rows > 0) {
  echo "<script>alert('La modification a été effectuée avec succès.')</script>" ;
  header("location: fournisseurs.php");
  } else {
  echo "<script>alert('La modification a échoué.')</script>" ;
  }
}

// Get all records from the database
$records = $conn->query('SELECT * FROM fournisseurs');

?>
<!DOCTYPE html>
<html lang="fr" dir="ltr">
  <head>
    <meta charset="UTF-8" />
    <title>Modifier un Fournisseur </title>
    <link rel="stylesheet" href="style11.css" />
    <link rel="icon" href="" type="image/x-icon">
    <link href="https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css" rel="stylesheet"/>
    <style>







/* General styling */
input,
textarea,
select {
  margin-bottom: 15px; /* Increased spacing for readability */
  box-sizing: border-box;
  width: 100%; /* Adjust width as needed */
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
  width: 120px; /* Adjusted width for better balance */
  height: 40px; /* Increased height for emphasis */
  border: none; /* Remove border for cleaner look */
  font-size: 16px; /* Match input font size */
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
  width: 70%; /* Adjusted width for better responsiveness */
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

/*
.box {
  width: 50%;
  position: relative;
}

.box::before {
  content: "";
  position: absolute;
  top: -4px;
  left: -4px;
  width: calc(100% + 8px);
  height: calc(100% + 8px);
  border: 2px solid transparent;
  border-image: conic-gradient(#00ccff, #ee00ff) 1;
  border-image-slice: 1;
  border-radius: 5px;
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
*/

/*
.box {
  width: 50%;
  position: relative;
  overflow: hidden;
}

.box::before {
  content: "";
  position: absolute;
  top: -100%;
  left: -100%;
  width: 300%;
  height: 300%;
  background: linear-gradient(120deg, #00ccff, #ee00ff);
  transform: rotate(-45deg);
  animation: rotate_border 6s linear infinite;
}

@keyframes rotate_border {
  0% {
    transform: rotate(-45deg);
  }
  100% {
    transform: rotate(315deg);
  }
}
*/







</style>
  </head>
  <body>
    <section class="home-section" id="form-etu"> 
        <div class="home-content">
            <div class="overview-boxes">
                <div class="box" style="width:50%; border:2px solid #00ccff;">
                
                    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">  
                    <label for="ID_client">Code Fournisseur</label>
                      <input type="text"  name="code_fournisseur" id="CodeFournisseur" value="<?php echo $row['code_fournisseur'];?>" autocomplete="off">
                      <label for="Nom">Nom</label>
                      <input type="text" name="nom" pattern="[A-Za-zàâçéèêëîïìôöùûüÿĀāĒēĪīŌōŪūǖŶŷ]+" id="Nom"value="<?php echo $row['Nom'];?>"autocomplete="off">
                      <label for="Adresse">Adresse</label>
                      <input type="text" name="Adresse" id="Adresse" value="<?php echo $row['Adresse'];?>" autocomplete="off">
                      <label for="Contact">Contact</label>
                      <input type="number" name="Contact" id="Contact" value="<?php echo $row['Contact'];?>" autocomplete="off">
                     <button type="submit" name="modifier"> Modifier</button>
                      <button type="reset" class="btn btn-secondary pull-right"> <a href="fournisseurs.php" style="text-decoration:none; color:white;">Annuler</a></button>


                    </form>
                    
                </div>
            </div>
        </div>
    </section>
</body>
</html>