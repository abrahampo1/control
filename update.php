<?php
function execPrint($command) {
    $result = array();
    exec($command, $result);
    print("<pre>");
    foreach ($result as $line) {
        print($line . "\n");
    }
    print("</pre>");
}
include('database.php');
$sql = "SELECT * FROM ajustes WHERE nombre = 'gituser'";
$do = mysqli_query($link, $sql);
$user = mysqli_fetch_assoc($do);
$sql = "SELECT * FROM ajustes WHERE nombre = 'gitpass'";
$do = mysqli_query($link, $sql);
$pass = mysqli_fetch_assoc($do);

execPrint("git pull https://".$user["value"].":".$pass["value"]."@github.com/abrahampo1/control.git beta");
//header("location: index.php?nice=0");
?>