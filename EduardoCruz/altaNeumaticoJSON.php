<?php

use Cruz\Eduardo\Neumatico;

require_once "./clases/Neumatico.php";

$marca = $_POST["marca"] ;
$medidas = $_POST["medidas"];
$precio = $_POST["precio"];

$path = "./archivos/neumaticos.json";

if($marca !== NULL && $medidas !== NULL & $precio !== NULL) {

    $neumatico = new Neumatico($marca,$medidas,$precio);
    $respuesta = $neumatico->guardarJSON($path);

    echo json_encode($respuesta);
}

// $tabla = isset($_GET['tabla']) ? $_GET['tabla'] : '';