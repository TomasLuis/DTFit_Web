<?php

session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: index.index");
    exit;
}


require_once "config.php";

$new_password = $confirm_password = "";
$new_password_err = $confirm_password_err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (empty(trim($_POST["new_password"]))) {
        $new_password_err = "Por favor coloque uma password.";
    } elseif (strlen(trim($_POST["new_password"])) < 6) {
        $new_password_err = "A password tem que ter no mínimo 6 caracteres.";
    } else {
        $new_password = trim($_POST["new_password"]);
    }

    if (empty(trim($_POST["confirm_password"]))) {
        $confirm_password_err = "Por favor confirme a password";
    } else {
        $confirm_password = trim($_POST["confirm_password"]);
        if (empty($new_password_err) && ($new_password != $confirm_password)) {
            $confirm_password_err = "A password não corresponde";
        }
    }

    if (empty($new_password_err) && empty($confirm_password_err)) {
        $sql = "UPDATE users SET password = ? WHERE id = ?";

        if ($stmt = mysqli_prepare($link, $sql)) {
            mysqli_stmt_bind_param($stmt, "si", $param_password, $param_id);


            $param_password = password_hash($new_password, PASSWORD_DEFAULT);
            $param_id = $_SESSION["id"];

            if (mysqli_stmt_execute($stmt)) {

                session_destroy();
                header("location: index.php");
                exit();
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
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Alteração da Pass</title>
    <link rel="stylesheet" href="css/cssPass.css">
    <link rel="stylesheet" href="css/jsBootstrap/bootstrap.bundle.min.js"> 
    <style type="text/css">
        body {
            background-image: url("img/dt.jpg");
            background-position: center;
            background-origin: content-box;
            background-repeat: no-repeat;
            background-size: cover;
            min-height: 100vh;
            font-family: 'Noto Sans';
            font: 14px sans-serif;
            text-align: center;
        }
    </style>
</head>
<body>
    <br>
    <div class="box">
        <h3 class="h3">Alteração da password</h3>
        <br>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group <?php echo (!empty($new_password_err)) ? 'has-error' : ''; ?>">
                <div class="input-container">
                    <input type="password" name="new_password" placeholder="Nova password" value="<?php echo $new_password; ?>">
                    <span class="help-block"><?php echo $new_password_err; ?></span>
                </div>
            </div>

            <div class="form-group <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
                <input type="password" name="confirm_password" placeholder="Confirmação da Password">
                <span class="help-block"><?php echo $confirm_password_err; ?></span>
            </div>
            <br>
            <br>

            <div class="form-group">
                <input type="submit" value="Enviar">
                <a class="btn btn-link" id="cancelar" href="welcome.php">Cancelar</a>
            </div>
        </form>
    </div>
</body>
</html>
