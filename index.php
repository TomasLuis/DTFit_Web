<?php
// Inicia a sessão
session_start();

// Verifica se o utilizador já está logado
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    header("location: welcome.php");
    exit;
}

require_once "config.php";

// Definir variaveis e iniciar as mesmas vazias
$username = $password = "";
$username_err = $password_err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // verifica se o nome ta vazio
    if (empty(trim($_POST["username"]))) {
        $username_err = "Por favor introduza o seu nome.";
    } else {
        $username = trim($_POST["username"]);
    }

    // verifica se a pass ta vazia
    if (empty(trim($_POST["password"]))) {
        $password_err = "Por favor introduza a password.";
    } else {
        $password = trim($_POST["password"]);
    }

    // Validação
    if (empty($username_err) && empty($password_err)) {

        $sql = "SELECT id, username, password FROM users WHERE username = ?";

        if ($stmt = mysqli_prepare($link, $sql)) {

            mysqli_stmt_bind_param($stmt, "s", $param_username);

            $param_username = $username;

            if (mysqli_stmt_execute($stmt)) {

                mysqli_stmt_store_result($stmt);

                if (mysqli_stmt_num_rows($stmt) == 1) {
                    
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

            mysqli_stmt_close($stmt);
        }
    }

    mysqli_close($link);
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="css/cssBootstrap/bootstrap.min.css">
    <link rel="stylesheet" href="css/cssIndex.css">
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
        <h2 style="padding: 10px;">Iniciar Sessão</h2>
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
