<?php
use Cruz\Eduardo\NeumaticoBD;
require_once "./clases/neumaticoBD.php";

// Obtener los valores enviados por POST
$marca = $_POST["marca"];
$medidas = $_POST["medidas"];
$precio = $_POST["precio"];
$foto = $_FILES["foto"];

$respuesta = new stdClass();
$respuesta->exito = false;
$respuesta->mensaje = "Error al agregar el neumático.";

$neumaticoNuevo = new NeumaticoBD($marca, $medidas, $precio);
$arrayNeumaticos = NeumaticoBD::traer();


if ($neumaticoNuevo->existe($arrayNeumaticos)) {
    $respuesta->mensaje = "El neumático ya existe en la base de datos.";
}
else {
    // Procesar la imagen
    $timestamp = date("His");
    $extension = pathinfo($foto["name"], PATHINFO_EXTENSION);
    $fotoNombre = $marca . "." . $timestamp . "." . $extension;
    $directorio = "./neumaticos/imagenes/";
    $rutaDestino = $directorio . $fotoNombre;

    if (!is_dir($directorio)) {
        mkdir($directorio, 0777, true);
    }

    if (move_uploaded_file($foto["tmp_name"], $rutaDestino)) {

        $neumaticoConFoto = new NeumaticoBD($marca, $medidas, $precio,"", $fotoNombre);
        
        // Intentar agregar el neumático a la base de datos
        if ($neumaticoConFoto->agregar()) {
            $respuesta->exito = true;
            $respuesta->mensaje = "Neumático agregado exitosamente.";
        } else {
            $respuesta->mensaje = "Error al agregar el neumático a la base de datos.";
        }

    } else {
        $respuesta->mensaje = "Error al subir la imagen.";
    }
}

echo json_encode($respuesta);


?>