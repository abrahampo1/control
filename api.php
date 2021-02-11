<?php
include("database.php");
include("protect.php");
if (isset($_GET["token"])) {
    $token = $_GET["token"];
    $sql = "SELECT * FROM usuarios WHERE api = BINARY '$token'";
    $do = mysqli_query($link, $sql);
    if ($do->num_rows != 0) {
        $info_cliente = mysqli_fetch_assoc($do);
        if (isset($_GET["request"])) {
            $peticion = $_GET["request"];
            $sql = "";
            if ($peticion == 'operarios') {
                $sql = "SELECT * FROM personal";
            }
            if($sql == "")
            {
                header("location: index.php");
            }
            if ($do = mysqli_query($link, $sql)) {
                $myArray = array();
                while ($row = mysqli_fetch_assoc($do)) {
                    $myArray[] = $row;
                }
                echo json_encode($myArray);
            } else {
                echo "Error en el uso del API";
            }
        } else {
            header("location: index.php");
        }
    } else {
        echo "Clave Api incorrecta";
        exit;
    }
} else {
    echo "El API se encuentra en funcionamiento, si quieres saber como se usa, buscalo en la documentacion corresponiente";
    exit;
}
