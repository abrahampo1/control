<?php
// Import PHPMailer classes into the global namespace
// These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

include('database.php');
require('protect.php');
if (!isset($_SESSION["turno"])) {
    if (isset($_GET["id"])) {
        $turno = $_GET["id"];
    } else {
        header('location: index.php');
    }
} else {
    $turno = $_SESSION["turno"];
}

$fecha_ahora = date('Y-m-d', time());
$dias = 1;
$incidencias_totales = 0;
$reportes_totales = 0;
$auditorias_totales = 0;
$ausencias_totales = 0;
$sql = "SELECT * FROM turnos WHERE id = $turno";
$do = mysqli_query($link, $sql);
$info_turno = mysqli_fetch_assoc($do);
$encargado = $info_turno["encargado"];
$sql = "SELECT * FROM usuarios WHERE id = $encargado";
$do = mysqli_query($link, $sql);
$info_encargado = mysqli_fetch_assoc($do);
$sql = "SELECT * FROM incidencias WHERE turno = '$turno'";
if ($fo = mysqli_query($link, $sql)) {
    $incidencias_totales += $fo->num_rows;
}
$sql = "SELECT * FROM reportes WHERE turno = '$turno'";
if ($fo = mysqli_query($link, $sql)) {
    $reportes_totales += $fo->num_rows;
}
$sql = "SELECT * FROM auditorias WHERE turno = '$turno'";
if ($fo = mysqli_query($link, $sql)) {
    $auditorias_totales += $fo->num_rows;
}
$sql = "SELECT * FROM ausencias_rot WHERE turno = '$turno' AND ausente='true'";
if ($fo = mysqli_query($link, $sql)) {
    $ausencias_totales += $fo->num_rows;
}
$currentWeekNumber = date('W');
$sql = "SELECT * FROM ajustes WHERE nombre = 'mailpass'";
$do = mysqli_query($link, $sql);
$info_mailpass = mysqli_fetch_assoc($do);
$sql = "SELECT * FROM ajustes WHERE nombre = 'mailuser'";
$do = mysqli_query($link, $sql);
$info_mailuser = mysqli_fetch_assoc($do);
$sql = "SELECT * FROM ajustes WHERE nombre = 'mailserver'";
$do = mysqli_query($link, $sql);
$info_mailserver = mysqli_fetch_assoc($do);
$sql = "SELECT * FROM ajustes WHERE nombre = 'mailport'";
$do = mysqli_query($link, $sql);
$info_mailport = mysqli_fetch_assoc($do);
$sql = "SELECT * FROM ajustes WHERE nombre = 'mailto'";
$do = mysqli_query($link, $sql);
$info_mailto = mysqli_fetch_assoc($do);
// Load Composer's autoloader
require 'vendor/autoload.php';

// Instantiation and passing `true` enables exceptions
$arraymailto = explode(';', $info_mailto['value']);
if(count($arraymailto) == 0)
{
    $arraymailto = array(
        $info_mailto["value"]
    );
}
for ($i = 0; $i < count($arraymailto); $i++) {
    $mail = new PHPMailer(true);

    try {
        //Server settings                     // Enable verbose debug output
        $mail->isSMTP();                                            // Send using SMTP
        $mail->Host       = $info_mailserver["value"];                    // Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
        $mail->Username   = $info_mailuser["value"];                     // SMTP username
        $mail->Password   = $info_mailpass["value"];                               // SMTP password
        $mail->Port       = $info_mailport["value"];                                    // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

        //Recipients
        $mail->setFrom('abraham@cpsoftware.es', 'Ilunion');
        $mail->addAddress($arraymailto[$i], '');

        // Content
        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8';                                 // Set email format to HTML
        $mail->Subject = 'Informe Ilunion Semana ' . $currentWeekNumber;

        $cuerpo = '<p style="text-align: center;"><img src="https://www.curralia.es/wp-content/uploads/2020/04/ilunion-logo.jpg" alt="" width="157" height="81" /></p>
    <p style="text-align: center;">Informe de turno, versi&oacute;n MAIL</p>
    <p style="text-align: center;">Turno: ' . $turno . '</p>
    <p style="text-align: center;">Responsable: ' . $info_encargado["nombre"] . '</p>
    <p style="text-align: center;">Semana: ' . $currentWeekNumber . '</p>
    <table style="border-collapse: collapse; width: 100%; height: 36px;" border="1">
    <tbody>
    <tr style="height: 18px;">
    <td style="width: 25%; height: 18px;">Incidencias</td>
    <td style="width: 25%; height: 18px;">Reportes</td>
    <td style="width: 25%; height: 18px;">Auditorias</td>
    <td style="width: 25%; height: 18px;">Ausencias</td>
    </tr>
    <tr style="height: 18px;">
    <td style="width: 25%; height: 18px;">' . $incidencias_totales . '</td>
    <td style="width: 25%; height: 18px;">' . $reportes_totales . '</td>
    <td style="width: 25%; height: 18px;">' . $auditorias_totales . '</td>
    <td style="width: 25%; height: 18px;">' . $ausencias_totales . '</td>
    </tr>
    </tbody>
    </table>
    <p style="text-align: center;">Cambios en la planificaci&oacute;n</p>
    ';

        $sql = "SELECT * FROM cambios WHERE turno = $turno";
        $do = mysqli_query($link, $sql);
        $num_cambios = 0;
        while ($row = mysqli_fetch_assoc($do)) {
            $num_cambios++;
            $cuerpo .= '<p>- ' . $row["cambio"] . '</p>';
        }
        if ($num_cambios == 0) {
            $cuerpo .= '<p>No hay ningún cambio</p>';
        };

        $cuerpo .= '
    <p style="text-align: center;">Asistencia y Rotaci&oacute;n</p>
    <table style="border-collapse: collapse; width: 100%;" border="1">
    <tbody>
    <tr>
    <td style="width: 20%;">Nombre</td>
    <td style="width: 20%;">Puesto 1</td>
    <td style="width: 20%;">Horas</td>
    <td style="width: 20%;">Puesto 2</td>
    <td style="width: 20%;">Horas</td>
    </tr>';

        $sql = "SELECT * FROM ausencias_rot WHERE turno = $turno AND ausente='false'";
        $do = mysqli_query($link, $sql);
        if (isset($_SESSION["turno"]) && $do->num_rows != 0) {

            while ($row = mysqli_fetch_assoc($do)) {
                $num_operario = $row["num_operario"];
                $sql = "SELECT * FROM personal WHERE id = '$num_operario'";
                $buscar = mysqli_query($link, $sql);
                $datos_operador = mysqli_fetch_assoc($buscar);
                if ($row['ausente'] == "true") {
                    $ausente = 'SI';
                } else {
                    $ausente = 'NO';
                }
                $puesto1 = $row["puesto1"];
                $puesto2 = $row["puesto2"];

                if ($puesto1 > 0) {
                    $sql = "SELECT * FROM puestos WHERE id = $puesto1";
                    $p1 = mysqli_query($link, $sql);
                    $result = mysqli_fetch_assoc($p1);
                    $puesto1 = $result["nombre"];
                }

                if ($puesto2 > 0) {
                    $sql = "SELECT * FROM puestos WHERE id = $puesto2";
                    $p1 = mysqli_query($link, $sql);
                    $result = mysqli_fetch_assoc($p1);
                    $puesto2 = $result["nombre"];
                }
                $fecha = $row["fecha"];
                $cuerpo .= '<tr>
			  <td style="width: 20%;">' . $datos_operador['nombre'] . '</td>
			  <td style="width: 20%;">' . $puesto1 . '</td>
			  <td style="width: 20%;">' . $row["horas1"] . '</td>
			  <td style="width: 20%;">' . $puesto2 . '</td>
			  <td style="width: 20%;">' . $row["horas2"] . '</td>
			  </tr>';
            }
        }

        $cuerpo .= '
    </tbody>
    </table>
    <p style="text-align: center;">Ausencias en el turno</p>
    <table style="border-collapse: collapse; width: 100%;" border="1">
    <tbody>
    ';

        $sql = "SELECT * FROM ausencias_rot WHERE turno = $turno AND ausente = 'true'";
        $do = mysqli_query($link, $sql);
        if (isset($_SESSION["turno"]) && $do->num_rows != 0) {
            $cuerpo .= '<tr>
      <td style="width: 20%;">Nombre</td>
      <td style="width: 20%;">Causa</td>
      </tr>';

            while ($row = mysqli_fetch_assoc($do)) {
                $num_operario = $row["num_operario"];
                $sql = "SELECT * FROM personal WHERE id = '$num_operario'";
                $buscar = mysqli_query($link, $sql);
                $datos_operador = mysqli_fetch_assoc($buscar);
                if ($row['ausente'] == "true") {
                    $ausente = 'SI';
                } else {
                    $ausente = 'NO';
                }
                $puesto1 = $row["puesto1"];
                $puesto2 = $row["puesto2"];

                if ($puesto1 > 0) {
                    $sql = "SELECT * FROM puestos WHERE id = $puesto1";
                    $p1 = mysqli_query($link, $sql);
                    $result = mysqli_fetch_assoc($p1);
                    $puesto1 = $result["nombre"];
                }

                if ($puesto2 > 0) {
                    $sql = "SELECT * FROM puestos WHERE id = $puesto2";
                    $p1 = mysqli_query($link, $sql);
                    $result = mysqli_fetch_assoc($p1);
                    $puesto2 = $result["nombre"];
                }
                $fecha = $row["fecha"];
                $cuerpo .= '<tr>
        <td style="width: 20%;">' . $datos_operador['nombre'] . '</td>
        <td style="width: 20%;">' . $row['causa'] . '</td>
        </tr>';
            }
        } else {
            $cuerpo .= '<p style="text-align: center;">No ha habido ausencias en este turno</p>';
        }

        $cuerpo .= '
    </tbody>
    </table>
    <p style="text-align: center;">Reportes</p>
    <table style="border-collapse: collapse; width: 100%;" border="1">
    <tbody>';

        $sql = "SELECT * FROM reportes WHERE turno = '$turno'";
        $do = mysqli_query($link, $sql);
        if ($do->num_rows > 0) {
            $cuerpo .= '
        <tr>
        <td style="width: 33.3333%;">Nombre</td>
        <td style="width: 33.3333%;">Puesto</td>
        <td style="width: 33.3333%;">Reporte</td>
        </tr>';
            while ($row = mysqli_fetch_assoc($do)) {
                $puesto = $row["puesto"];
                $sql = "SELECT * FROM puestos WHERE id = '$puesto'";
                $do2 = mysqli_query($link, $sql);
                $puesto = mysqli_fetch_assoc($do2);
                $cuerpo .= '<tr>
        <td>' . $row["operario"] . '</td>
        <td>' . $puesto["nombre"] . '</td>
        <td>' . $row["reporte"] . '</td>
    </tr>';
            }
        } else {
            $cuerpo .= '<p style="text-align: center;">No ha habido ningún reporte.</p>';
        }

        $cuerpo .= '
    </tbody>
    </table>
    <p style="text-align: center;">Accidentes e Incidencias detectadas</p>
    <table style="border-collapse: collapse; width: 100%;" border="1">
    <tbody>';

        $sql = "SELECT * FROM incidencias WHERE turno = '$turno'";
        $do = mysqli_query($link, $sql);
        if ($do->num_rows > 0) {
            $cuerpo .= '
        <tr>
        <td style="width: 33.3333%;">Nombre</td>
        <td style="width: 33.3333%;">Puesto</td>
        <td style="width: 33.3333%;">Incidencia</td>
        </tr>';
            while ($row = mysqli_fetch_assoc($do)) {
                $puesto = $row["puesto"];
                $sql = "SELECT * FROM puestos WHERE id = '$puesto'";
                $do2 = mysqli_query($link, $sql);
                $puesto = mysqli_fetch_assoc($do2);
                $cuerpo .= '<tr>
        <td>' . $row["operario"] . '</td>
        <td>' . $puesto["nombre"] . '</td>
        <td>' . $row["incidencia"] . '</td>
    </tr>';
            }
        } else {
            $cuerpo .= '<p style="text-align: center;">No ha habido ninguna incidencia.</p>';
        }

        $cuerpo .= '
    </tbody>
    </table>
    <p style="text-align: center;">Auditorias Realizadas</p>
    <table style="border-collapse: collapse; width: 100%;" border="1">
    <tbody>
    ';
        $sql = "SELECT * FROM auditorias WHERE turno = '$turno'";
        $do = mysqli_query($link, $sql);
        if ($do->num_rows > 0) {
            $cuerpo .= '<tr>
        <td style="width: 14.2857%;">Nombre</td>
        <td style="width: 14.2857%;">Seguridad</td>
        <td style="width: 14.2857%;">Medioambiente</td>
        <td style="width: 14.2857%;">Calidad</td>
        <td style="width: 14.2857%;">Cumplimiento de EPIS</td>
        <td style="width: 14.2857%;">Propuestas</td>
        <td style="width: 14.2857%;">Acciones</td>
        </tr>';
            while ($row = mysqli_fetch_assoc($do)) {
                $num_operario = $row["num_operario"];
                $sql = "SELECT * FROM personal WHERE id = '$num_operario'";
                $do2 = mysqli_query($link, $sql);
                $info = mysqli_fetch_assoc($do2);
                $nombre_operario = $info["nombre"];
                $seguridad = "NO";
                $medioambiente = "NO";
                $calidad = "NO";
                $epis = "NO";
                if ($row["seguridad"] == 1) {
                    $seguridad = "SI";
                }
                if ($row["medioambiente"] == 1) {
                    $medioambiente = "SI";
                }
                if ($row["calidad"] == 1) {
                    $calidad = "SI";
                }
                if ($row["epis"] == 1) {
                    $epis = "SI";
                }
                $cuerpo .= '<tr>
        <td>' . $nombre_operario . '</td>
        <td>' . $seguridad . '</td>
        <td>' . $medioambiente . '</td>
        <td>' . $calidad . '</td>
        <td>' . $epis . '</td>
        <td>' . $row["propuestas"] . '</td>
        <td>' . $row["acciones"] . '</td>
    </tr>';
            }
        } else {
            $cuerpo .= '<p style="text-align: center;">No ha habido ninguna auditoria.</p>';
        }

        $cuerpo .= '
    </tbody>
    </table>';

        $mail->Body    = $cuerpo;
        $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

        $mail->send();
        header("location: index.php?nice=6");
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}
