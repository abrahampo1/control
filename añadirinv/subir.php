<?php
include('../../conectar.php');
$conn = $link;
//traspasamos a variables locales, para evitar complicaciones con las comillas:
$viticultor = $_POST["viticultor"];
$peso = $_POST["peso"];
$exceso = 0;
$tipo_palet = $_POST["tipo_palet"];
$con2 = 'SELECT * FROM personas WHERE vit='.$viticultor;
$vit_real = $viticultor;
$cajas = $_POST["cajas"];
$c_cajas = $_POST["c_cajas"];
$bodega = $_POST["bodega"];
$asociado_bool = false;
include("../actualizarpeso.php");
actualizar($viticultor, $link);
actualizar($vit_real, $link);
if($res = mysqli_query($conn, $con2)){
    $data = mysqli_fetch_assoc($res);
    if($data["superficie"] == 0)
    {
        header("Location: ../../profile.php?id=".$viticultor."&err=423");
        exit;
    }
    include_once("../restar_pesos.php");
    $peso_total = peso_rebajado($link, $peso, $c_cajas, $cajas, $tipo_palet);
    $pesonuevo = $data["kg_entregados"] + $peso_total;
    if($pesonuevo < $data["disponible"]){
        $con3 = 'UPDATE personas SET kg_entregados = '.$pesonuevo.' WHERE personas.vit ='.$viticultor;
        $conexion = mysqli_query($conn, $con3);  
        $vit_real = $viticultor;
    }
    else{
        $asociados = explode("#",$data["asociados"]);
        if(count($asociados)<=1){
            header("Location: ../../palets.php?err=422");
            exit;
        }
        $disponible = false;
        for($i = 0; $i<count($asociados)-1; $i++){
            if($data["disponible"] != 0)
            {
                $restante = $data["disponible"] - $data["kg_entregados"];
                $exceso = $peso_total - $restante;
                $con3 = 'UPDATE personas SET exceso = '.$restante.' WHERE personas.vit ='.$viticultor;
                $conexion = mysqli_query($conn, $con3);  
                $tmp_con = "SELECT * FROM personas WHERE vit =".$asociados[$i];
                actualizar($asociados[$i], $link);
                $result0 = mysqli_query($conn, $tmp_con);
                $result = mysqli_fetch_assoc($result0);
                $pesototal = $result["disponible"]-$result["kg_entregados"];
                if($exceso < $pesototal){
                $pesoactual=$exceso+$result["kg_entregados"];
                $exceso_nuevo = $exceso + $result["exceso"];
                $con3 = 'UPDATE personas SET kg_entregados = '.$pesoactual.', exceso = '.$exceso_nuevo.' WHERE personas.vit ='.$asociados[$i];
                $conexion = mysqli_query($conn, $con3); 
                $viticultor = $asociados[$i];
                $asociado_bool = true;
                $disponible = true;
                break;
            }else{
                $disponible = false;
            }
            }else
            {
                $tmp_con = "SELECT * FROM personas WHERE vit =".$asociados[$i];
                $result0 = mysqli_query($conn, $tmp_con);
                $result = mysqli_fetch_assoc($result0);
                $pesototal = $result["disponible"]-$result["kg_entregados"];
                if($peso < $pesototal){
                    $pesoactual=$peso_total+$result["kg_entregados"];
                    $exceso = $peso_total;
                    $exceso_nuevo = $exceso + $result["exceso"];
                    $con3 = 'UPDATE personas SET kg_entregados = '.$pesoactual.', exceso = '.$exceso_nuevo.' WHERE personas.vit ='.$asociados[$i];
                    $conexion = mysqli_query($conn, $con3); 
                    $viticultor = $asociados[$i];
                    $asociado_bool = true;
                    $disponible = true;
                    break;
                }else{
                    $disponible = false;
                }
            }
            
        }
        if($disponible == false){
            header("Location: ../../palets.php?err=422");
            exit;
        }
    }

}


$con = $conn;
//Validamos que hayan llegado estas variables, y que no esten vacias:
if (isset($_POST["viticultor"], $_POST["peso"], $_POST["cajas"], $_POST["c_cajas"], $_POST["bodega"]) and $_POST["viticultor"] !="" and $_POST["peso"]!="" and $_POST["cajas"]!="" and $_POST["c_cajas"] and $_POST["bodega"]){


    $cajas = $_POST["cajas"];
    $c_cajas = $_POST["c_cajas"];
    $bodega = $_POST["bodega"];
    //Preparamos la orden SQL
    $orden = 'SELECT * FROM personas WHERE id = '.$viticultor.'';
    if ($resultado = mysqli_query($conn, $orden)) {
        while ($row = mysqli_fetch_assoc($resultado)) {
            echo '<br><br>VITICULTOR: ('.$row["vit"].') '.$row["nombre"].'';
        }
    }

} else {

    //echo '<p>Por favor, complete el <a href="formulario.html">formulario</a></p>';
}
$ahora = getdate();
if($ahora["mday"]<10){
    $dia = "0".$ahora["mday"];
}else{
    $dia = $ahora["mday"];
}
if($ahora["mon"]<10){
    $mon = "0".$ahora["mon"];
}else{
    $mon = $ahora["mon"];
}
$data = $ahora["year"].'-'.$mon.'-'.$dia.'';
if(isset($_POST["remontado"]))
{
    $remontado = $_POST["remontado"];
    
}else
{
    $remontado = null;
}

$consulta = "INSERT INTO palets
(id,n_viticultor,peso,tipo_caja,c_cajas,bodega,fecha,vit_real,tipo_palet,remontado,exceso) VALUES ('0','$viticultor','$peso','$cajas','$c_cajas','$bodega','$data','$vit_real','$tipo_palet','$remontado', '$exceso')";

//Aqui ejecutaremos esa orden
if (mysqli_query($conn, $consulta))
{

    echo "Actualizado e Impreso a dia ".$ahora["year"]."-".$mon."-".$dia."  ".$ahora["hours"].":".$ahora["minutes"].":".$ahora["seconds"];
    $id = $conn->insert_id;
    header("Location: barcode.php?vit_real=".$vit_real."&id=".$id."&asoc=".$asociado_bool."&viticultor=".$viticultor."&peso=".$peso."&c_cajas=".$c_cajas."&bodega=".$bodega);
}else{
    echo "Error: <br>" . mysqli_error($conn);
}

mysqli_close($conn);
?>

