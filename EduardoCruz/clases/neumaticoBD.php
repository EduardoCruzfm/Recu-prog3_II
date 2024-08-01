<?php
namespace Cruz\Eduardo;
use stdClass;

require_once "Neumatico.php";
require_once "IParte1.php";
require_once "IParte2.php";
require_once "IParte3.php";
require_once "IParte4.php";
require_once "accesoDatos.php"; 

class NeumaticoBD extends Neumatico implements IParte1 , IParte2 , IParte3 , IParte4{
    protected $id;
    protected $pathFoto;

    public function __construct($marca, $medidas, $precio, $id = null, $pathFoto = "") {
        parent::__construct($marca, $medidas, $precio);
        $this->id = $id;
        $this->pathFoto = $pathFoto;
    }

    public function toJSON() {
        return json_encode([
            'id' => $this->id,
            'marca' => $this->marca,
            'medidas' => $this->medidas,
            'precio' => $this->precio,
            'pathFoto' => $this->pathFoto
        ]);
    }

    public function agregar(): bool {

        $objPDO = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objPDO->retornarConsulta("INSERT INTO neumaticos (marca, medidas, precio, foto) 
        VALUES (:marca, :medidas, :precio ,:foto)");


        $consulta->bindValue(':marca', $this->marca, \PDO::PARAM_STR);
        $consulta->bindValue(':medidas', $this->medidas, \PDO::PARAM_STR);
        $consulta->bindValue(':precio', $this->precio, \PDO::PARAM_INT);
        $consulta->bindValue(':foto', $this->pathFoto, \PDO::PARAM_STR);
        $consulta->execute();

        
        if($consulta->rowCount() == 1){
            return true;
        }

        return false;
    }

    public static function traer(): array {
        $objPDO = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objPDO->retornarConsulta("SELECT id, marca, medidas, precio, foto FROM neumaticos");
        $consulta->execute();

        $neumaticos = [];

        while ($fila = $consulta->fetch(\PDO::FETCH_ASSOC)) {

            if ($fila['foto'] == NULL) {
                $neumatico = new NeumaticoBD(
                
                    $fila['marca'],
                    $fila['medidas'],
                    $fila['precio'],
                    $fila['id'],
                    ''
                );
                array_push($neumaticos, $neumatico);
            }
            else{
                $neumatico = new NeumaticoBD(
                
                    $fila['marca'],
                    $fila['medidas'],
                    $fila['precio'],
                    $fila['id'],
                    $fila['foto']
                );
                array_push($neumaticos, $neumatico);
            }
        }

        return $neumaticos;
    }

    public static function eliminar($id): bool{
        $retorno = false;

        $objPDO = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objPDO->retornarConsulta("DELETE FROM neumaticos WHERE id = :id");
        $consulta->bindParam(':id', $id, \PDO::PARAM_INT);
        $consulta->execute();

        if($consulta->rowCount() == 1){
            $retorno = true;
        }

        return $retorno;
    }

    public function modificar(): bool{
        // $retorno = false;
        $objPDO = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objPDO->retornarConsulta("UPDATE neumaticos 
                                                SET marca = :marca, medidas = :medidas, precio = :precio, foto = :foto 
                                                WHERE id = :id");


        $consulta->bindValue(':id', $this->id, \PDO::PARAM_INT);
        $consulta->bindValue(':marca', $this->marca, \PDO::PARAM_STR);
        $consulta->bindValue(':medidas', $this->medidas, \PDO::PARAM_STR);
        $consulta->bindValue(':precio', $this->precio, \PDO::PARAM_INT);
        $consulta->bindValue(':foto', $this->pathFoto, \PDO::PARAM_STR);

        $consulta->execute();

        
        if($consulta->rowCount() == 1){
            return true;
        }

        return false;
    }

    public function existe(array $neumaticos): bool{ //marca y medidas
        $retorno = false;

        foreach ($neumaticos as $item) {
            $item_json = json_decode($item->toJSON());

            if ($this->marca === $item_json->marca && $this->medidas === $item_json->medidas) {
                $retorno = true;
            }
        }


        return $retorno;
    }

    public function guardarArchivo(): string {
        $retorno = new stdClass();
        $retorno->exito = false;
        $retorno->mensaje = "Error al guardar el archivo";
    
        $pathArchivo = "./archivos/neumaticosbd_borrados.txt";
        $directorioFotos = "./neumaticosBorrados/";
        
        // Crear el directorio si no existe
        if (!is_dir($directorioFotos)) {
            mkdir($directorioFotos, 0777, true);
        }
    
        // Generar el nuevo nombre para la foto
        $timestamp = date("His");
        $extension = pathinfo($this->pathFoto, PATHINFO_EXTENSION);
        $nombreFoto = $this->id . "." . $this->marca . ".borrado." . $timestamp . "." . $extension;
        $rutaDestino = $directorioFotos . $nombreFoto;
    
        // Mover la foto
        if (file_exists($this->pathFoto) && rename($this->pathFoto, $rutaDestino)) {
            // Actualizar el pathFoto en el objeto
            $this->pathFoto = $rutaDestino;
    
            // Guardar la información en el archivo
            $archivo = fopen($pathArchivo, "a");
            $caracteresEscritos = fwrite($archivo, $this->toJSON() . "\r\n");
            fclose($archivo);
    
            if ($caracteresEscritos > 0) {
                $retorno->exito = true;
                $retorno->mensaje = "Éxito al guardar el archivo y mover la foto";
            } else {
                $retorno->mensaje = "Error al escribir en el archivo";
            }
        } else {
            $retorno->mensaje = "Error al mover la foto";
        }
    
        return json_encode($retorno);
    }
    
}
?>
