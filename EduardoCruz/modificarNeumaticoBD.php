<?php
require_once "./clases/neumaticoBD.php";
use Cruz\Eduardo\NeumaticoBD;


$response = new stdClass();
$response->exito = false;
$response->mensaje = "Error al modificar";

if (isset($_POST["neumatico_json"])) {
    
    
    $neumatico_json = $_POST["neumatico_json"]; // id, marca, medidas y precio
    $neu_obj = json_decode($neumatico_json);
    
   
    $neumatico_update = new NeumaticoBD($neu_obj->marca, $neu_obj->medidas, $neu_obj->precio, $neu_obj->id);
    $estado = $neumatico_update->modificar();

    if ($estado === true) {
        $response->exito = true;
        $response->mensaje = "Exito al modificar";
    }
}

echo json_encode($response);

// {"id" : 31, "marca" : "Khumo", "medidas" : "225-75-R15", "precio" : 80900}
?>