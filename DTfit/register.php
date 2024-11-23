<?php
// Include config file
require_once "config.php";

// Define variables and initialize with empty values
$username = $password = $confirm_password = $telemovel = $data_de_nascimento = $peso = $altura = "";
$username_err = $password_err = $confirm_password_err = $telemovel_err = $data_de_nascimento_err = $peso_err = $altura_err = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Validate username
    if (empty(trim($_POST["username"]))) {
        $username_err = "Por favor, digite o seu nome.";
    } else {
        // Check if username exists
        $sql = "SELECT id FROM users WHERE username = ?";
        if ($stmt = mysqli_prepare($link, $sql)) {
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            $param_username = trim($_POST["username"]);
            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_store_result($stmt);
                if (mysqli_stmt_num_rows($stmt) == 1) {
                    $username_err = "Este nome já existe.";
                } else {
                    $username = trim($_POST["username"]);
                }
            } else {
                echo "Oops! Algo correu mal. Por favor tente mais tarde.";
            }
            mysqli_stmt_close($stmt);
        }
    }

    // Validate password
    if (empty(trim($_POST["password"]))) {
        $password_err = "Coloque a password.";
    } elseif (strlen(trim($_POST["password"])) < 6) {
        $password_err = "A password tem que ter pelo menos 6 caracteres.";
    } else {
        $password = trim($_POST["password"]);
    }

    // Validate confirm password
    if (empty(trim($_POST["confirm_password"]))) {
        $confirm_password_err = "Por favor, confirme a password.";
    } else {
        $confirm_password = trim($_POST["confirm_password"]);
        if (empty($password_err) && ($password != $confirm_password)) {
            $confirm_password_err = "Password não corresponde.";
        }
    }

    // Validate telemovel
    if (empty(trim($_POST["telemovel"]))) {
        $telemovel_err = "Coloque o telemóvel.";
    } elseif (!preg_match("/^\d{9}$/", trim($_POST["telemovel"]))) {
        $telemovel_err = "O telemóvel deve conter exatamente 9 dígitos.";
    } else {
        $telemovel = trim($_POST["telemovel"]);
    }

    // Validate data de nascimento
    if (empty(trim($_POST["data_de_nascimento"]))) {
        $data_de_nascimento_err = "Coloque a data de nascimento.";
    } else {
        $data_de_nascimento = trim($_POST["data_de_nascimento"]);
    }

    // Validate peso
    if (empty(trim($_POST["peso"]))) {
        $peso_err = "Coloque o seu peso.";
    } elseif (!is_numeric(trim($_POST["peso"])) || trim($_POST["peso"]) <= 0) {
        $peso_err = "Peso inválido.";
    } else {
        $peso = trim($_POST["peso"]);
    }

    // Validate altura
    if (empty(trim($_POST["altura"]))) {
        $altura_err = "Coloque a sua altura.";
    } elseif (!is_numeric(trim($_POST["altura"])) || trim($_POST["altura"]) <= 0) {
        $altura_err = "Altura inválida.";
    } else {
        $altura = trim($_POST["altura"]);
    }

    // Check input errors before inserting in database
    if (empty($username_err) && empty($password_err) && empty($confirm_password_err) && empty($telemovel_err) && empty($data_de_nascimento_err) && empty($peso_err) && empty($altura_err)) {
        // Prepare an insert statement
        $sql = "INSERT INTO users (username, password, telemovel, data_de_nascimento, peso, altura) VALUES (?, ?, ?, ?, ?, ?)";
        if ($stmt = mysqli_prepare($link, $sql)) {
            mysqli_stmt_bind_param($stmt, "ssssss", $param_username, $param_password, $param_telemovel, $param_data_de_nascimento, $param_peso, $param_altura);

            // Set parameters
            $param_username = $username;
            $param_password = password_hash($password, PASSWORD_DEFAULT);
            $param_telemovel = $telemovel;
            $param_data_de_nascimento = $data_de_nascimento;
            $param_peso = $peso;
            $param_altura = $altura;

            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                // Redirect to login page
                header("location: index.php");
                exit;
            } else {
                echo "Algo deu errado. Tente novamente mais tarde.";
            }
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
    <title>Sign Up</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="css/cssRegister.css">

    <style>
       body {
            background-image: url("img/DT.jpg");
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
            min-height: 100vh;
            font-family: 'Arial', sans-serif;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            color: #ddd; /* Texto mais claro em fundo escuro */
        }
    </style>
</head>
<body>
    <div class="box">
        <span class="text-center">Crie uma conta</span>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <!-- Form Fields -->
            <div class="input-container">
                <label for="nome">Nome</label>
                <input id="nome" type="text" name="username" value="<?php echo $username; ?>">
                <span class="help-block"><?php echo $username_err; ?></span>
            </div>
            <div class="input-container">
                <label for="password">Password</label>
                <input id="password" type="password" name="password">
                <span class="help-block"><?php echo $password_err; ?></span>
            </div>
            <div class="input-container">
                <label for="confirmpassword">Confirm Password</label>
                <input id="confirmpassword" type="password" name="confirm_password">
                <span class="help-block"><?php echo $confirm_password_err; ?></span>
            </div>
            <div class="input-container">
                <label for="telemovel">Telemóvel</label>
                <input id="telemovel" type="text" name="telemovel">
                <span class="help-block"><?php echo $telemovel_err; ?></span>
            </div>
            <div class="input-container">
                <label for="data">Data de nascimento</label>
                <input id="data" type="date" name="data_de_nascimento">
            </div>
            <div class="input-container">
                <label for="peso">Peso</label>
                <input id="peso" type="number" name="peso" step="0.1">
            </div>
            <div class="input-container">
                <label for="altura">Altura</label>
                <input id="altura" type="number" name="altura" step="0.01">
            </div>
            <div>
                <input type="submit" class="btn" value="Enviar">
                <input type="reset" class="btn" value="Apagar">
            </div>
            <p>Já tem uma conta? <a href="index.php">Clique aqui!</a>.</p>
        </form>
    </div>
</body>
</html>
