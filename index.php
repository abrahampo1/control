<?php
ini_set( 'error_reporting', E_ALL );
ini_set( 'display_errors', true );
include('database.php');
require('protect.php');
include('errores.php');
include('nice.php');
$fecha_ahora = date('Y-m-d', time());
$dias = 7;
$incidencias_totales = 0;
$reportes_totales = 0;
$auditorias_totales = 0;
$ausencias_totales = 0;
$iduser = $_SESSION["user_id"];
$sql = "SELECT * FROM usuarios WHERE id = $iduser";
$do = mysqli_query($link, $sql);
$datos_usuario = mysqli_fetch_assoc($do);
if(isset($_GET["ecambio"]))
{
	$cambio_a_eliminar = $_GET["ecambio"];
	$sql = "DELETE FROM `cambios` WHERE `cambios`.`id` = $cambio_a_eliminar";
	if($do = mysqli_query($link, $sql))
	{
		header("location: index.php?nice=4");
	}else
	{
		header("location: index.php?err=7");
	}
}
if(isset($_GET["eauditoria"]))
{
	$cambio_a_eliminar = $_GET["eauditoria"];
	$sql = "DELETE FROM `auditorias` WHERE `auditorias`.`id` = $cambio_a_eliminar";
	if($do = mysqli_query($link, $sql))
	{
		header("location: index.php?nice=4");
	}else
	{
		header("location: index.php?err=7");
	}
}
if(isset($_GET["ereporte"]))
{
	$cambio_a_eliminar = $_GET["ereporte"];
	$sql = "DELETE FROM `reportes` WHERE `reportes`.`id` = $cambio_a_eliminar";
	if($do = mysqli_query($link, $sql))
	{
		header("location: index.php?nice=4");
	}else
	{
		header("location: index.php?err=7");
	}
}
if(isset($_GET["eincidencia"]))
{
	$cambio_a_eliminar = $_GET["eincidencia"];
	$sql = "DELETE FROM `incidencias` WHERE `incidencias`.`id` = $cambio_a_eliminar";
	if($do = mysqli_query($link, $sql))
	{
		header("location: index.php?nice=4");
	}else
	{
		header("location: index.php?err=7");
	}
}
for($i=1;$i<=1;$i++)
{
$resta = $i - $dias;
$turno_numero = 0;
$fecha_variable = date("Y-m-d", time());
if(isset($_SESSION["turno"]))
{
	$turno_numero = $_SESSION["turno"];
}
if($resta == 0)
{
	$fecha_variable = $fecha_ahora;
}
$sql = "SELECT * FROM incidencias WHERE turno = '$turno_numero'";
if($fo = mysqli_query($link, $sql))
{
	$incidencias_totales += $fo->num_rows;
}
$sql = "SELECT * FROM reportes WHERE turno = '$turno_numero'";
if($fo = mysqli_query($link, $sql))
{
	$reportes_totales += $fo->num_rows;
}
$sql = "SELECT * FROM auditorias WHERE turno = '$turno_numero'";
if($fo = mysqli_query($link, $sql))
{
	$auditorias_totales += $fo->num_rows;
}
$sql = "SELECT * FROM ausencias_rot WHERE turno = '$turno_numero'  AND ausente = 'true'";
if($fo = mysqli_query($link, $sql))
{
	$ausencias_totales += $fo->num_rows;
}
}
	
if(isset($_GET["comenzar"]) && isset($_POST["tipo_turno"]))
{
$tipo_turno = $_POST["tipo_turno"];
$unix = time();
$sql = "SELECT * FROM turnos WHERE encargado = '$iduser' and estado = 'abierto'";
$do = mysqli_query($link, $sql);
$turno_libre = true;
while($r = mysqli_fetch_assoc($do))
{
	$turno_libre = false;
}
if($turno_libre=true)
{
	$sql = "INSERT INTO `turnos` (`id`, `encargado`, `unix`, `estado`, `tipo`) VALUES (NULL, '$iduser', '$unix', 'abierto', '$tipo_turno')";
	mysqli_query($link, $sql);
	$_SESSION["turno"] = mysqli_insert_id($link);
	header("location: index.php");
}else
{
	header("location: index.php?err=6");
}
  
}
if(isset($_GET["reanudar"]))
{
	$sql = "SELECT * FROM turnos WHERE encargado = '$iduser' AND estado = 'abierto'";
				if($do = mysqli_query($link, $sql))
				{
					$info_turno = mysqli_fetch_assoc($do);
					$_SESSION["turno"] = $info_turno["id"];
					header("location: index.php");
				}
}
if(isset($_GET["cerrar"]))
{
	$sql = "SELECT * FROM turnos WHERE encargado = '$iduser' AND estado = 'abierto'";
				if($do = mysqli_query($link, $sql))
				{
					$info_turno = mysqli_fetch_assoc($do);
					$_SESSION["turno"] = $info_turno["id"];
				}
	$turno = $_SESSION["turno"];
	$sql = "UPDATE `turnos` SET `estado` = 'cerrado' WHERE `turnos`.`id` = $turno";
	if($do = mysqli_query($link, $sql))
	{
		unset($_SESSION["turno"]);
		header("location: index.php");
	}
}

if(isset($_POST['operario']))
{
$err = false;
if(isset($_POST["puesto1"]) && $_POST["horas1"])
{

}else
{
	if(!isset($_POST["ausente"]))
	{
		header('Location: index.php?err=3');
		$err = true;
	}
}
$ausente = "false";
$causa_ausencia = "";
$operario_nuevo = $_POST['operario'];
$puesto1_nuevo = $_POST["puesto1"];
if($puesto1_nuevo=="" && !isset($_POST["ausente"]))
{
	header("location: index.php?err=1");
	$err = true;
}
$horas1_nuevo = $_POST["horas1"];
$puesto2_nuevo = "";
$horas2_nuevo = 0;
if(isset($_POST["ausente"]))
{
	$ausente = "true";
	if(isset($_POST['causa-ausente']))
	{
		$causa_ausencia = $_POST["causa-ausente"];
	}
	if($_POST["causa-ausente"] == "")
	{
		header("Location: index.php?err=0");
		$err = true;
	}
}
if(isset($_POST["puesto2"]))
{
	$puesto2_nuevo = $_POST["puesto2"];
	
}
if(isset($_POST["horas2"]))
{
	$horas2_nuevo = $_POST["horas2"];
}
if(!$horas2_nuevo > 0)
{
	$horas2_nuevo = 0;
}
if(!$horas1_nuevo > 0)
{
	$horas1_nuevo = 0;
}
if($_POST["horas2"] == NULL && $_POST["puesto2"] != NULL)
	{
		header("Location: index.php?err=2");
		$err = true;
	}
$turno_id = $_SESSION["turno"];
$sql = "SELECT * FROM turnos WHERE id = '$turno_id'";
if($do = mysqli_query($link, $sql))
{
	$info_turno = mysqli_fetch_assoc($do);
	$turno_actual = $info_turno["tipo"];
	$fecha_unix = date("Y-m-d", time());
	if($causa_ausencia == NULL && $ausente == 'true')
	{
		header("Location: ./index.php?err=0");
		$err = true;
	}
	$sql2 = "SELECT * FROM ausencias_rot WHERE num_operario = '$operario_nuevo' AND turno = '$turno_id'";
	if($do = mysqli_query($link, $sql2)){
	$duplicado = false;
	while($fila = mysqli_fetch_assoc($do))
	{
		$duplicado=true;
	}
	if($err == false)
	{
		/* ESTO ES PARA DEBUG<<<< */
		if($duplicado == false){
		$sql = "INSERT INTO `ausencias_rot` (`id`, `fecha`, `num_operario`, `ausente`, `causa`, `puesto1`, `puesto2`, `horas1`, `horas2`, `turno`, `usuario`) VALUES (NULL, '$fecha_unix', '$operario_nuevo', '$ausente', '$causa_ausencia', '$puesto1_nuevo', '$puesto2_nuevo', '$horas1_nuevo', '$horas2_nuevo', '$turno_id', '$iduser')";
		if($do = mysqli_query($link, $sql))
		{
			header('location: index.php?nice=3');
		}else
		{
			
		echo mysqli_error($link);
	}
	}else
	{
		header('Location: ./index.php?err=4');
	}
}
}else
	{
		print(mysqli_error($link));
		exit;
	}
	
	}else
	{
		
	echo mysqli_error($link);
	}
}

if(isset($_POST["borrar_ficha"]))
{
	$operario_borrar = $_POST["borrar_ficha"];
	$sql = "DELETE FROM `ausencias_rot` WHERE `ausencias_rot`.`id` = $operario_borrar;";
	mysqli_query($link, $sql);
	header("location: index.php?nice=4");
}

if(isset($_POST["input_cambio"]))
{
	$turno_id = $_SESSION["turno"];
	$cambio = $_POST["input_cambio"];
	$sql = "INSERT INTO `cambios` (`id`, `fecha`, `turno`, `cambio`) VALUES (NULL, '$fecha_ahora', '$turno_id', '$cambio')";
	if(mysqli_query($link, $sql))
	{
		header("location: index.php?nice=5");
	}else
	{
		header("location: index.php?err=7");
	}
}

if(isset($_POST["operario_inc"]) && isset($_POST["accidente"]))
{
	$operario = $_POST["operario_inc"];
	$descripcion = $_POST["causa_inc"];
	$puesto = $_POST["puesto_inc"];
	$fecha = date("Y-m-d", time());
	$hora = date("G:i", time());
	$turno_id = $_SESSION["turno"];
	$sql = "SELECT * FROM personal WHERE id = '$operario'";
	$do = mysqli_query($link, $sql);
	$info_operario = mysqli_fetch_assoc($do);
	$nombre_operario = $info_operario["nombre"];
	if($_POST["accidente"] == 1)
	{
		$sql = "INSERT INTO `incidencias` (`id`, `fecha`, `turno`, `num_operario`, `operario`, `puesto`, `incidencia`, `hora`, `usuario`) VALUES (NULL, '$fecha', '$turno_id', '$operario', '$nombre_operario', '$puesto', '$descripcion', '$hora', '$iduser')";
	}else if($_POST["accidente"]==2)
	{
		$sql = "INSERT INTO `reportes` (`id`, `fecha`, `turno`, `num_operario`, `operario`, `puesto`, `reporte`, `hora`, `usuario`) VALUES (NULL, '$fecha', '$turno_id', '$operario', '$nombre_operario', '$puesto', '$descripcion', '$hora', '$iduser')";
	}
	if($do = mysqli_query($link,$sql))
	{
		header('location: index.php?nice=2');
	}else
	{
		echo mysqli_error($link);
		exit;
	}
}

if(isset($_POST["operario_audi"]))
{
	$operario_auditar = $_POST["operario_audi"];
	$seguridad = false;
	$medioambiente = false;
	$calidad = false;
	$cumplimientoepis = false;
	$propuesta = "";
	$cumplimientoepis = "";
	$acciones = "";
	if(isset($_POST["check1"]))
	{
		$seguridad = true;
	}
	if(isset($_POST["check2"]))
	{
		$medioambiente = true;
	}
	if(isset($_POST["check3"]))
	{
		$calidad = true;
	}
	if(isset($_POST["check4"]))
	{
		$cumplimientoepis = true;
	}
	if(isset($_POST["propuestas_audi"]))
	{
		$propuesta = $_POST["propuestas_audi"];
	}
	if(isset($_POST["acciones_audi"]))
	{
		$acciones = $_POST["acciones_audi"];
	}
	$turno = $_SESSION["turno"];
	$sql = "SELECT * FROM auditorias WHERE num_operario = '$operario_auditar' and turno = '$turno'";
	$do = mysqli_query($link, $sql);
	if($do->num_rows == 0)
	{
		$fecha = date('Y-m-d', time());
		$hora = date('H:i', time());
		$sql = "INSERT INTO `auditorias` (`turno`, `id`, `auditor`, `num_operario`, `seguridad`, `medioambiente`, `calidad`, `epis`, `propuestas`, `acciones`, `comentarios`, `fecha`, `hora`) VALUES ('$turno', NULL, '$iduser', '$operario_auditar', '$seguridad', '$medioambiente', '$calidad', '$cumplimientoepis', '$propuesta', '$acciones', '', '$fecha', '$hora')";
		if($does = mysqli_query($link, $sql))
		{
			header('location: index.php?nice=1');
		}else{
			print(mysqli_error($link));
			exit;
			header('location: index.php?err=7');
		}
	}
	else
	{
		header('location: index.php?err=6');
	}
}
?>
<!doctype html>
<html lang="es">
  <head>
    <meta charset="utf-8"/>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge"/>
    <title>Ilunion - Panel</title>
	<script src="./dist/js/fontawesome.js" crossorigin="anonymous"></script>
	<link rel="stylesheet" type="text/css" href="./dist/js/jquery.dataTables.css"/>
    <link href="./dist/libs/jqvmap/dist/jqvmap.min.css" rel="stylesheet"/>
    <link href="./dist/css/tabler.min.css" rel="stylesheet"/>
    <link href="./dist/css/tabler-flags.min.css" rel="stylesheet"/>
    <link href="./dist/css/tabler-payments.min.css" rel="stylesheet"/>
    <link href="./dist/css/tabler-vendors.min.css" rel="stylesheet"/>
    <link href="./dist/css/demo.min.css" rel="stylesheet"/>
  </head>
  <body class="antialiased">
    <div class="page">
      <?php include('topbar.php');?>
      <div class="content">
        <div class="container-xl">
          <div class="page-header d-print-none">
            <div class="row align-items-center">
              <div class="col">
                <div class="page-pretitle">
                  Resumen
                </div>
                <h2 class="page-title">
                  Administración
                </h2>
              </div>
              <div class="col-auto ms-auto d-print-none">
                <div class="btn-list">
				<?php
				if(isset($_SESSION["turno"]))
				{
					if($datos_usuario["tipo"] == "admin")
					{
						echo('<span class="d-none d-sm-inline">
						<a href="update.php" class="btn btn-white">
						 Actualizar via Github
						</a>
					  </span>');
					}
					echo('
					<span class="d-none d-sm-inline">
					  <a href="#" class="btn btn-white" data-bs-toggle="modal" data-bs-target="#hacer-cambio">
					   Hacer un cambio
					  </a>
					</span><span class="d-none d-sm-inline">
					<a href="#" class="btn btn-white" data-bs-toggle="modal" data-bs-target="#auditar">
					 Auditar
					</a>
				  </span><a href="#" class="btn btn-danger d-none d-sm-inline-block" data-bs-toggle="modal" data-bs-target="#modal-report">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="12" y1="5" x2="12" y2="19" /><line x1="5" y1="12" x2="19" y2="12" /></svg>
                    Reportar una irregularidad
                  </a>
                  <a href="#" class="btn btn-primary d-sm-none btn-icon" data-bs-toggle="modal" data-bs-target="#modal-report" aria-label="Create new report">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="12" y1="5" x2="12" y2="19" /><line x1="5" y1="12" x2="19" y2="12" /></svg>
                  </a>');
				}
				?>
                  
                </div>
              </div>
            </div>
          </div>
          <div class="row row-deck row-cards">
			  <?php
			 if(isset($_GET["err"]))
			 {
				 $mensaje = error($_GET["err"]);
				 echo('<div class="col-12">
				 <div class="card" style="color:red">
				   <div class="card-header">
					 <h3 class="card-title">Error</h3>
					 <a href="index.php" type="button" class="btn-close" style="position: absolute;
				   right: 5px;
				   top: 5px;
				   width: 32px;
				   height: 32px;
				   opacity: 0.3;" aria-label="Close"></a>
				   </div>
				   <div class="card-body">
					 <p>'.$mensaje.'</p>
					 <br>
					 <h4>Si no logra arreglar este error, comuiniqueselo al creador del programa Abraham Leiro Fernandez (<a href="mailto:abraham@cpsoftware.es">abraham@cpsoftware.es</a>)</h4>
				   </div>
				 </div>
			   </div>');
			 } 
			 if(isset($_GET["nice"]))
			 {
				 $mensaje = nice($_GET["nice"]);
				 echo('<div class="col-12">
				 <div class="card" style="color:green">
				   <div class="card-header">
				   <a href="index.php" type="button" class="btn-close" style="position: absolute;
				   right: 5px;
				   top: 5px;
				   width: 32px;
				   height: 32px;
				   opacity: 0.3;" aria-label="Close"></a>
					 <h3 class="card-title">OK</h3>
				   </div>
				   <div class="card-body">
					 <p>'.$mensaje.'</p>
				   </div>
				 </div>
			   </div>');
			 } 
			  ?>
          <div class="col-sm-6 col-lg-3">
              <div class="card">
                <div class="card-body">
                  <div class="d-flex align-items-center">
                    <div class="subheader">Incidencias</div>
                    <div class="ms-auto lh-1">
                    </div>
                  </div>
                  <div class="d-flex align-items-baseline">
                    <div class="h1 mb-0 me-2"><?php echo $incidencias_totales;?></div>
                    <div class="me-auto">
                    </div>
                  </div>
                </div>
                <div id="chart-incidencias-bg" class="chart-sm"></div>
              </div>
            </div>
            <div class="col-sm-6 col-lg-3">
              <div class="card">
                <div class="card-body">
                  <div class="d-flex align-items-center">
                    <div class="subheader">Reportes</div>
                    <div class="ms-auto lh-1">
                      
                    </div>
                  </div>
                  <div class="d-flex align-items-baseline">
                    <div class="h1 mb-0 me-2"><?php echo $reportes_totales?></div>
                    <div class="me-auto">
                    </div>
                  </div>
                </div>
                <div id="chart-revenue-bg" class="chart-sm"></div>
              </div>
            </div>
            <div class="col-sm-6 col-lg-3">
              <div class="card">
                <div class="card-body">
                  <div class="d-flex align-items-center">
                    <div class="subheader">Auditorias</div>
                    <div class="ms-auto lh-1">
                      
                    </div>
                  </div>
                  <div class="d-flex align-items-baseline">
                    <div class="h1 mb-3 me-2"><?php echo($auditorias_totales); ?></div>
                    <div class="me-auto">
                    </div>
                  </div>
                  
                </div><div id="chart-new-clients" class="chart-sm"></div>
              </div>
            </div>
            <div class="col-sm-6 col-lg-3">
              <div class="card">
                <div class="card-body">
                  <div class="d-flex align-items-center">
                    <div class="subheader">Ausencias</div>
                    <div class="ms-auto lh-1">
                      
                    </div>
                  </div>
                  <div class="d-flex align-items-baseline">
                    <div class="h1 mb-3 me-2"><?php echo $ausencias_totales?></div>
                    <div class="me-auto">
                    </div>
                  </div>
                  
                </div><div id="chart-active-users" class="chart-sm"></div>
              </div>
            </div>
		  </div>
		  <br>
		  <?php if(isset($_SESSION["turno"])){
$sql = "SELECT * FROM personal";
if($do = mysqli_query($link, $sql))
{}
echo('<div class="col-12">
<div class="card">
  <div class="card-header">
	<h3 class="card-title">Fichar operarios</h3>
  </div>
  <div class="card-body">
	<form action="./index.php" method="POST">
  <select name="operario" id="" required="" class="form-select">');
		  while($row = mysqli_fetch_assoc($do))
		  {
			  echo('<option value='.$row["id"].'>'.$row["num_operario"].' - '.$row['nombre'].'</option>');
		  }
		  echo('
  </select><br><label class="form-check">
  <input class="form-check-input" name="ausente" type="checkbox">
  <span class="form-check-label">Ausente</span>
</label>
<label class="form-label">Causa</label>
<input type="text" class="form-control" value="" name="causa-ausente" placeholder="En caso de ausente, intoducir causa, si no, dejar en blanco">
<br>
<h2>Rotaciones</h2>

<div class="row">
<div class="col-6">
<label class="form-label">Puesto 1</label>
<select name="puesto1" id="" class="form-select">
<option value="" selected>Seleccione</option>');
$sql = "SELECT * FROM puestos";
$do = mysqli_query($link, $sql);
		  while($row = mysqli_fetch_assoc($do))
		  {
			  echo('<option value='.$row["id"].'>'.$row['nombre'].'</option>');
		  }
		  echo('
  </select><br>
</div>
<div class="col-6">
<label class="form-label">Horas</label>
<input type="number" class="form-control" name="horas1" placeholder="Horas...">
<br>
</div>
</div>
<div class="row">
<div class="col-6">
<label class="form-label">Puesto 2 (opcional)</label>
<select name="puesto2" id="" class="form-select">
<option value="" selected>Seleccione</option>');
$sql = "SELECT * FROM puestos";
$do = mysqli_query($link, $sql);
		  while($row = mysqli_fetch_assoc($do))
		  {
			  echo('<option value='.$row["id"].'>'.$row['nombre'].'</option>');
		  }
		  echo('
  </select><br>
</div>
<div class="col-6">
<label class="form-label">Horas</label>
<input type="number" class="form-control" name="horas2" placeholder="Horas...">
<br>
</div>
</div>
  <button type="submit" class="btn btn-primary ms-auto">
  Fichar
  </button>
  </form>
');

		  }?><br>
		  <?php
		  if(isset($_SESSION["turno"])){
		  $turno = $_SESSION["turno"];
		  $sql = "SELECT * FROM ausencias_rot WHERE turno = $turno";
		  $do = mysqli_query($link, $sql);
		if(isset($_SESSION["turno"])&&$do->num_rows != 0)
		{
			echo('
		  <div class="col-12">
                          <div class="card" style="padding:20px">
						  <table id="table_id" class="display">
		  	<thead>
				  <tr>
					  <th>Nombre</th>
					  <th>Ausente</th>
					  <th>Causa</th>
					  <th>Puesto 1</th>
					  <th>Horas</th>
					  <th>Puesto 2</th>
					  <th>Horas</th>
					  <th>Fecha</th>
					  <th></th>
					  <th></th>
				  </tr>
			  </thead>
			  <tbody>');
			
			while($row = mysqli_fetch_assoc($do))
			{
				$num_operario = $row["num_operario"];
				$sql = "SELECT * FROM personal WHERE id = '$num_operario'";
				$buscar = mysqli_query($link, $sql);
				$datos_operador = mysqli_fetch_assoc($buscar);
				if($row['ausente']=="true")
				{
					$ausente = 'SI';
				}else
				{
					$ausente = 'NO';
				}
				$puesto1 = $row["puesto1"];
				$puesto2 = $row["puesto2"];
			
				if($puesto1>0)
				{
					$sql = "SELECT * FROM puestos WHERE id = $puesto1";
					$p1 = mysqli_query($link, $sql);
					$result = mysqli_fetch_assoc($p1);
					$puesto1 = $result["nombre"];
				}
				
				if($puesto2>0)
				{
					$sql = "SELECT * FROM puestos WHERE id = $puesto2";
				$p1 = mysqli_query($link, $sql);
				$result = mysqli_fetch_assoc($p1);
				$puesto2 = $result["nombre"];
				}
				if($row["horas2"] == 0)
				{
					$horas2 = "-----";
				}else
				{
					$horas2=$row["horas2"];
				}
				if($row["horas1"] == 0)
				{
					$horas1 = "-----";
				}else
				{
					$horas1=$row["horas1"];
				}
				$fecha = $row["fecha"];
				echo('<tr>
				<td>'.$datos_operador['nombre'].'</td>
				<td>'.$ausente.'</td>
				<td>'.$row['causa'].'</td>
				<td>'.$puesto1.'</td>
				<td>'.$horas1.'</td>
				<td>'.$puesto2.'</td>
				<td>'.$horas2.'</td>
				<td>'.$fecha.'</td>
				<td><form method="POST"><input type="hidden" name="borrar_ficha" value="'.$row["id"].'"><button><i class="fas fa-trash"></i></button></form></td>
				<td><button><i class="fas fa-pen"></i></button></td>
				</tr>');
			}
			echo(' 
			  </tbody>
		  </table>
		
                            </div>
                          </div>
                        </div>');
		}
	}
		?>

</div>
</div>
</div>
	  </div>
      </div>
	</div>
	
    <div class="modal modal-blur fade" id="modal-report" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
		<form action="" method="POST">
          <div class="modal-header">
            <h5 class="modal-title">Reportar nueva irregularidad</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="mb-3">
              <label class="form-label">Operario Afectado</label>
              <select name="operario_inc" required id="" class="form-select">
			  <?php
			  $sql = "SELECT * FROM personal";
			  if($do = mysqli_query($link, $sql)){
				  echo('<option value="" selected>Seleccione</option>');
			 while($row = mysqli_fetch_assoc($do))
			 {
				 echo('<option value='.$row["id"].'>'.$row["num_operario"].' - '.$row['nombre'].'</option>');
			 } }
			  ?>
			  </select>
            </div>
            <label class="form-label">Tipo de irregularidad</label>
            <div class="form-selectgroup-boxes row mb-3">
              <div class="col-lg-6">
                <label class="form-selectgroup-item">
                  <input type="radio" name="accidente" value="1" class="form-selectgroup-input" checked>
                  <span class="form-selectgroup-label d-flex align-items-center p-3">
                    <span class="me-3">
                      <span class="form-selectgroup-check"></span>
                    </span>
                    <span class="form-selectgroup-label-content">
                      <span class="form-selectgroup-title strong mb-1">Accidente</span>
                      <span class="d-block text-muted">Un operario ha sufrido un accidente.</span>
                    </span>
                  </span>
                </label>
              </div>
              <div class="col-lg-6">
                <label class="form-selectgroup-item">
                  <input type="radio" name="accidente" value="2" class="form-selectgroup-input">
                  <span class="form-selectgroup-label d-flex align-items-center p-3">
                    <span class="me-3">
                      <span class="form-selectgroup-check"></span>
                    </span>
                    <span class="form-selectgroup-label-content">
                      <span class="form-selectgroup-title strong mb-1">Reporte</span>
                      <span class="d-block text-muted">Un operario ha hecho algo que no deberia...</span>
                    </span>
                  </span>
                </label>
              </div>
			</div>
			
            <div class="row">
              <div class="col-lg-8">
                <div class="mb-3">
                  <label class="form-label">Escribe la causa</label>
                  <div class="input-group input-group-flat">
                    <span class="input-group-text">
                    </span>
                    <input type="text" required class="form-control ps-0" name="causa_inc"  value="" placeholder="Escribe aqui..." autocomplete="off">
                  </div>
                </div>
              </div>
              <div class="col-lg-4">
                <div class="mb-3">
                  <label class="form-label">Puesto</label>
                  <select class="form-select" name="puesto_inc" required>
					<?php
					$sql = "SELECT * FROM puestos";
					$do = mysqli_query($link, $sql);
					echo('<option value="" selected>Selccione</option>');
					while($curro = mysqli_fetch_assoc($do))
					{
						
						echo('<option value="'.$curro["id"].'">'.$curro["nombre"].'</option>');
					}
					?>
                  </select>
                </div>
              </div>
            </div><?php
		  if(isset($_SESSION["turno"]))
		  {
		  $turno = $_SESSION["turno"];
		  $sql = "SELECT * FROM reportes WHERE turno = $turno";
		  $do = mysqli_query($link ,$sql);
		  $sql = "SELECT * FROM incidencias WHERE turno = $turno";
		  $do2 = mysqli_query($link ,$sql);
		  if($do->num_rows > 0 || $do2->num_rows > 0)
		  {
			echo('<hr>
			<table>
			<thead>
				<tr>
				  <th>Irregularidades actuales</th>
				</tr>
			</thead>
				<tbody>
				
				');
				$sql = "SELECT * FROM reportes WHERE turno = $turno";
				$do = mysqli_query($link, $sql);
				while($row = mysqli_fetch_assoc($do))
				{
					echo('<tr><td><a href="index.php?ereporte='.$row["id"].'">Eliminar</a></td><td>'.$row["operario"].'</td><td>Reporte: '.$row["reporte"].'</td></tr><tr><td></td><td></td></tr>');
				}
				$sql = "SELECT * FROM incidencias WHERE turno = $turno";
				$do = mysqli_query($link, $sql);
				while($row = mysqli_fetch_assoc($do))
				{
					echo('<tr><td><a href="index.php?eincidencia='.$row["id"].'">Eliminar</a></td><td>'.$row["operario"].'</td><td>Incidencia: '.$row["incidencia"].'</td></tr><tr><td></td><td></td></tr>');
				}
		   echo('
		   </tbody>
		</table>'); 
		  }
		}
		  ?>
          </div>
          
          <div class="modal-footer">
            <a href="#" class="btn btn-link link-secondary" data-bs-dismiss="modal">
              Cancelar
            </a>
            <button type="submit" class="btn btn-primary ms-auto">
              <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="12" y1="5" x2="12" y2="19" /><line x1="5" y1="12" x2="19" y2="12" /></svg>
              Crear nueva irregularidad
            </button>
          </div>
		  </form>
        </div>
      </div>
    </div>
	<div class="modal modal-blur fade" id="hacer-cambio" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
		<form action="" method="post">
          <div class="modal-header">
            <h5 class="modal-title">Reportar cambio</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="row">
              <div class="col-lg-12">
                <div class="mb-3">
                  <label class="form-label">Cambio en la planificación</label>
                  <div class="input-group input-group-flat">
                    <span class="input-group-text">
                    </span>
                    <input type="text" name="input_cambio" required class="form-control ps-0"  value="" placeholder="Escribe aqui..." autocomplete="off">
                  </div>
                </div><?php
		  if(isset($_SESSION["turno"]))
		  {
		  $turno = $_SESSION["turno"];
		  $sql = "SELECT * FROM cambios WHERE turno = $turno";
		  $do = mysqli_query($link ,$sql);
		  if($do->num_rows > 0)
		  {
			echo('<hr>
			<table>
			<thead>
				<tr>
				  <th>Cambios Actuales</th>
				</tr>
			</thead>
				<tbody>
				
				');
				$sql = "SELECT * FROM cambios WHERE turno = $turno";
				$do = mysqli_query($link, $sql);
				while($row = mysqli_fetch_assoc($do))
				{
					echo('<tr><td><a href="index.php?ecambio='.$row["id"].'">Eliminar</a></td><td>'.$row["cambio"].'</td></tr><tr><td></td><td></td></tr>');
				}
		   echo('
		   </tbody>
		</table>'); 
		  }
		}
		  ?>
              </div>
            </div>
          </div>
          
          <div class="modal-footer">
            <a href="#" class="btn btn-link link-secondary" data-bs-dismiss="modal">
              Cancelar
            </a>
            <button type="submit" class="btn btn-primary ms-auto">
              <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="12" y1="5" x2="12" y2="19" /><line x1="5" y1="12" x2="19" y2="12" /></svg>
              Reportar Cambio
            </button>
			
          </div>
		  
		  </form>
		  
        </div>
      </div>
	</div>
	<div class="modal modal-blur fade" id="auditar" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
		<form action="" method="post">
          <div class="modal-header">
            <h5 class="modal-title">Auditar</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
		  <div class="mb-3">
              <label class="form-label">Operario Auditado</label>
              <select name="operario_audi" required id="" class="form-select">
			  <?php
			  $sql = "SELECT * FROM personal";
			  if($do = mysqli_query($link, $sql)){
				  echo('<option value="" selected>Seleccione</option>');
			 while($row = mysqli_fetch_assoc($do))
			 {
				 echo('<option value='.$row["id"].'>'.$row["num_operario"].' - '.$row['nombre'].'</option>');
			 } }
			  ?>
			  </select>
			</div>
			<div class="col-md-6 col-lg-4">
			<div class="form-group">
                        <div class="form-label">Seleccione las siguientes casillas</div>
                        <div class="custom-controls-stacked"><hr>
                          <label class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" name="check1" value="option1" checked>
                            <span class="custom-control-label">Seguridad</span>
                          </label><br><hr>
                          <label class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" name="check2" value="option2">
                            <span class="custom-control-label">Medioambiente</span>
						  </label><br><hr>
						  <label class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" name="check3" value="option2">
                            <span class="custom-control-label">Calidad</span>
						  </label><br><hr>
						  <label class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" name="check4" value="option2">
                            <span class="custom-control-label">Cumplimiento EPIS</span>
                          </label>
                        </div>
					  </div>
			</div>
			<hr>
            <div class="row">
              <div class="col-lg-12">
			  <div class="mb-3">
                  <label class="form-label">Propuestas</label>
                  <div class="input-group input-group-flat">
                    <span class="input-group-text">
                    </span>
                    <input type="text" name="audi_propuesta" class="form-control ps-0"  value="" placeholder="Escribe aqui..." autocomplete="off">
                  </div>
                </div><div class="mb-3">
                  <label class="form-label">Acciones</label>
                  <div class="input-group input-group-flat">
                    <span class="input-group-text">
                    </span>
                    <input type="text" name="audi_accion" class="form-control ps-0"  value="" placeholder="Escribe aqui..." autocomplete="off">
                  </div>
				</div>
              </div>
            </div><?php
		  if(isset($_SESSION["turno"]))
		  {
		  $turno = $_SESSION["turno"];
		  $sql = "SELECT * FROM auditorias WHERE turno = $turno";
		  $do = mysqli_query($link ,$sql);
		  if($do->num_rows > 0 || $do2->num_rows > 0)
		  {
			echo('<hr>
			<table>
			<thead>
				<tr>
				  <th>Auditorias actuales</th>
				</tr>
			</thead>
				<tbody>
				
				');
				$sql = "SELECT * FROM auditorias WHERE turno = $turno";
				$do = mysqli_query($link, $sql);
				
				while($row = mysqli_fetch_assoc($do))
				{
					$num_operario = $row["num_operario"];
					$sql = "SELECT * FROM personal WHERE id = '$num_operario'";
				$do2 = mysqli_query($link, $sql);
				$info = mysqli_fetch_assoc($do2);
				$nombre_operario = $info["nombre"];
					echo('<tr><td><a href="index.php?eauditoria='.$row["id"].'">Eliminar</a></td><td>'.$nombre_operario.'</td><td> '.$row["fecha"].' '.$row["hora"].'</td></tr><tr><td></td><td></td></tr>');
				}
		   echo('
		   </tbody>
		</table>'); 
		  }
		}
		  ?>
          </div>
          
          <div class="modal-footer">
            <a href="#" class="btn btn-link link-secondary" data-bs-dismiss="modal">
              Cancelar
            </a>
            <button type="submit" class="btn btn-primary ms-auto">
              <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="12" y1="5" x2="12" y2="19" /><line x1="5" y1="12" x2="19" y2="12" /></svg>
              Auditar
            </button>
          </div>
		  </form>
        </div>
      </div>
	</div>

	
	<!-- Libs JS -->

    <script src="./dist/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="./dist/libs/jquery/dist/jquery.slim.min.js"></script>
    <script src="./dist/libs/apexcharts/dist/apexcharts.min.js"></script>
    <script src="./dist/libs/jqvmap/dist/jquery.vmap.min.js"></script>
    <script src="./dist/libs/jqvmap/dist/maps/jquery.vmap.world.js"></script>
	<!-- Tabler Core -->
	<script type="text/javascript" src="./dist/js/jquery-3.5.1.js"></script>
    <script type="text/javascript" src="./dist/js/jquery.dataTables.js"></script>
	<script src="./dist/js/tabler.min.js"></script>
	<script>
$(document).ready( function () {
    $('#table_id').DataTable();
} );
</script>
    <script>

      // @formatter:off
      document.addEventListener("DOMContentLoaded", function () {
      	window.ApexCharts && (new ApexCharts(document.getElementById('chart-incidencias-bg'), {
      		chart: {
      			type: "area",
      			fontFamily: 'inherit',
      			height: 40.0,
      			sparkline: {
      				enabled: true
      			},
      			animations: {
      				enabled: false
      			},
      		},
      		dataLabels: {
      			enabled: false,
      		},
      		fill: {
      			opacity: .16,
      			type: 'solid'
      		},
      		stroke: {
      			width: 2,
      			lineCap: "round",
      			curve: "smooth",
      		},
      		series: [{
            name: "Incidencias",
            
      			data: [<?php
            $fecha_ahora = date('Y-m-d', time());
            
              for($i=1;$i<=$dias;$i++)
              {
                $resta = $i - $dias;
                $fecha_variable = date("Y-m-d", strtotime($resta." day"));
                if($resta == 0)
                {
                  $fecha_variable = $fecha_ahora;
                }
                $sql = "SELECT * FROM incidencias WHERE fecha = '$fecha_variable' AND usuario = '$iduser'";
                if($fo = mysqli_query($link, $sql))
                {
                  if($resta != 0)
                  {
                    echo " '".$fo->num_rows."',";
                  }else
                  {
                 echo " '".$fo->num_rows."'";
                  }
                }
              }
              ?>]
      		}],
      		grid: {
      			strokeDashArray: 4,
      		},
      		xaxis: {
      			labels: {
      				padding: 0
      			},
      			tooltip: {
      				enabled: false
      			},
      			axisBorder: {
      				show: false,
      			},
      			type: 'text',
      		},
      		yaxis: {
      			labels: {
      				padding: 4
      			},
      		},
      		labels: [
            <?php
            $fecha_ahora = date('Y-m-d', time());
              for($i=1;$i<=$dias;$i++)
              {
                $resta = $i - $dias;
                $fecha_variable = date("Y-m-d", strtotime($resta." day"));
                if($resta == 0)
                {
                  $fecha_variable = $fecha_ahora;
                }
                if($i == $dias)
                {
                  echo(" '".$fecha_variable."'");
                }else
                {
                  echo(" '".$fecha_variable."',");
                }
              }
              ?>
      		],
      		colors: ["#FA3005"],
      		legend: {
      			show: false,
      		},
      	})).render();
      });
      document.addEventListener("DOMContentLoaded", function () {
      	window.ApexCharts && (new ApexCharts(document.getElementById('chart-revenue-bg'), {
      		chart: {
      			type: "area",
      			fontFamily: 'inherit',
      			height: 40.0,
      			sparkline: {
      				enabled: true
      			},
      			animations: {
      				enabled: false
      			},
      		},
      		dataLabels: {
      			enabled: false,
      		},
      		fill: {
      			opacity: .16,
      			type: 'solid'
      		},
      		stroke: {
      			width: 2,
      			lineCap: "round",
      			curve: "smooth",
      		},
      		series: [{
      			name: "Reportes",
      			data: [<?php
            $fecha_ahora = date('Y-m-d', time());
            
              for($i=1;$i<=$dias;$i++)
              {
                $resta = $i - $dias;
                $fecha_variable = date("Y-m-d", strtotime($resta." day"));
                if($resta == 0)
                {
                  $fecha_variable = $fecha_ahora;
                }
                $sql = "SELECT * FROM reportes WHERE fecha = '$fecha_variable' AND usuario = '$iduser'";
                if($fo = mysqli_query($link, $sql))
                {
                  if($resta != 0)
                  {
                    echo " '".$fo->num_rows."',";
                  }else
                  {
                 echo " '".$fo->num_rows."'";
                  }
                }
              }
              ?>]
      		}],
      		grid: {
      			strokeDashArray: 4,
      		},
      		xaxis: {
      			labels: {
      				padding: 0
      			},
      			tooltip: {
      				enabled: false
      			},
      			axisBorder: {
      				show: false,
      			},
      			type: 'text',
      		},
      		yaxis: {
      			labels: {
      				padding: 4
      			},
      		},
      		labels: [
      			<?php
            $fecha_ahora = date('Y-m-d', time());
              for($i=1;$i<=$dias;$i++)
              {
                $resta = $i - $dias;
                $fecha_variable = date("Y-m-d", strtotime($resta." day"));
                if($resta == 0)
                {
                  $fecha_variable = $fecha_ahora;
                }
                if($i == $dias)
                {
                  echo(" '".$fecha_variable."'");
                }else
                {
                  echo(" '".$fecha_variable."',");
                }
              }
              ?>
      		],
      		colors: ["#FFC300"],
      		legend: {
      			show: false,
      		},
      	})).render();
      });
      // @formatter:on
      // @formatter:off
      document.addEventListener("DOMContentLoaded", function () {
      	window.ApexCharts && (new ApexCharts(document.getElementById('chart-new-clients'), {
      		chart: {
      			type: "area",
      			fontFamily: 'inherit',
      			height: 40.0,
      			sparkline: {
      				enabled: true
      			},
      			animations: {
      				enabled: false
      			},
      		},
      		dataLabels: {
      			enabled: false,
      		},
      		fill: {
      			opacity: .16,
      			type: 'solid'
      		},
      		stroke: {
      			width: 2,
      			lineCap: "round",
      			curve: "smooth",
      		},
      		series: [{
      			name: "Auditorias",
      			data: [<?php
            $fecha_ahora = date('Y-m-d', time());
            
              for($i=1;$i<=$dias;$i++)
              {
                $resta = $i - $dias;
                $fecha_variable = date("Y-m-d", strtotime($resta." day"));
                if($resta == 0)
                {
                  $fecha_variable = $fecha_ahora;
                }
                $sql = "SELECT * FROM auditorias WHERE fecha = '$fecha_variable' AND auditor = '$iduser'";
                if($fo = mysqli_query($link, $sql))
                {
                  if($resta != 0)
                  {
                    echo " '".$fo->num_rows."',";
                  }else
                  {
                 echo " '".$fo->num_rows."'";
                  }
                }
              }
              ?>]
      		}],
      		grid: {
      			strokeDashArray: 4,
      		},
      		xaxis: {
      			labels: {
      				padding: 0
      			},
      			tooltip: {
      				enabled: false
      			},
      			axisBorder: {
      				show: false,
      			},
      			type: 'text',
      		},
      		yaxis: {
      			labels: {
      				padding: 4
      			},
      		},
      		labels: [
      			<?php
            $fecha_ahora = date('Y-m-d', time());
              for($i=1;$i<=$dias;$i++)
              {
                $resta = $i - $dias;
                $fecha_variable = date("Y-m-d", strtotime($resta." day"));
                if($resta == 0)
                {
                  $fecha_variable = $fecha_ahora;
                }
                if($i == $dias)
                {
                  echo(" '".$fecha_variable."'");
                }else
                {
                  echo(" '".$fecha_variable."',");
                }
              }
              ?>
      		],
      		colors: ["#1FCA07"],
      		legend: {
      			show: false,
      		},
      	})).render();
      });
      // @formatter:on
      // @formatter:off
      document.addEventListener("DOMContentLoaded", function () {
      	window.ApexCharts && (new ApexCharts(document.getElementById('chart-active-users'), {
      		chart: {
      			type: "area",
      			fontFamily: 'inherit',
      			height: 40.0,
      			sparkline: {
      				enabled: true
      			},
      			animations: {
      				enabled: false
      			},
      		},
      		dataLabels: {
      			enabled: false,
      		},
      		fill: {
      			opacity: .16,
      			type: 'solid'
      		},
      		stroke: {
      			width: 2,
      			lineCap: "round",
      			curve: "smooth",
      		},
      		series: [{
      			name: "Ausencias",
      			data: [<?php
            $fecha_ahora = date('Y-m-d', time());
            
              for($i=1;$i<=$dias;$i++)
              {
                $resta = $i - $dias;
                $fecha_variable = date("Y-m-d", strtotime($resta." day"));
                if($resta == 0)
                {
                  $fecha_variable = $fecha_ahora;
                }
                $sql = "SELECT * FROM ausencias_rot WHERE fecha = '$fecha_variable' AND ausente = 'true' AND usuario = '$iduser'";
                if($fo = mysqli_query($link, $sql))
                {
                  if($resta != 0)
                  {
                    echo " '".$fo->num_rows."',";
                  }else
                  {
                 echo " '".$fo->num_rows."'";
                  }
                }
              }
              ?>]
      		}],
      		grid: {
      			strokeDashArray: 4,
      		},
      		xaxis: {
      			labels: {
      				padding: 0
      			},
      			tooltip: {
      				enabled: false
      			},
      			axisBorder: {
      				show: false,
      			},
      			type: 'text',
      		},
      		yaxis: {
      			labels: {
      				padding: 4
      			},
      		},
      		labels: [
      			<?php
            $fecha_ahora = date('Y-m-d', time());
              for($i=1;$i<=$dias;$i++)
              {
                $resta = $i - $dias;
                $fecha_variable = date("Y-m-d", strtotime($resta." day"));
                if($resta == 0)
                {
                  $fecha_variable = $fecha_ahora;
                }
                if($i == $dias)
                {
                  echo(" '".$fecha_variable."'");
                }else
                {
                  echo(" '".$fecha_variable."',");
                }
              }
              ?>
      		],
      		colors: ["#206bc4"],
      		legend: {
      			show: false,
      		},
      	})).render();
      });
      // @formatter:on
    </script>
    <script>
      // @formatter:off
      document.addEventListener("DOMContentLoaded", function () {
      	window.ApexCharts && (new ApexCharts(document.getElementById('chart-mentions'), {
      		chart: {
      			type: "bar",
      			fontFamily: 'inherit',
      			height: 240,
      			parentHeightOffset: 0,
      			toolbar: {
      				show: false,
      			},
      			animations: {
      				enabled: false
      			},
      			stacked: true,
      		},
      		plotOptions: {
      			bar: {
      				columnWidth: '50%',
      			}
      		},
      		dataLabels: {
      			enabled: false,
      		},
      		fill: {
      			opacity: 1,
      		},
      		series: [{
      			name: "Web",
      			data: [1, 0, 0, 0, 0, 1, 1, 0, 0, 0, 2, 12, 5, 8, 22, 6, 8, 6, 4, 1, 8, 24, 29, 51, 40, 47, 23, 26, 50, 26, 41, 22, 46, 47, 81, 46, 6]
      		},{
      			name: "Social",
      			data: [2, 5, 4, 3, 3, 1, 4, 7, 5, 1, 2, 5, 3, 2, 6, 7, 7, 1, 5, 5, 2, 12, 4, 6, 18, 3, 5, 2, 13, 15, 20, 47, 18, 15, 11, 10, 0]
      		},{
      			name: "Other",
      			data: [2, 9, 1, 7, 8, 3, 6, 5, 5, 4, 6, 4, 1, 9, 3, 6, 7, 5, 2, 8, 4, 9, 1, 2, 6, 7, 5, 1, 8, 3, 2, 3, 4, 9, 7, 1, 6]
      		}],
      		grid: {
      			padding: {
      				top: -20,
      				right: 0,
      				left: -4,
      				bottom: -4
      			},
      			strokeDashArray: 4,
      			xaxis: {
      				lines: {
      					show: true
      				}
      			},
      		},
      		xaxis: {
      			labels: {
      				padding: 0
      			},
      			tooltip: {
      				enabled: false
      			},
      			axisBorder: {
      				show: false,
      			},
      			type: 'datetime',
      		},
      		yaxis: {
      			labels: {
      				padding: 4
      			},
      		},
      		labels: [
      			'2020-06-20', '2020-06-21', '2020-06-22', '2020-06-23', '2020-06-24', '2020-06-25', '2020-06-26', '2020-06-27', '2020-06-28', '2020-06-29', '2020-06-30', '2020-07-01', '2020-07-02', '2020-07-03', '2020-07-04', '2020-07-05', '2020-07-06', '2020-07-07', '2020-07-08', '2020-07-09', '2020-07-10', '2020-07-11', '2020-07-12', '2020-07-13', '2020-07-14', '2020-07-15', '2020-07-16', '2020-07-17', '2020-07-18', '2020-07-19', '2020-07-20', '2020-07-21', '2020-07-22', '2020-07-23', '2020-07-24', '2020-07-25', '2020-07-26'
      		],
      		colors: ["#206bc4", "#79a6dc", "#bfe399"],
      		legend: {
      			show: true,
      			position: 'bottom',
      			height: 32,
      			offsetY: 8,
      			markers: {
      				width: 8,
      				height: 8,
      				radius: 100,
      			},
      			itemMargin: {
      				horizontal: 8,
      			},
      		},
      	})).render();
      });
      // @formatter:on
    </script>
    <script>
      // @formatter:on
      document.addEventListener("DOMContentLoaded", function() {
      	$('#map-world').vectorMap({
      		map: 'world_en',
      		backgroundColor: 'transparent',
      		color: 'rgba(120, 130, 140, .1)',
      		borderColor: 'transparent',
      		scaleColors: ["#d2e1f3", "#206bc4"],
      		normalizeFunction: 'polynomial',
      		values: (chart_data = {"af":16, "al":11, "dz":158, "ao":85, "ag":1, "ar":351, "am":8, "au":1219, "at":366, "az":52, "bs":7, "bh":21, "bd":105, "bb":3, "by":52, "be":461, "bz":1, "bj":6, "bt":1, "bo":19, "ba":16, "bw":12, "br":2023, "bn":11, "bg":44, "bf":8, "bi":1, "kh":11, "cm":21, "ca":1563, "cv":1, "cf":2, "td":7, "cl":199, "cn":5745, "co":283, "km":0, "cd":12, "cg":11, "cr":35, "ci":22, "hr":59, "cy":22, "cz":195, "dk":304, "dj":1, "dm":0, "do":50, "ec":61, "eg":216, "sv":21, "gq":14, "er":2, "ee":19, "et":30, "fj":3, "fi":231, "fr":2555, "ga":12, "gm":1, "ge":11, "de":3305, "gh":18, "gr":305, "gd":0, "gt":40, "gn":4, "gw":0, "gy":2, "ht":6, "hn":15, "hk":226, "hu":132, "is":12, "in":1430, "id":695, "ir":337, "iq":84, "ie":204, "il":201, "it":2036, "jm":13, "jp":5390, "jo":27, "kz":129, "ke":32, "ki":0, "kr":986, "undefined":5, "kw":117, "kg":4, "la":6, "lv":23, "lb":39, "ls":1, "lr":0, "ly":77, "lt":35, "lu":52, "mk":9, "mg":8, "mw":5, "my":218, "mv":1, "ml":9, "mt":7, "mr":3, "mu":9, "mx":1004, "md":5, "mn":5, "me":3, "ma":91, "mz":10, "mm":35, "na":11, "np":15, "nl":770, "nz":138, "ni":6, "ne":5, "ng":206, "no":413, "om":53, "pk":174, "pa":27, "pg":8, "py":17, "pe":153, "ph":189, "pl":438, "pt":223, "qa":126, "ro":158, "ru":1476, "rw":5, "ws":0, "st":0, "sa":434, "sn":12, "rs":38, "sc":0, "sl":1, "sg":217, "sk":86, "si":46, "sb":0, "za":354, "es":1374, "lk":48, "kn":0, "lc":1, "vc":0, "sd":65, "sr":3, "sz":3, "se":444, "ch":522, "sy":59, "tw":426, "tj":5, "tz":22, "th":312, "tl":0, "tg":3, "to":0, "tt":21, "tn":43, "tr":729, "tm":0, "ug":17, "ua":136, "ae":239, "gb":2258, "us":4624, "uy":40, "uz":37, "vu":0, "ve":285, "vn":101, "ye":30, "zm":15, "zw":5}),
      		onLabelShow: function (event, label, code) {
      			if (chart_data[code] > 0) {
      				label.append(': <strong>' + chart_data[code] + '</strong>');
      			}
      		},
      	});
      });
      // @formatter:off
    </script>
    <script>
      // @formatter:off
      document.addEventListener("DOMContentLoaded", function () {
      	window.ApexCharts && (new ApexCharts(document.getElementById('sparkline-activity'), {
      		chart: {
      			type: "radialBar",
      			fontFamily: 'inherit',
      			height: 40,
      			width: 40,
      			animations: {
      				enabled: false
      			},
      			sparkline: {
      				enabled: true
      			},
      		},
      		tooltip: {
      			enabled: false,
      		},
      		plotOptions: {
      			radialBar: {
      				hollow: {
      					margin: 0,
      					size: '75%'
      				},
      				track: {
      					margin: 0
      				},
      				dataLabels: {
      					show: false
      				}
      			}
      		},
      		colors: ["#206bc4"],
      		series: [35],
      	})).render();
      });
      // @formatter:on
    </script>
    <script>
      // @formatter:off
      document.addEventListener("DOMContentLoaded", function () {
      	window.ApexCharts && (new ApexCharts(document.getElementById('chart-development-activity'), {
      		chart: {
      			type: "area",
      			fontFamily: 'inherit',
      			height: 192,
      			sparkline: {
      				enabled: true
      			},
      			animations: {
      				enabled: false
      			},
      		},
      		dataLabels: {
      			enabled: false,
      		},
      		fill: {
      			opacity: .16,
      			type: 'solid'
      		},
      		stroke: {
      			width: 2,
      			lineCap: "round",
      			curve: "smooth",
      		},
      		series: [{
      			name: "Purchases",
      			data: [3, 5, 4, 6, 7, 5, 6, 8, 24, 7, 12, 5, 6, 3, 8, 4, 14, 30, 17, 19, 15, 14, 25, 32, 40, 55, 60, 48, 52, 70]
      		}],
      		grid: {
      			strokeDashArray: 4,
      		},
      		xaxis: {
      			labels: {
      				padding: 0
      			},
      			tooltip: {
      				enabled: false
      			},
      			axisBorder: {
      				show: false,
      			},
      			type: 'datetime',
      		},
      		yaxis: {
      			labels: {
      				padding: 4
      			},
      		},
      		labels: [
      			'2020-06-20', '2020-06-21', '2020-06-22', '2020-06-23', '2020-06-24', '2020-06-25', '2020-06-26', '2020-06-27', '2020-06-28', '2020-06-29', '2020-06-30', '2020-07-01', '2020-07-02', '2020-07-03', '2020-07-04', '2020-07-05', '2020-07-06', '2020-07-07', '2020-07-08', '2020-07-09', '2020-07-10', '2020-07-11', '2020-07-12', '2020-07-13', '2020-07-14', '2020-07-15', '2020-07-16', '2020-07-17', '2020-07-18', '2020-07-19'
      		],
      		colors: ["#206bc4"],
      		legend: {
      			show: false,
      		},
      		point: {
      			show: false
      		},
      	})).render();
      });
      // @formatter:on
    </script>
    <script>
      // @formatter:off
      document.addEventListener("DOMContentLoaded", function () {
      	window.ApexCharts && (new ApexCharts(document.getElementById('sparkline-bounce-rate-1'), {
      		chart: {
      			type: "line",
      			fontFamily: 'inherit',
      			height: 24,
      			animations: {
      				enabled: false
      			},
      			sparkline: {
      				enabled: true
      			},
      		},
      		tooltip: {
      			enabled: false,
      		},
      		stroke: {
      			width: 2,
      			lineCap: "round",
      		},
      		series: [{
      			color: "#206bc4",
      			data: [17, 24, 20, 10, 5, 1, 4, 18, 13]
      		}],
      	})).render();
      });
      // @formatter:on
    </script>
    <script>
      // @formatter:off
      document.addEventListener("DOMContentLoaded", function () {
      	window.ApexCharts && (new ApexCharts(document.getElementById('sparkline-bounce-rate-2'), {
      		chart: {
      			type: "line",
      			fontFamily: 'inherit',
      			height: 24,
      			animations: {
      				enabled: false
      			},
      			sparkline: {
      				enabled: true
      			},
      		},
      		tooltip: {
      			enabled: false,
      		},
      		stroke: {
      			width: 2,
      			lineCap: "round",
      		},
      		series: [{
      			color: "#206bc4",
      			data: [13, 11, 19, 22, 12, 7, 14, 3, 21]
      		}],
      	})).render();
      });
      // @formatter:on
    </script>
    <script>
      // @formatter:off
      document.addEventListener("DOMContentLoaded", function () {
      	window.ApexCharts && (new ApexCharts(document.getElementById('sparkline-bounce-rate-3'), {
      		chart: {
      			type: "line",
      			fontFamily: 'inherit',
      			height: 24,
      			animations: {
      				enabled: false
      			},
      			sparkline: {
      				enabled: true
      			},
      		},
      		tooltip: {
      			enabled: false,
      		},
      		stroke: {
      			width: 2,
      			lineCap: "round",
      		},
      		series: [{
      			color: "#206bc4",
      			data: [10, 13, 10, 4, 17, 3, 23, 22, 19]
      		}],
      	})).render();
      });
      // @formatter:on
    </script>
    <script>
      // @formatter:off
      document.addEventListener("DOMContentLoaded", function () {
      	window.ApexCharts && (new ApexCharts(document.getElementById('sparkline-bounce-rate-4'), {
      		chart: {
      			type: "line",
      			fontFamily: 'inherit',
      			height: 24,
      			animations: {
      				enabled: false
      			},
      			sparkline: {
      				enabled: true
      			},
      		},
      		tooltip: {
      			enabled: false,
      		},
      		stroke: {
      			width: 2,
      			lineCap: "round",
      		},
      		series: [{
      			color: "#206bc4",
      			data: [6, 15, 13, 13, 5, 7, 17, 20, 19]
      		}],
      	})).render();
      });
      // @formatter:on
    </script>
    <script>
      // @formatter:off
      document.addEventListener("DOMContentLoaded", function () {
      	window.ApexCharts && (new ApexCharts(document.getElementById('sparkline-bounce-rate-5'), {
      		chart: {
      			type: "line",
      			fontFamily: 'inherit',
      			height: 24,
      			animations: {
      				enabled: false
      			},
      			sparkline: {
      				enabled: true
      			},
      		},
      		tooltip: {
      			enabled: false,
      		},
      		stroke: {
      			width: 2,
      			lineCap: "round",
      		},
      		series: [{
      			color: "#206bc4",
      			data: [2, 11, 15, 14, 21, 20, 8, 23, 18, 14]
      		}],
      	})).render();
      });
      // @formatter:on
    </script>
    <script>
      // @formatter:off
      document.addEventListener("DOMContentLoaded", function () {
      	window.ApexCharts && (new ApexCharts(document.getElementById('sparkline-bounce-rate-6'), {
      		chart: {
      			type: "line",
      			fontFamily: 'inherit',
      			height: 24,
      			animations: {
      				enabled: false
      			},
      			sparkline: {
      				enabled: true
      			},
      		},
      		tooltip: {
      			enabled: false,
      		},
      		stroke: {
      			width: 2,
      			lineCap: "round",
      		},
      		series: [{
      			color: "#206bc4",
      			data: [22, 12, 7, 14, 3, 21, 8, 23, 18, 14]
      		}],
      	})).render();
      });
      // @formatter:on
	</script>
	<script>
		if (window.history.replaceState) { // verificamos disponibilidad
    window.history.replaceState(null, null, window.location.href);
}
	</script>
	<footer class="footer">
        <div class="container">
          <div class="row align-items-center flex-row-reverse">
            <div class="col-auto ml-lg-auto">
              <div class="row align-items-center">
                <div class="col-auto">
                  </ul>
                </div>
                <div class="col-auto">
                  <a href="https://github.com/abrahampo1/control" class="btn btn-outline-primary btn-sm">Código Fuente</a>
                </div>
              </div>
            </div>
            <div class="col-12 col-lg-auto mt-3 mt-lg-0 text-center">
              Copyright © 2021 <a href="mailto:abraham@cpsoftware.es">CPSoftware</a>. </a>El programa ha sido creado por Abraham Leiro Fernandez y Rubén Bendaña Couse.
            </div>
          </div>
        </div>
      </footer>
  </body>
</html>