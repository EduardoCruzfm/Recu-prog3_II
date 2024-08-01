<?php
require_once "./clases/neumaticoBD.php";
use Cruz\Eduardo\NeumaticoBD;

$response = new stdClass();
$response->exito = false;
$response->mensaje = "Error al eliminar el neumático";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST["neumatico_json"])) {

        $neumatico_json = $_POST["neumatico_json"];
        $neu_obj = json_decode($neumatico_json);

        if (NeumaticoBD::eliminar($neu_obj->id)) {

            $neumatico = new NeumaticoBD($neu_obj->marca, $neu_obj->medidas, $neu_obj->precio, $neu_obj->id, $neu_obj->pathFoto);
            if ($neumatico->guardarArchivo()) {
                $response->exito = true;
                $response->mensaje = "Neumático eliminado y guardado en el archivo";
            } else {
                $response->mensaje = "Neumático eliminado pero error al guardar en el archivo";
            }
        }
    }
    echo json_encode($response);

} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {

    $pathArchivo = "./archivos/neumaticosbd_borrados.txt";

    if (file_exists($pathArchivo)) {

        $neumaticosBorrados = file($pathArchivo, FILE_IGNORE_NEW_LINES);
        
        echo '<table border="1">';
        echo '<tr><th>ID</th><th>Marca</th><th>Medidas</th><th>Precio</th><th>Foto</th></tr>';
        foreach ($neumaticosBorrados as $neumaticoStr) {
            $neumatico = json_decode($neumaticoStr);
            echo '<tr>';
            echo '<td>' . $neumatico->id . '</td>';
            echo '<td>' . $neumatico->marca . '</td>';
            echo '<td>' . $neumatico->medidas . '</td>';
            echo '<td>' . $neumatico->precio . '</td>';
            echo '<td><img src="' . $neumatico->pathFoto . '" alt="Foto" width="50" height="50"></td>';
            echo '</tr>';
        }
        echo '</table>';
    } else {
        echo "No hay neumáticos borrados.";
    }
}
?>
