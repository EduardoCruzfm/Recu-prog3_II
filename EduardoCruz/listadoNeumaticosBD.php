<?php
// namespace CruzEduardo;
use Cruz\Eduardo\NeumaticoBD;

require_once "./clases/neumaticoBD.php";

$tabla = isset($_GET['tabla']) ? $_GET['tabla'] : '';


// Obtener todos los usuarios
$lista_neumaticos = NeumaticoBD::traer();

if ($tabla == "mostrar") {

    // Iniciar el HTML para la tabla
    echo '<table border="1">';
    echo '<tr><th>marca</th><th>medidas</th><th>precio</th><th>Foto</th></tr>';

    foreach ($lista_neumaticos as $neu) {
        $neumatico =  json_decode($neu->toJSON());

        echo '<tr>';
        echo '<td>' . htmlspecialchars($neumatico->marca) . '</td>';
        echo '<td>' . htmlspecialchars($neumatico->medidas) . '</td>';
        echo '<td>' . htmlspecialchars($neumatico->precio) . '</td>';
        echo '<td><img src="' . htmlspecialchars($neumatico->pathFoto) . '" alt="Foto" width="50" height="50"></td>';
        echo '</tr>';
    }

    // Cerrar la tabla
    echo '</table>';
}else{

    foreach ( $lista_neumaticos as $n){

        echo json_encode($n->toJSON());
    }
}

?>
