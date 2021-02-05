<?php
include('database.php');
require('protect.php');
if(!isset($_SESSION["turno"]))
{
	header('location: index.php');
}
            $fecha_ahora = date('Y-m-d', time());
            $dias = 1;
            $incidencias_totales = 0;
            $reportes_totales = 0;
            $auditorias_totales = 0;
			$ausencias_totales = 0;
			$turno = $_SESSION["turno"];
			$sql = "SELECT * FROM turnos WHERE id = $turno";
			$do = mysqli_query($link, $sql);
			$info_turno = mysqli_fetch_assoc($do);
			$sql = "SELECT * FROM incidencias WHERE turno = '$turno'";
			if($fo = mysqli_query($link, $sql))
			{
				$incidencias_totales += $fo->num_rows;
			}
			$sql = "SELECT * FROM reportes WHERE turno = '$turno'";
			if($fo = mysqli_query($link, $sql))
			{
				$reportes_totales += $fo->num_rows;
			}
			$sql = "SELECT * FROM auditorias WHERE turno = '$turno'";
			if($fo = mysqli_query($link, $sql))
			{
				$auditorias_totales += $fo->num_rows;
			}
			$sql = "SELECT * FROM ausencias_rot WHERE turno = '$turno' AND ausente='true'";
			if($fo = mysqli_query($link, $sql))
			{
				$ausencias_totales += $fo->num_rows;
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
<html lang="es">
  <head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge"/>
    <title>CPControl</title>
    <!-- CSS files -->
    <link href="./dist/libs/jqvmap/dist/jqvmap.min.css" rel="stylesheet"/>
    <link href="./dist/css/tabler.min.css" rel="stylesheet"/>
    <link href="./dist/css/tabler-flags.min.css" rel="stylesheet"/>
    <link href="./dist/css/tabler-payments.min.css" rel="stylesheet"/>
    <link href="./dist/css/tabler-vendors.min.css" rel="stylesheet"/>
    <link href="./dist/css/demo.min.css" rel="stylesheet"/>
  </head>
  <body class="antialiased">
    <div class="page">
      <div class="content">
        <div class="container-xl">
          <!-- Page title -->
            <div class="row align-items-center">
              <div class="col">
                <!-- Page pre-title -->
				<div class="d-print-none col-auto btn-list"><div class=""><button class="btn btn-primary d-none d-sm-inline-block" onclick="goBack()">Retroceder</button></div><div class=""><button class="btn btn-primary d-none d-sm-inline-block" onclick="window.print();return false;">Imprimir</button></div></div> <br>
				 <div class="page-pretitle">
				 
				 ILUNION Servicios Industriales<br>
Informe del <?php echo $info_turno["tipo"]?> generado el dia <?php echo $fecha_ahora?>
                </div>
                <h2 class="page-title">
				Informe de cambio de turno Operativa Benteler Valladares
                </h2>
              </div>
              <!-- Page title actions -->
              <div class="col-auto ms-auto">
			  <img src="dist/img/logos/logo.png" width="180" height="140" alt="Tabler" class="">
                
              </div>
            </div>
          <div class="row row-deck row-cards">
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
                    <div class="h1 mb-3 me-2">0</div>
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

			<div class="col-12">
                          <div class="card card-stacked">
                            <div class="card-body">
                              <h3 class="card-title">Cambios planificación</h3>
							  <?php
								$sql = "SELECT * FROM cambios WHERE turno = $turno";
								$do = mysqli_query($link, $sql);
								$num_cambios = 0;
								while($row = mysqli_fetch_assoc($do))
								{
									$num_cambios++;
									echo('<p>- '.$row["cambio"].'</p>');
								} 
								if($num_cambios == 0)
								{
									echo('<p>No hay ningún cambio</p>');
								}
							  ?>
                            </div>
                          </div>
                        </div><br>
						<div class="col-12">
						<div class="col-12 border-0">
						<div class="">
                          <div class="card">
                            <div class="card-body">
                              <h3 class="card-title">Asistencia y Rotación</h3>
							  <?php
		  $turno = $_SESSION["turno"];
		  $sql = "SELECT * FROM ausencias_rot WHERE turno = $turno AND ausente='false'";
		  $do = mysqli_query($link, $sql);
		if(isset($_SESSION["turno"])&&$do->num_rows != 0)
		{
			echo('
			<table id="table_id" class="display" width="100%" style="margin:0px">
			<thead>
				<tr>
					<th>Nombre</th>
					<th>Puesto 1</th>
					<th>Horas</th>
					<th>Puesto 2</th>
					<th>Horas</th>
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
			  $fecha = $row["fecha"];
			  echo('<tr>
			  <td>'.$datos_operador['nombre'].'</td>
			  <td>'.$puesto1.'</td>
			  <td>'.$row["horas1"].'</td>
			  <td>'.$puesto2.'</td>
			  <td>'.$row["horas2"].'</td>
			  </tr>');
		  }
		  echo('
			</tbody>
		</table>');
		}
		?>
                            </div>
                            <!-- Card footer -->
                            <div class="card-footer">
							  <h3>Ausencias en el turno</h3>
							  <?php
		  $turno = $_SESSION["turno"];
		  $sql = "SELECT * FROM ausencias_rot WHERE turno = $turno AND ausente = 'true'";
		  $do = mysqli_query($link, $sql);
		if(isset($_SESSION["turno"])&&$do->num_rows != 0)
		{
			echo('
			<table id="table_id" class="display" width="100%" style="margin:0px">
			<thead>
				<tr>
					<th>Nombre</th>
					<th>Causa</th>
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
			  $fecha = $row["fecha"];
			  echo('<tr>
			  <td>'.$datos_operador['nombre'].'</td>
			  <td>'.$row['causa'].'</td>
			  </tr>');
		  }
		  echo('
			</tbody>
		</table>');
		}
		?>
                            </div>
                          </div>
						</div><br>
						
						<div class="col-12">
                          <div class="card card-stacked">
                            <div class="card-body">
                              <h3 class="card-title">Reportes</h3>
                              <table border="0" width="100%" style="margin: 0px;">
								  <thead>
									  <tr>
										  <th>Nombre</th>
										  <th>Puesto</th>
										  <th>Reporte</th>
									  </tr>
								  </thead>
								  <tbody>
								  <?php
									 $sql="SELECT * FROM reportes WHERE turno = '$turno'";
									 $do = mysqli_query($link, $sql);
									 while($row = mysqli_fetch_assoc($do))
									 {
										 $puesto = $row["puesto"];
										 $sql = "SELECT * FROM puestos WHERE id = '$puesto'";
										 $do2 = mysqli_query($link, $sql);
										 $puesto = mysqli_fetch_assoc($do2);
										 echo('<tr>
										 <td>'.$row["operario"].'</td>
										 <td>'.$puesto["nombre"].'</td>
										 <td>'.$row["reporte"].'</td>
									 </tr>');
									 } 
									  ?>
								  </tbody>
							  </table>
                            </div>
                          </div>
						</div>
						<br>
						<div class="col-12">
                          <div class="card card-stacked">
                            <div class="card-body">
                              <h3 class="card-title">Accidentes e Incidencias detectadas</h3>
                              <table border="0" width="100%" style="margin: 0px;">
								  <thead>
									  <tr>
										  <th>Nombre</th>
										  <th>Puesto</th>
										  <th>Causa</th>
									  </tr>
								  </thead>
								  <tbody>
									  <?php
									 $sql="SELECT * FROM incidencias WHERE turno = '$turno'";
									 $do = mysqli_query($link, $sql);
									 while($row = mysqli_fetch_assoc($do))
									 {
										 $puesto = $row["puesto"];
										 $sql = "SELECT * FROM puestos WHERE id = '$puesto'";
										 $do2 = mysqli_query($link, $sql);
										 $puesto = mysqli_fetch_assoc($do2);
										 echo('<tr>
										 <td>'.$row["operario"].'</td>
										 <td>'.$puesto["nombre"].'</td>
										 <td>'.$row["incidencia"].'</td>
									 </tr>');
									 } 
									  ?>
								  </tbody>
							  </table>
                            </div>
                          </div>
						</div>
						<br>
						<div class="col-12">
                          <div class="card">
                            <div class="card-header">
                              <h3 class="card-title">Riesgos 5s e incumplimiento de epis</h3>
                            </div>
                            <div class="card-body">
                              <p>No ha habido ningun incumplimiento</p>
                            </div>
                          </div>
						</div>
						<br>
          </div>
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
    <script src="./dist/js/tabler.min.js"></script>
    <script>
      // @formatter:off
      
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
function goBack() {
  window.history.back();
}
</script>
  </body>
</html>