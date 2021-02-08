<?php
include("proteger.php");
include("database.php");
if(isset($_POST["sql"]))
{
    $sql = $_POST["sql"];
    if($do = mysqli_query($link, $sql))
    {
        echo "Base de datos actualizada";
    }
}

?>


<form method="post">
<input type="text" name="sql" id="">
<button type="submit">Enviar</button>
</form>