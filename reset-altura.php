<?php

session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: index.php");
    exit;
}

require_once "config.php";

$new_altura = $confirm_altura = "";
$new_altura_err = $confirm_altura_err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (empty(trim($_POST["new_altura"]))) {
        $new_altura_err = "Por favor coloque a sua altura atual.";
    } else {
        $new_altura = trim($_POST["new_altura"]);
    }

    if (empty(trim($_POST["confirm_altura"]))) {
        $confirm_altura_err = "Por favor confirme a sua altura atual";
    } else {
        $confirm_altura = trim($_POST["confirm_altura"]);
        if (empty($new_altura_err) && ($new_altura != $confirm_altura)) {
            $confirm_altura_err = "A altura não corresponde";
        }
    }

    if (empty($new_altura_err) && empty($confirm_altura_err)) {
        $sql = "UPDATE users SET altura = ? WHERE id = ?";

        if ($stmt = mysqli_prepare($link, $sql)) {
            mysqli_stmt_bind_param($stmt, "si", $param_altura, $param_id);

            $param_altura = $new_altura;
            $param_id = $_SESSION["id"];

            if (mysqli_stmt_execute($stmt)) {
                header("location: welcome.php");
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
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <title>Alteração da altura</title>
    <link rel="stylesheet" href="css/cssPesoAlturaPass.css">
    <style>
        body {
            font: 14px sans-serif;
            text-align: center;
        }

        .wrapper {
            width: 350px;
            padding: 20px;
        }

        body {
            background-image: url("img/Altura.jpg");
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
        <p style="font-size: 25px; color:gray;">Altura</p>
        <div style="margin-bottom: 50px;"></div>
        </span>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group <?php echo (!empty($new_altura_err)) ? 'has-error' : ''; ?>">
                <div class="input-container">
                    <input type="float" name="new_altura" placeholder="Insira a sua altura" value="<?php echo $new_altura; ?>">
                    <span class="help-block"><?php echo $new_altura_err; ?></span>
                </div>
            </div>
            <div class="form-group <?php echo (!empty($confirm_altura_err)) ? 'has-error' : ''; ?>">
                <input type="float" name="confirm_altura" placeholder="Confirmação da sua altura">
                <span class="help-block"><?php echo $confirm_altura_err; ?></span>
            </div>
            <div style="margin-bottom: 60px;"></div>
            <div class="form-group">
                <input type="submit" href="welcome.php" value="Enviar">
                <a class="btn btn-link" id="cancelar" href="welcome.php">Cancelar</a>
            </div>
        </form>
    </div>
</body>

</html>
