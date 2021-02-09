<?php
include('database.php');
require('protect.php');
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
<html lang="es">
  <head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge"/>
    <title>Ilunion - Personal</title>
    <!-- CSS files -->

    <script src="./dist/js/fontawesome.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" type="text/css" href="./dist/js/jquery.dataTables.css"/>
    <link href="./dist/css/tabler.min.css" rel="stylesheet"/>
    <link href="./dist/css/tabler-flags.min.css" rel="stylesheet"/>
    <link href="./dist/css/tabler-payments.min.css" rel="stylesheet"/>
    <link href="./dist/css/tabler-vendors.min.css" rel="stylesheet"/>
    <link href="./dist/css/demo.min.css" rel="stylesheet"/>
  </head>
  <body class="antialiased">
    <div class="page">
      <?php include("topbar.php");?>
      <div class="content">
        <div class="container-xl">
          <!-- Page title -->
          <?php
            if($info_usuario["tipo"] == "supervisor")
            {
              echo '
              
              <h1>Insertar nuevo operario</h1>
              <div class="page-header">
              <input type="text" required class="form-control" value="" name="causa-ausente" placeholder="Numero de operario">
              <input type="text" required class="form-control" value="" name="causa-ausente" placeholder="Nombre del operario">
              <input type="text" class="form-control" value="" name="causa-ausente" placeholder="Numero de telefono (opcional)">
              <br>
              <button type="submit" class="btn btn-primary ms-auto">Insertar</button>
              </div>';
            }
            ?>
            <br>
          <div class="page-header d-print-none">
          
            <div class="row align-items-center">
            
              <div class="col">
                <h2 class="page-title">
                  Personal
                </h2>
              </div>
            </div>
          </div>
          <table id="table_id" class="display">
    <thead>
        <tr>
            <th>Número</th>
            <th>Turno</th>
            <th>Nombre</th>
            <th>Telefono</th>
            <?php
            if($info_usuario["tipo"]=="supervisor")
            {
              echo '<th></th>';
            }
            ?>
        </tr>
    </thead>
    <tbody>
        <?php
        
          $sql = "SELECT * FROM personal";
          if($do = mysqli_query($link, $sql))
          {
            while($row = mysqli_fetch_assoc($do))
            {
              if($info_usuario["tipo"] != "supervisor")
              {
                echo("<tr>
              <td>".$row["num_operario"]."</td>
              <td>".$row["turno"]."</td>
              <td>".$row["nombre"]."</td>
              <td>".$row["telefono"]."</td>
              </tr>");
              }else
              {
                echo("<tr>
              <td>".$row["num_operario"]."</td>
              <td>".$row["turno"]."</td>
              <td>".$row["nombre"]."</td>
              <td>".$row["telefono"]."</td>
              <td><a class='fas fa-trash' href='#'></a></td>
              </tr>");
              }
            }
          }

        ?>
    </tbody>
</table>
        </div>
      </div>
    </div>
    <script type="text/javascript" src="./dist/js/jquery-3.5.1.js"></script>
    <script type="text/javascript" src="./dist/js/jquery.dataTables.js"></script>
    <!-- Libs JS -->
    <script src="./dist/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Tabler Core -->
    <script src="./dist/js/tabler.min.js"></script>

    
<script>
$(document).ready( function () {
    $('#table_id').DataTable();
} );
</script>
  </body>
</html>