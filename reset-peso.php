<?php

session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: index.index");
    exit;
}

require_once "config.php";

$new_peso = $confirm_peso = "";
$new_peso_err = $confirm_peso_err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // validação da altura
    if (empty(trim($_POST["new_peso"]))) {
        $new_peso_err = "Por favor coloque o seu peso atual.";
    } else {
        $new_peso = trim($_POST["new_peso"]);
    }

    // validação do peso
    if (empty(trim($_POST["confirm_peso"]))) {
        $confirm_peso_err = "Por favor confirme o seu peso atual";
    } else {
        $confirm_peso = trim($_POST["confirm_peso"]);
        if (empty($new_peso_err) && ($new_peso != $confirm_peso)) {
            $confirm_peso_err = "O peso nao corresponde";
        }
    }

    // Se não tiver erros, atualiza na base de dados
    if (empty($new_peso_err) && empty($confirm_peso_err)) {
        $sql = "UPDATE users SET peso = ? WHERE id = ?";

        if ($stmt = mysqli_prepare($link, $sql)) {
            mysqli_stmt_bind_param($stmt, "si", $param_peso, $param_id);

            $param_peso = ($new_peso);
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
    <title>Alteração do Peso</title>
    <link rel="stylesheet" href="css/cssPesoAlturaPass.css">     
    <style>
        body {
            background-image: url("img/Peso.jpg");
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
    <div class="box">
        <p style="font-size: 25px; color:gray;">Peso</p>
        <div style="margin-bottom: 50px;"></div>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group <?php echo (!empty($new_peso_err)) ? 'has-error' : ''; ?>">
                <div class="input-container">
                    <input type="float" name="new_peso" placeholder="Insira o seu peso" value="<?php echo $new_peso; ?>">
                    <span class="help-block"><?php echo $new_peso_err; ?></span>
                </div>
            </div>
            <div class="form-group <?php echo (!empty($confirm_peso_err)) ? 'has-error' : ''; ?>">
                <input type="float" name="confirm_peso" placeholder="Confirmação do seu peso">
                <span class="help-block"><?php echo $confirm_peso_err; ?></span>
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
