<?php
function error(int $i){
    $error = array( 

        'Debes seleccionar una causa si el operario esta ausente.',
        'Debes seleccionar al menos un puesto para poder fichar al operario.',
        'El operario debe tener asignadas las horas en caso de usar el puesto 2.',
        'Si el operador no está ausente, debe de seleccionar minimo (1) puesto y sus respectivas horas.',
        'Ya se ha fichado ese operario, reviselo en la tabla inferior.',
        'Parece que ya hay un turno comenzado con este operario, por favor recargue la pagina y cierre el turno anterior si desea abrir uno nuevo, o reanudelo si desea continuarlo'
    );
    return $error[$i];
}








?>