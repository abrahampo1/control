<?php
$ahora = getdate();
include("../../conectar.php");
if ($ahora["mday"] < 10) {
    $dia = "0" . $ahora["mday"];
} else {
    $dia = $ahora["mday"];
}
if ($ahora["mon"] < 10) {
    $mon = "0" . $ahora["mon"];
} else {
    $mon = $ahora["mon"];
}
$vit_real = $_GET["vit_real"];
$id = $_GET["id"];
$asociado_bool = $_GET["asoc"];
$viticultor = $_GET["viticultor"];
$peso = $_GET["peso"];
$c_cajas = $_GET["c_cajas"];
$bodega = $_GET["bodega"];
$consulta = "SELECT * FROM bodegas WHERE id =" . $bodega;
$resultado_temporal = mysqli_query($link, $consulta);
$dato_palet = mysqli_fetch_assoc($resultado_temporal);
$bodega = $dato_palet["nombre"];


?>

<head>
    <style>
        p.inline {
            display: inline-block;
        }

        span {
            font-size: 13px;
        }
    </style>
    <style type="text/css" media="print">
        @page {
            size: auto;
            /* auto is the initial value */
            margin: 0mm;
            /* this affects the margin in the printer settings */

        }
    </style>
</head>

<body onload="window.print()">
    <div style="margin-left: 5%">
        <?php
        include 'barcode128.php';
        $product_id = $vit_real;
        $rate = "1";
        $sel = "SELECT * FROM personas WHERE vit =".$vit_real;
        $c = mysqli_query($link, $sel);
        $datos = mysqli_fetch_assoc($c);
        if($datos["oculto"] == 0)
{
$nombre = $datos["nombre"].' '.$datos["apellidos"];
}else
{ 
$nombre = $datos["nombre"][0].' '.$datos["apellidos"][0];
}
        for ($i = 1; $i <= $rate; $i++) {
            $text=$id;
            echo '<!DOCTYPE html>
            <html>
            
            <head>
                <meta charset="utf-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
                <title>Blank Page - CPLogisics</title>
                <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
                <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i">
                <link rel="stylesheet" href="assets/fonts/fontawesome-all.min.css">
            </head>
            
            <body id="page-top">
                <div id="wrapper">
                    <div class="d-flex flex-column" id="content-wrapper">
                        <div id="content">
                            <div class="container-fluid">
                                <h3 class="text-dark mb-1" style="margin-top: 30px;margin-bottom: 53px;">CPLogistics</h3>
                                
<button class="btn btn-primary text-right btn-circle ml-1" onclick="goBack()"><i class="fas fa-long-arrow-alt-left text-white"></i></button>

<script>
function goBack() {
  window.history.back();
}
</script>
                                <h3 class="text-dark mb-1" style="margin-top: 30px;margin-bottom: 53px;">'.$nombre.'</h3>
                                <div class="row" style="margin-top: 20px;">
                                    <div class="col">
                                        <div class="card shadow-none border-left-primary py-2">
                                            <div class="card-body">
                                                <div class="row align-items-center no-gutters">
                                                    <div class="col mr-2">
                                                        <div class="text-uppercase text-primary font-weight-bold text-xs mb-1"><span>KILOS</span></div>
                                                        <div class="text-dark font-weight-bold h5 mb-0"><span>'.$peso.'</span></div>
                                                    </div>
                                                    <div class="col-auto"><i class="fas fa-weight-hanging fa-2x text-gray-300"></i></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="card shadow-none border-left-success py-2">
                                            <div class="card-body">
                                                <div class="row align-items-center no-gutters">
                                                    <div class="col mr-2">
                                                        <div class="text-uppercase text-success font-weight-bold text-xs mb-1"><span>cajas</span></div>
                                                        <div class="text-dark font-weight-bold h5 mb-0"><span>'.$c_cajas.'</span></div>
                                                    </div>
                                                    <div class="col-auto"><i class="fas fa-pallet fa-2x text-gray-300"></i></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="card shadow-none border-left-warning py-2">
                                            <div class="card-body">
                                                <div class="row align-items-center no-gutters">
                                                    <div class="col mr-2">
                                                        <div class="text-uppercase text-warning font-weight-bold text-xs mb-1"><span>VITICULTOR</span></div>
                                                        <div class="text-dark font-weight-bold h5 mb-0"><span>'.$vit_real.'</span></div>
                                                    </div>
                                                    <div class="col-auto"><i class="fas fa-user-alt fa-2x text-gray-300"></i></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row align-items-center no-gutters" style="margin-top: 30px;">
                            <img alt="testing" src="barcode/barcode.php?codetype=code128&size=90&text='.$text.'&print=true" width="500" height="400"/></div>
                        </div><span style="margin-top: 16px;">*El codigo de palet es el mostrado debajo de el de barras, es posible que la información aqui mostrada pueda variar debido a modificaciones del registro, le recomendamos consultar el codio de palet accediendo a la página de bodega que le hemos proporcionado para obtener la información más reciente.</span>
                        <footer
                            class="bg-white sticky-footer">
                            <div class="container my-auto">
                                <div class="text-center my-auto copyright"><span>Copyright © CPLogisics 2020</span></div>
                            </div>
                            </footer>
                    </div>
                </div>
                <script src="assets/js/jquery.min.js"></script>
                <script src="assets/bootstrap/js/bootstrap.min.js"></script>
                <script src="assets/js/chart.min.js"></script>
                <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.4.1/jquery.easing.js"></script>
                <script src="assets/js/script.min.js"></script>
            </body>
            
            </html>';

        }
 ?>
    </div>
</body>
