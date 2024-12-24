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
<html lang="pt">
<head>
  <meta charset="UTF-8">
  <link rel="stylesheet" href="css/cssBootstrap/bootstrap.min.css">    
  <link rel="stylesheet" href="css/cssWelcome.css">
  <title>Bem-Vindo!</title>
  <style>
    body{
      background-image: url("img/imagem-welcome.jpeg");
    }
  </style>
</head>
<body>
  <div class="page-header">
    <h1 style="font-size: 80px; color:white;" >Olá, <b class="nome" style="color: green;"><?php echo htmlspecialchars($_SESSION["username"]); ?></b>. Bem-vindo ao nosso site.</h1>
  </div>
  <br></br>

  <div class="calorias">
    <h1> Não perca a <span style="color: green;">Paciência</span>, perca <span style="color: green;">Calorias</span>!</h1>
  </div>
  
  <div class="menu-wrap">
    <input type="checkbox" class="toggler"/>
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
  
  <div style="text-align: center; margin-top: 5%;">
    <a href="menu.html" class="botaoSite">Continuar</a>
  </div>
</body>
</html>