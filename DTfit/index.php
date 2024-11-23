<?php
// Initialize the session
session_start();

// Check if the user is already logged in, if yes then redirect him to welcome page
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    header("location: welcome.php");
    exit;
}

// Include config file
require_once "config.php";

// Define variables and initialize with empty values
$username = $password = "";
$username_err = $password_err = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Check if username is empty
    if (empty(trim($_POST["username"]))) {
        $username_err = "Por favor introduza o seu nome.";
    } else {
        $username = trim($_POST["username"]);
    }

    // Check if password is empty
    if (empty(trim($_POST["password"]))) {
        $password_err = "Por favor introduza a password.";
    } else {
        $password = trim($_POST["password"]);
    }

    // Validate credentials
    if (empty($username_err) && empty($password_err)) {

        $sql = "SELECT id, username, password FROM users WHERE username = ?";

        if ($stmt = mysqli_prepare($link, $sql)) {

            mysqli_stmt_bind_param($stmt, "s", $param_username);

            $param_username = $username;

            if (mysqli_stmt_execute($stmt)) {
                // Store result
                mysqli_stmt_store_result($stmt);

                if (mysqli_stmt_num_rows($stmt) == 1) {
                    // Bind result variables
                    mysqli_stmt_bind_result($stmt, $id, $username, $hashed_password);
                    if (mysqli_stmt_fetch($stmt)) {
                        if (password_verify($password, $hashed_password)) {
                            session_start();
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["username"] = $username;

                            header("location: welcome.php");
                        } else {
                            // Mensagem de erro se a password nao for valida
                            $password_err = "A password que inseriu nao é valida";
                        }
                    }
                } else {
                    // Aparecer uma mensagem de erro se o nome nao existir
                    $username_err = "Nome nao foi encontrado";
                }
            } else {
                echo "Oops! Algo correu mal. Por favor tente mais tarde, obrigado.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }

    // Close connection
    mysqli_close($link);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="CSS/cssIndex.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <style type="text/css">
        body {
            font: 14px sans-serif;
            background-image: url("img/DT.jpg");
            background-position: center;
            background-origin: content-box;
            background-repeat: no-repeat;
            background-size: cover;
            min-height: 100vh;
            font-family: 'Noto Sans', sans-serif;
}
    </style>
</head>
<body>
    <div class="box">
        <h2>Iniciar Sessão</h2>
        <br>
        <br>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                <input type="text" name="username" placeholder="Nome">
                <span style="color: red;" class="help-block"><?php echo $username_err; ?></span>
            </div>    
            <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                <input type="password" name="password" placeholder="Password">
                <span style="color: red;" class="help-block"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group">
                <br>
                <input type="submit" value="Login">
            </div>
            <p style="color:aquamarine;">Não tem uma conta? <a href="register.php">Clique</a>.</p>
        </form>
    </div>   
</body>
</html>
