<?php
// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect him to login page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="css/cssWelcome.css">
    <style>
    </style>
</head>
<body>
    <div class="page-header">
        <h1>Olá, <b class="nome"><?php echo htmlspecialchars($_SESSION["username"]); ?></b>. Bem-vindo ao nosso site.</h1>
    </div>

    <div class="menu-wrap">
        <input type="checkbox" class="toggler" />
        <div class="hamburger">
            <div></div>
        </div>
        <div class="menu">
            <div>
                <ul>
                    <li><a href="logout.php">Sair</a></li><br><br>
                    <li><a href="reset-password.php"> Mudar Password</a></li><br><br>
                    <li><a href="reset-peso.php">Mudar Peso</a></li><br><br>
                    <li><a href="reset-altura.php">Mudar Altura</a></li><br>
                </ul>
            </div>
        </div>
    </div>

    <div style="text-align: center; margin-top: 35%;">
        <a href="menu.html" class="botaoSite">Continuar</a>
    </div>
</body>
</html>
