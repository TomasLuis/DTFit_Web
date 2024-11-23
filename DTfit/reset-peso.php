<?php
// Initialize the session
session_start();

// Check if the user is logged in, otherwise redirect to login page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: index.index");
    exit;
}

// Include config file
require_once "config.php";

$new_peso = $confirm_peso = "";
$new_peso_err = $confirm_peso_err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Validate new weight
    if (empty(trim($_POST["new_peso"]))) {
        $new_peso_err = "Por favor coloque o seu peso atual.";
    } else {
        $new_peso = trim($_POST["new_peso"]);
    }

    // Validate confirm weight
    if (empty(trim($_POST["confirm_peso"]))) {
        $confirm_peso_err = "Por favor confirme o seu peso atual";
    } else {
        $confirm_peso = trim($_POST["confirm_peso"]);
        if (empty($new_peso_err) && ($new_peso != $confirm_peso)) {
            $confirm_peso_err = "O peso nao corresponde";
        }
    }

    // If no errors, update the weight in the database
    if (empty($new_peso_err) && empty($confirm_peso_err)) {
        $sql = "UPDATE users SET peso = ? WHERE id = ?";

        if ($stmt = mysqli_prepare($link, $sql)) {
            mysqli_stmt_bind_param($stmt, "si", $param_peso, $param_id);

            // Set parameters
            $param_peso = ($new_peso);
            $param_id = $_SESSION["id"];

            if (mysqli_stmt_execute($stmt)) {
                header("location: welcome.php");
                exit();
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
    <title>Peso</title>
    <link rel="stylesheet" href="CSS/cssPeso.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <style type="text/css">
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
    <br>
    <div class="box">
        <h3 class="h3">Peso</h3>
        <br>

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
            <br>
            <br>

            <div class="form-group">
                <input type="submit" href="welcome.php" value="Enviar">
                <a class="btn btn-link" id="cancelar" href="welcome.php">Cancelar</a>
            </div>
        </form>
    </div>
</body>
</html>
