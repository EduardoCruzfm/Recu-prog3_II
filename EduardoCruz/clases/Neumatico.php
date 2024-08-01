<?php
namespace Cruz\Eduardo;
    use stdClass;


    class Neumatico {
        protected $marca;
        protected $medidas;
        protected $precio;
    
        public function __construct($marca, $medidas, $precio) {
            $this->marca = $marca;
            $this->medidas = $medidas;
            $this->precio = $precio;
        }
    
        public function toJSON() {
            $retorno = '{"marca" : "' . $this->marca . '", "medidas" : "' . $this->medidas . '", "precio" : ' . $this->precio . '}';
            return $retorno;
        }

        public function guardarJSON($path){

            $retorno = new stdClass();
            $retorno->exito = false;
            $retorno->mensaje = "error guardar el archivo";

            $archivo = fopen($path,"a");

            $caracteresEscritos = fwrite($archivo ,$this->ToJSON() . "\r\n");

            if ($caracteresEscritos > 0) {
                $retorno->exito = true;
                $retorno->mensaje = "exito al guardar el archivo";
            }

            fclose($archivo);
            
            return $retorno;
            
        }


        public static function traerJSON($path):array{
            $texto = "";
            $array_respuesta = array();
            $archivo = fopen($path,"r");
            
            if ($archivo !== false) {
                
                while (!feof($archivo)) {        
                    $texto .= fgetc($archivo);
                }
                
                
                $obj_array = explode("\r\n",$texto);
                fclose($archivo);
                
                foreach($obj_array as $item){
                    
                    if ($item !== "") {
                        
                        $obj = json_decode($item);
                        
                        $neumatico = new Neumatico($obj->marca, $obj->medidas, $obj->precio);
    
                        array_push($array_respuesta,$neumatico); //Es protegido usar toJSON
                    }
                }
            }
    
            return $array_respuesta;
        }


    }




?>