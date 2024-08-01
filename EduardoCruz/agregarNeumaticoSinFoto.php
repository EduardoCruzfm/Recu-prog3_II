<?php
require_once "./clases/neumaticoBD.php";
require_once "./clases/accesoDatos.php";

use Cruz\Eduardo\NeumaticoBD;

$response = new stdClass();
$response->exito = false;
$response->mensaje = "No Agregado";

if (isset($_POST["neumatico_json"])){

    $neumático_json = $_POST["neumatico_json"]; 
    $n = json_decode($neumático_json);

    $marca = $n->marca;
    $medidas = $n->medidas;
    $precio = $n->precio;

    $neumatico = new NeumaticoBD($marca,$medidas,$precio);

    if ($neumatico->agregar()) {
        $response->exito = true;
        $response->mensaje = "Agregado";
    }
}





echo json_encode($response);

// neumático_json (marca, medidas y precio)
// {"marca" : "Khumo", "medidas" : "225-75-R15", "precio" : 132900}
?>