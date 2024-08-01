<?php

use Cruz\Eduardo\Neumatico;

require_once "./clases/Neumatico.php";

$path = "./archivos/neumaticos.json";
$array_res = Neumatico::traerJSON($path);

foreach ($array_res as $n) {
    # code...
    echo json_encode($n->toJSON());
}


?>