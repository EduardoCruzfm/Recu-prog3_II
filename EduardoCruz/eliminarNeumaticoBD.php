<?php
use Cruz\Eduardo\NeumaticoBD;
require_once "./clases/neumaticoBD.php";

$path = "./archivos/neumaticos_eliminados.json";

$neumatico_json = $_POST["neumatico_json"]; // id, marca, medidas y precio
$neu_obj = json_decode($neumatico_json);

$estado = NeumaticoBD::eliminar($neu_obj->id);

$response = new stdClass();
$response->exito = false;
$response->mensaje = "Error al eliminar";


if ($estado == true) {
    $n_delete = new NeumaticoBD($neu_obj->marca, $neu_obj->medidas, $neu_obj->precio, $neu_obj->id);
    $n_delete->guardarJSON($path);

    $response->exito = true;
    $response->mensaje = "Exito al eliminar";
}

echo json_encode($response);
// {"marca" : "Khumo", "medidas" : "225-75-R15", "precio" : 132900 ,"id" : 31}
?>