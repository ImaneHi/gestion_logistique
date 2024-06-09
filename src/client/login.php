<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Login & signup form</title>
  <link rel="stylesheet" href="../../assets/css/client_css/login.css">
</head>
<body>
<br><br>
    <div class="cont">
        <div class="form sign-in">
            <h2>Welcome</h2>
            <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
                <label>
                    <span>Email</span>
                    <input type="email" name="email" required />
                </label>
                <label>
                    <span>Password</span>
                    <input type="password" name="password" required />
                </label>
                <p class="forgot-pass">Forgot password?</p>
                <button type="submit" class="submit" name="login">Sign In</button>
            </form>
            <?php if (isset($error)) { echo '<p style="color:red;">' . $error . '</p>'; } ?>
        </div>
        <div class="sub-cont">
            <div class="img">
                <div class="img__text m--up">
                    <h3>Vous n'avez pas de compte? Please Sign up!</h3>
                </div>
                <div class="img__text m--in">
                    <h3>Vous avez déjà un compte, veuillez se connecter.</h3>
                </div>
                <div class="img__btn">
                    <span class="m--up">Sign Up</span>
                    <span class="m--in">Sign In</span>
                </div>
            </div>
            <div class="form sign-up">
                <h2>Créer un compte</h2>
                <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
                    <label>
                        <span>Nom</span>
                        <input type="text" name="nom" required />
                    </label>
                    <label>
                        <span>Prenom</span>
                        <input type="text" name="prenom" required />
                    </label>
                    <label>
                        <span>Email</span>
                        <input type="email" name="email" required />
                    </label>
                    <label>
                        <span>Contact</span>
                        <input type="text" name="contact" required />
                    </label>
                    <label>
                        <span>Mot de Passe</span>
                        <input type="password" name="password" required />
                    </label>
                    <label>
                        <span>Confirmer Mot de Passe</span>
                        <input type="password" name="psw-repeat" required />
                    </label>
                    <button type="submit" class="submit" name="signup">Sign Up</button>
                </form>
                <?php if (isset($signup_error)) { echo '<p style="color:red;">' . $signup_error . '</p>'; } ?>
                <?php if (isset($signup_success)) { echo '<p style="color:green;">' . $signup_success . '</p>'; } ?>
            </div>
        </div>
    </div>

    <script>
        document.querySelector('.img__btn').addEventListener('click', function() {
            document.querySelector('.cont').classList.toggle('s--signup');
        });
    </script>
</body>
</html>

<?php
session_start();
include 'config.php';

$error = '';
$signup_error = '';
$signup_success = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['login'])) {
        // Login logic
        $email = htmlspecialchars($_POST['email']);
        $pswd = htmlspecialchars($_POST['password']);

        $stmt = $conn->prepare("SELECT id, nom, email, role, password FROM clients WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if ($result->num_rows == 1 && password_verify($pswd, $user['password'])) {
            $_SESSION['id'] = $user['id'];
            $_SESSION['username'] = $user['nom'];
            if ($user['role'] == 'client') {
                header("Location: acceuil.php");
            } else {
                header("Location: ../admin/clients.php");
            }
            exit();
        } else {
            $error = "Email ou mot de passe incorrect.";
        }

        $stmt->close();
    } elseif (isset($_POST['signup'])) {
        // Signup logic
        $nom = htmlspecialchars($_POST['nom']);
        $prenom = htmlspecialchars($_POST['prenom']);
        $email = htmlspecialchars($_POST['email']);
        $contact = htmlspecialchars($_POST['contact']);
        $pswd = htmlspecialchars($_POST['password']);
        $psw_repeat = htmlspecialchars($_POST['psw-repeat']);

        if ($pswd !== $psw_repeat) {
            $signup_error = "Les mots de passe ne correspondent pas.";
        } else {
            $hashed_password = password_hash($pswd, PASSWORD_DEFAULT);

            $stmt = $conn->prepare("SELECT id FROM clients WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows == 0) {
                $stmt->close();
                $stmt = $conn->prepare("INSERT INTO clients (nom, prenom, email, contact, password) VALUES (?, ?, ?, ?, ?)");
                $stmt->bind_param("sssss", $nom, $prenom, $email, $contact, $hashed_password);
                if ($stmt->execute()) {
                    $signup_success = "Inscription réussie! Vous pouvez maintenant vous connecter.";
                } else {
                    $signup_error = "Erreur lors de l'inscription. Veuillez réessayer.";
                }
            } else {
                $signup_error = "Cet email est déjà utilisé.";
            }

            $stmt->close();
        }
    }

    $conn->close();
}
?>
