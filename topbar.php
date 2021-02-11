<?php
if (!isset($_SESSION)) {
  session_start();
}
$unix = time();
$iduser = $_SESSION["user_id"];
$sql = "SELECT * FROM usuarios WHERE id = $iduser";
if ($do = mysqli_query($link, $sql)) {
  $info_usuario = mysqli_fetch_assoc($do);
}
if (isset($_POST["clave_vieja"])) {
  $clave_vieja = $_POST["clave_vieja"];
  $clave_nueva = $_POST["clave_nueva"];

  $sql = "SELECT * FROM usuarios WHERE id = '$iduser'";
  if ($do = mysqli_query($link, $sql)) {
    $info_user = mysqli_fetch_assoc($do);
    if (password_verify($clave_vieja, $info_user["clave"])) {
      $passhash = password_hash($clave_nueva, PASSWORD_DEFAULT);
      $sql = "UPDATE `usuarios` SET `clave` = '$passhash' WHERE `usuarios`.`id` = $iduser;";
      if ($do = mysqli_query($link, $sql)) {
        session_start();
        session_unset();
        session_destroy();
        header("location: login.php?nice=7");
      }
    }
  }
}
if (isset($_POST["cuenta_nombre"])) {
  $nombre = $_POST["cuenta_nombre"];
  $mail = $_POST["cuenta_mail"];
  $sql = "UPDATE `usuarios` SET `nombre` = '$nombre', `mail` = '$mail' WHERE `usuarios`.`id` = $iduser";
  if ($do = mysqli_query($link, $sql)) {
    header('location: index.php?nice=8');
  }else
  {
    echo mysqli_error($link);
    exit;
  }
  if (isset($_FILES['uploadedFile']) && $_FILES['uploadedFile']['error'] === UPLOAD_ERR_OK) {
    $fileTmpPath = $_FILES['uploadedFile']['tmp_name'];
    $fileName = $_FILES['uploadedFile']['name'];
    $fileSize = $_FILES['uploadedFile']['size'];
    $fileType = $_FILES['uploadedFile']['type'];
    $fileNameCmps = explode(".", $fileName);
    $fileExtension = strtolower(end($fileNameCmps));
    $allowedfileExtensions = array('jpg', 'png');
    if (in_array($fileExtension, $allowedfileExtensions)) {
      $uploadFileDir = 'dist/img/avatar/';
      $newFileName = $iduser . '.png';
      $dest_path = $uploadFileDir . $newFileName;
      if (file_exists($dest_path)) {
        unlink($dest_path);
      }
      if (move_uploaded_file($fileTmpPath, $dest_path)) {
        $message = 'File is successfully uploaded.';
      } else {
        echo 'There was some error moving the file to upload directory. Please make sure the upload directory is writable by web server.';
        exit;
      }
    } else {
      echo "Error: No es un archivo valido";
      exit;
    }
  }
}

if(isset($_POST["mail_server"]))
{
  $mail_server = $_POST["mail_server"];
  $mail_port = $_POST["mail_port"];
  $mail_user = $_POST["mail_user"];
  $mail_pass = $_POST["mail_pass"];
  $mail_to = $_POST["mail_to"];

  $sql = "UPDATE `ajustes` SET `value` = '$mail_server' WHERE `ajustes`.`nombre` = 'mailserver'";
  if($do = mysqli_query($link, $sql))
  {}else{
    echo "Error en la base de datos al meter el servidor de mail";
    exit;
  }
  $sql = "UPDATE `ajustes` SET `value` = '$mail_port' WHERE `ajustes`.`nombre` = 'mailport'";
  if($do = mysqli_query($link, $sql))
  {}else{
    echo "Error en la base de datos al meter el puerto de mail";
    exit;
  }
  $sql = "UPDATE `ajustes` SET `value` = '$mail_user' WHERE `ajustes`.`nombre` = 'mailuser'";
  if($do = mysqli_query($link, $sql))
  {}else{
    echo "Error en la base de datos al meter el usuario de mail";
    exit;
  }
  $sql = "UPDATE `ajustes` SET `value` = '$mail_pass' WHERE `ajustes`.`nombre` = 'mailpass'";
  if($do = mysqli_query($link, $sql))
  {}else{
    echo "Error en la base de datos al meter la clave de mail";
    exit;
  }
  $sql = "UPDATE `ajustes` SET `value` = '$mail_to' WHERE `ajustes`.`nombre` = 'mailto'";
  if($do = mysqli_query($link, $sql))
  {}else{
    echo "Error en la base de datos al meter los destinatarios de mail";
    exit;
  }
}

?>
<header class="navbar navbar-expand-md navbar-light d-print-none">
  <div class="container-xl">
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar-menu">
      <span class="navbar-toggler-icon"></span>
    </button>
    <h1 class="navbar-brand navbar-brand-autodark d-none-navbar-horizontal pe-0 pe-md-3">
      <a href=".">
        <img src="dist/img/logos/logo.png" width="110" height="80" alt="Tabler" class="">
      </a>
      <?php
      if (isset($_SESSION["turno"])) {
        $turno_id = $_SESSION["turno"];
        $sql = "SELECT * FROM turnos WHERE id = '$turno_id'";
        if ($do = mysqli_query($link, $sql)) {
          $info_turno = mysqli_fetch_assoc($do);
          $currentWeekNumber = date('W');
          echo ('Turno ' . $info_turno["tipo"] . '#' . $turno_id . '.  Semana: ' . $currentWeekNumber);
        }
      }
      ?>

    </h1>
    <div class="navbar-nav flex-row order-md-last">

      <div class="nav-item dropdown">
        <a href="#" class="nav-link d-flex lh-1 text-reset p-0" data-bs-toggle="dropdown" aria-label="Open user menu">
          <?php
          if (file_exists("./dist/img/avatar/" . $iduser . ".png")) {
            echo ('<span class="avatar avatar-sm" style="background-image: url(./dist/img/avatar/' . $iduser . '.png)"></span>');
          } else {
            echo ('<span class="avatar avatar-sm" style="background-image: url(./dist/img/avatar/default.png)"></span>');
          }
          ?>

          <div class="d-none d-xl-block ps-2">
            <div><?php echo $info_usuario["nombre"] ?></div>
            <div class="mt-1 small text-muted"></div>
          </div>
        </a>
        <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
          <a href="#" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#cambiar-cuenta">Cuenta</a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#cambiar-clave">Cambiar Contraseña</a>
          <?php
          if ($info_usuario["tipo"] == "admin") {
            echo '<a href="#" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#cambiar-mail">Configurar Email Automatico</a>';
          }
          ?>
          <a href="./logout.php" class="dropdown-item">Cerrar Sesión</a>
        </div>
      </div>
    </div>
  </div>
</header>
<div class="navbar-expand-md">
  <div class="collapse navbar-collapse" id="navbar-menu">
    <div class="navbar navbar-light">
      <div class="container-xl">
        <ul class="navbar-nav">
          <li class="nav-item">
            <a class="nav-link" href="./index.php">
              <span class="nav-link-icon d-md-none d-lg-inline-block"><svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                  <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                  <polyline points="5 12 3 12 12 3 21 12 19 12" />
                  <path d="M5 12v7a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-7" />
                  <path d="M9 21v-6a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v6" />
                </svg>
              </span>
              <span class="nav-link-title">
                Inicio
              </span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="./tables.php">
              <span class="nav-link-icon d-md-none d-lg-inline-block"><svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                  <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                  <polyline points="9 11 12 14 20 6" />
                  <path d="M20 12v6a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2h9" />
                </svg>
              </span>
              <span class="nav-link-title">
                Personal
              </span>
            </a>
          </li>
          <?php
          if (isset($_SESSION["turno"])) {
            echo ('<li class="nav-item">
                  <a class="nav-link" href="./resumen.php" >
                    <span class="nav-link-icon d-md-none d-lg-inline-block"><svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 3v4a1 1 0 0 0 1 1h4" /><path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" /><line x1="9" y1="9" x2="10" y2="9" /><line x1="9" y1="13" x2="15" y2="13" /><line x1="9" y1="17" x2="15" y2="17" /></svg>
                    </span>
                    <span class="nav-link-title">
                      Informe
                    </span>
                  </a>
                </li><li class="nav-item">
                <a class="nav-link" href="./index.php?cerrar=1"  data-bs-toggle="modal" data-bs-target="#modal-seguro">
                  <span class="nav-link-icon d-md-none d-lg-inline-block"><svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 3v4a1 1 0 0 0 1 1h4" /><path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" /><line x1="9" y1="9" x2="10" y2="9" /><line x1="9" y1="13" x2="15" y2="13" /><line x1="9" y1="17" x2="15" y2="17" /></svg>
                  </span>
                  <span class="nav-link-title">
                    Cerrar Turno
                  </span>
                </a>
              </li>');
          } else {
            $sql = "SELECT * FROM turnos WHERE encargado = '$iduser' AND estado = 'abierto'";
            if ($do = mysqli_query($link, $sql)) {
              $turno = 0;
              while ($row = mysqli_fetch_assoc($do)) {
                $turno++;
                echo ('<li class="nav-item">
                    <a class="nav-link" href="./index.php?reanudar=1" >
                      <span class="nav-link-title">
                        Reanudar Turno
                      </span>
                    </a>
                  </li><li class="nav-item">
                  <a class="nav-link" href="./index.php?cerrar=1"  data-bs-toggle="modal" data-bs-target="#modal-seguro">
                    <span class="nav-link-icon d-md-none d-lg-inline-block"><svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 3v4a1 1 0 0 0 1 1h4" /><path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" /><line x1="9" y1="9" x2="10" y2="9" /><line x1="9" y1="13" x2="15" y2="13" /><line x1="9" y1="17" x2="15" y2="17" /></svg>
                    </span>
                    <span class="nav-link-title">
                      Cerrar Turno
                    </span>
                  </a>
                </li>');
              }
              if ($turno == 0) {
                echo ('<li class="nav-item">
                    <a class="nav-link" href="./index.php?comenzar=1" data-bs-toggle="modal" data-bs-target="#modal-turno">
                      <span class="nav-link-title">
                        Comenzar Turno
                      </span>
                    </a>
                  </li>');
              }
            }
          }
          ?>
        </ul>
        <div class="my-2 my-md-0 flex-grow-1 flex-md-grow-0 order-first order-md-last">
          <form action="." method="get">
            <div class="input-icon">
              <span class="input-icon-addon">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                  <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                  <circle cx="10" cy="10" r="7" />
                  <line x1="21" y1="21" x2="15" y2="15" />
                </svg>
              </span>
              <input type="text" class="form-control" placeholder="Buscar…" aria-label="Search in website">
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="modal modal-blur fade" id="modal-turno" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Comenzar turno</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="./index.php?comenzar=1" method="POST">
        <div class="modal-body">
          <h3><?php echo date('d-m-Y', time()) ?></h3>
          <div class="row">

            <div class="col-lg-12">
              <div class="mb-3">
                <label class="form-label">Turno</label>
                <select class="form-select" name="tipo_turno">
                  <option value="TA" selected>TA</option>
                  <option value="TB">TB</option>
                  <option value="TC">TC</option>
                  <option value="TD">TD</option>
                </select>
              </div>
            </div>
          </div>
        </div>

        <div class="modal-footer">
          <a href="#" class="btn btn-link link-secondary" data-bs-dismiss="modal">
            Cancelar
          </a>
          <button type="submit" class="btn btn-primary ms-auto">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
              <path stroke="none" d="M0 0h24v24H0z" fill="none" />
              <line x1="12" y1="5" x2="12" y2="19" />
              <line x1="5" y1="12" x2="19" y2="12" />
            </svg>
            Comenzar nuevo turno
          </button>
      </form>
    </div>
  </div>
</div>
</div>

<div class="modal modal-blur fade" id="modal-seguro" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Comenzar turno</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="./index.php?comenzar=1" method="POST">
        <div class="modal-body">
          <h3>¿Estás seguro de que quieres cerrar el turno?</h3>
          <div class="row">
          </div>
        </div>

        <div class="modal-footer">
          <a href="#" class="btn btn-link link-secondary" data-bs-dismiss="modal">
            Cancelar
          </a>
          <a href="./index.php?cerrar=1" class="btn btn-danger ms-auto">
            Si, cerrar el turno.
          </a>
      </form>
    </div>
  </div>
</div>
</div>

<div class="modal modal-blur fade" id="cambiar-clave" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <form action="" method="post">
        <div class="modal-header">
          <h5 class="modal-title">Cambiar Contraseña</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-lg-12">
              <div class="mb-3">
                <label class="form-label">Contraseña Actual</label>
                <div class="input-group input-group-flat">
                  <span class="input-group-text">
                  </span>
                  <input type="password" name="clave_vieja" required class="form-control ps-0" value="" placeholder="Escribe aqui..." autocomplete="off">
                </div>
              </div>
              <div class="mb-3">

                <input type="hidden" name="cambiar_clave" value="1">
                <label class="form-label">Contraseña Nueva</label>
                <div class="input-group input-group-flat">
                  <span class="input-group-text">
                  </span>
                  <input type="password" name="clave_nueva" id="password" required class="form-control ps-0" value="" placeholder="Escribe aqui..." autocomplete="off">
                </div>
              </div>
              <div class="mb-3">
                <label class="form-label">Repite Contraseña Nueva</label>
                <div class="input-group input-group-flat">
                  <span class="input-group-text">
                  </span>
                  <input type="password" name="clave_nueva2" id="confirm_password" required class="form-control ps-0" value="" placeholder="Escribe aqui..." autocomplete="off">
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="modal-footer">
          <a href="#" class="btn btn-link link-secondary" data-bs-dismiss="modal">
            Cancelar
          </a>
          <button type="submit" class="btn btn-primary ms-auto">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
              <path stroke="none" d="M0 0h24v24H0z" fill="none" />
              <line x1="12" y1="5" x2="12" y2="19" />
              <line x1="5" y1="12" x2="19" y2="12" />
            </svg>
            Cambiar Contraseña
          </button>

        </div>

      </form>

    </div>
  </div>
</div>
<div class="modal modal-blur fade" id="cambiar-mail" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <form action="" method="post">
        <div class="modal-header">
          <h5 class="modal-title">Configurar Email</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-lg-12">
              <div class="mb-3">
                <?php
                $sql = "SELECT * FROM ajustes WHERE nombre = 'mailserver'";
                $do = mysqli_query($link, $sql);
                $row = mysqli_fetch_assoc($do);
                ?>
                <label class="form-label">Servidor SMTP</label>
                <div class="input-group input-group-flat">
                  <span class="input-group-text">
                  </span>
                  <input type="text" name="mail_server" required class="form-control ps-0" value="<?php echo $row["value"] ?>" placeholder="Escribe aqui..." autocomplete="off">
                </div>
              </div>
              <div class="mb-3">
                <?php
                $sql = "SELECT * FROM ajustes WHERE nombre = 'mailport'";
                $do = mysqli_query($link, $sql);
                $row = mysqli_fetch_assoc($do);
                ?>
                <label class="form-label">Puerto Servidor</label>
                <div class="input-group input-group-flat">
                  <span class="input-group-text">
                  </span>
                  <input type="text" name="mail_port" required class="form-control ps-0" value="<?php echo $row["value"] ?>" placeholder="Escribe aqui..." autocomplete="off">
                </div>
              </div>
              <div class="mb-3">
                <?php
                $sql = "SELECT * FROM ajustes WHERE nombre = 'mailuser'";
                $do = mysqli_query($link, $sql);
                $row = mysqli_fetch_assoc($do);
                ?>
                <label class="form-label">Usuario</label>
                <div class="input-group input-group-flat">
                  <span class="input-group-text">
                  </span>
                  <input type="text" name="mail_user" required class="form-control ps-0" value="<?php echo $row["value"] ?>" placeholder="Escribe aqui..." autocomplete="off">
                </div>
              </div>
              <div class="mb-3">
                <?php
                $sql = "SELECT * FROM ajustes WHERE nombre = 'mailpass'";
                $do = mysqli_query($link, $sql);
                $row = mysqli_fetch_assoc($do);
                ?>
                <label class="form-label">Clave</label>
                <div class="input-group input-group-flat">
                  <span class="input-group-text">
                  </span>
                  <input type="password" name="mail_pass" required class="form-control ps-0" value="<?php echo $row["value"] ?>" placeholder="Escribe aqui..." autocomplete="off">
                </div>
              </div>
            </div>
            <div class="mb-3">
              <?php
              $sql = "SELECT * FROM ajustes WHERE nombre = 'mailto'";
              $do = mysqli_query($link, $sql);
              $row = mysqli_fetch_assoc($do);
              ?>
              <label class="form-label">Destinatarios (separar cada mail usando ;)</label>
              <div class="input-group input-group-flat">
                <span class="input-group-text">
                </span>
                <input type="text" name="mail_to" required class="form-control ps-0" value="<?php echo $row["value"] ?>" placeholder="Escribe aqui..." autocomplete="off">
              </div>
            </div>
          </div>
        </div>


        <div class="modal-footer">
          <a href="#" class="btn btn-link link-secondary" data-bs-dismiss="modal">
            Cancelar
          </a>
          <button type="submit" class="btn btn-primary ms-auto">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
              <path stroke="none" d="M0 0h24v24H0z" fill="none" />
              <line x1="12" y1="5" x2="12" y2="19" />
              <line x1="5" y1="12" x2="19" y2="12" />
            </svg>
            Guardar
          </button>

        </div>

      </form>
    </div>
  </div>
</div>
<div class="modal modal-blur fade" id="cambiar-cuenta" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <form action="" method="post" enctype="multipart/form-data">
        <div class="modal-header">
          <h5 class="modal-title">Configurar Cuenta</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-lg-12">
              <div class="mb-3">
                <img src="dist/img/avatar/<?php echo $info_usuario["id"] ?>.png" width="150px" height="150px" style="border-radius: 5px;" alt="">
                <input type="file" name="uploadedFile" />
                <br>
                <label class="form-label">Nombre</label>
                <div class="input-group input-group-flat">
                  <span class="input-group-text">
                  </span>
                  <input type="text" name="cuenta_nombre" required class="form-control ps-0" value="<?php echo $info_usuario["nombre"] ?>" placeholder="Escribe aqui..." autocomplete="off">
                </div>
              </div>
              <div class="mb-3">
                <label class="form-label">Email</label>
                <div class="input-group input-group-flat">
                  <span class="input-group-text">
                  </span>
                  <input type="text" name="cuenta_mail" required class="form-control ps-0" value="<?php echo $info_usuario["mail"] ?>" placeholder="Escribe aqui..." autocomplete="off">
                </div>
              </div>
              <?php
              if($info_usuario["tipo"] == "supervisor")
              {
                echo '<div class="mb-3">
                <label class="form-label">API</label>
                <div class="input-group input-group-flat">
                  <p>'.$info_usuario["api"].'</p><a href="index.php?gapi=1" class="btn btn-link link-secondary">Generar clave API</a>
                  </div>
              </div>';
              }
              ?>
            </div>
          </div>
        </div>

        <div class="modal-footer">
          <a href="#" class="btn btn-link link-secondary" data-bs-dismiss="modal">
            Cancelar
          </a>
          <button type="submit" name="cuenta" class="btn btn-primary ms-auto">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
              <path stroke="none" d="M0 0h24v24H0z" fill="none" />
              <line x1="12" y1="5" x2="12" y2="19" />
              <line x1="5" y1="12" x2="19" y2="12" />
            </svg>
            Guardar
          </button>

        </div>

      </form>

    </div>
  </div>
</div>
<script>
  var password = document.getElementById("password"),
    confirm_password = document.getElementById("confirm_password");

  function validatePassword() {
    if (password.value != confirm_password.value) {
      confirm_password.setCustomValidity("Las contraseñas no coinciden.");
    } else {
      confirm_password.setCustomValidity('');
    }
  }

  password.onchange = validatePassword;
  confirm_password.onkeyup = validatePassword;
</script>