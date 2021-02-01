<?php
/* ADMIN pass: 1234qwerty */
if(isset($_SESSION['user_id']))
{
  header("location: index.php");
}
if(isset($_POST["user"]) && isset($_POST["pass"]))
{
$nombre = $_POST["user"];
$pass = $_POST["pass"];
include('database.php');
$sql = "SELECT * FROM usuarios WHERE mail = '$nombre'";
$do = mysqli_query($link, $sql);
$result = mysqli_fetch_assoc($do);
if(password_verify($pass, $result["clave"]))
{
  session_start();
  $_SESSION['user_id'] = $result['id'];
  $hora = time();
  $idusuario = $result['id'];
  $sql = "UPDATE `usuarios` SET `last_login` = '$hora' WHERE `usuarios`.`id` = $idusuario";
  mysqli_query($link, $sql);
  header("location: index.php");
}
}
?>

<!doctype html>
<!--
* Tabler - Premium and Open Source dashboard template with responsive and high quality UI.
* @version 1.0.0-alpha.22
* @link https://tabler.io
* Copyright 2018-2021 The Tabler Authors
* Copyright 2018-2021 codecalm.net Paweł Kuna
* Licensed under MIT (https://github.com/tabler/tabler/blob/master/LICENSE)
-->
<html lang="en">
  <head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge"/>
    <title>Login</title>
    <!-- CSS files -->
    <link href="./dist/css/tabler.min.css" rel="stylesheet"/>
    <link href="./dist/css/tabler-flags.min.css" rel="stylesheet"/>
    <link href="./dist/css/tabler-payments.min.css" rel="stylesheet"/>
    <link href="./dist/css/tabler-vendors.min.css" rel="stylesheet"/>
    <link href="./dist/css/demo.min.css" rel="stylesheet"/>
  </head>
  <body class="antialiased border-top-wide border-primary d-flex flex-column">
    <div class="flex-fill d-flex flex-column justify-content-center py-4">
      <div class="container-tight py-6">
        <div class="text-center mb-1">
          <a href="."><img src="./dist/img/logos/ilunion.png" height="160" alt=""></a>
        </div>
        <form class="card card-md" action="" method="post" autocomplete="off">
          <div class="card-body">
            <h2 class="card-title text-center mb-4">Inicia sesion en tu cuenta</h2>
            <div class="mb-3">
              <label class="form-label">Email</label>
              <input type="email" name="user" class="form-control" placeholder="Introduce el email">
            </div>
            <div class="mb-2">
              <label class="form-label">
                Contraseña
                <span class="form-label-description">
                  <a href="./forgot-password.html">Olvidé la contraseña</a>
                </span>
              </label>
              <div class="input-group input-group-flat">
                <input type="password" name="pass" class="form-control"  placeholder="Password"  autocomplete="off">
                <span class="input-group-text">
                </span>
              </div>
            </div>
            <div class="form-footer">
              <button type="submit" class="btn btn-primary w-100">Iniciar sesión</button>
            </div>
          </div>
          <div class="card-body">
            
          </div>
        </form>
      </div>
    </div>
    <!-- Libs JS -->
    <script src="./dist/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Tabler Core -->
    <script src="./dist/js/tabler.min.js"></script>
  </body>
</html>