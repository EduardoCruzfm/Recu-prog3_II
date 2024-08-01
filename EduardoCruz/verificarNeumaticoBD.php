<?php
use Cruz\Eduardo\NeumaticoBD;
require_once "./clases/neumaticoBD.php";

$retorno = "{}";

if (isset($_POST["obj_neumatico"])) {

    // {"marca":"Khumo","medidas":"225-75-R15"}

    $obj_neumatico = $_POST["obj_neumatico"]; // marca y medidas
    $neumatico_json = json_decode($obj_neumatico);
    
    $marca = $neumatico_json->marca;
    $medidas = $neumatico_json->medidas;
    
    $array_neumaticos = NeumaticoBD::traer();

    foreach ($array_neumaticos as $n) {

        $n_json = json_decode($n->toJSON());
    
        if ($marca === $n_json->marca && $medidas === $n_json->medidas) {
            $retorno = $n_json;
            break;
        }
    }

}


echo json_encode($retorno);


?>