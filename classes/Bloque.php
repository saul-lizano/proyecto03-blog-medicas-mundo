<?php
// Incluir la clase DB para la conexión a la base de datos
include_once "DB.php";
// Clase Bloque: Representa un bloque de contenido en el sistema de gestión de Médicos del Mundo.
// Un bloque contiene texto, título, descripción, y pertenece a una categoría.
class Bloque
{
    // Propiedades privadas del bloque
    private $id_bloque;
    private $orden_bloque;
    private $titulo_bloque;
    private $descripcion_bloque;
    private $texto_bloque;
    private $id_categoria;
    private $fecha_actualizacion_bloque;
    private $icono;
    /**
     * Inicializa todas las propiedades del objeto
     * @param $id_bloque
     * @param $orden_bloque
     * @param $titulo_bloque
     * @param $descripcion
     * @param $texto_bloque
     * @param $fecha_actualizacion
     * @param $id_categoria
     * @param $icono
     */
     public function __construct($id_bloque, $orden_bloque, $titulo_bloque, $descripcion, $texto_bloque, $fecha_actualizacion, $id_categoria, $icono){
        $this->id_bloque = $id_bloque;
        $this->orden_bloque = $orden_bloque;
        $this->titulo_bloque = $titulo_bloque;
        $this->descripcion_bloque = $descripcion;
        $this->texto_bloque = $texto_bloque;
        $this->fecha_actualizacion_bloque = $fecha_actualizacion;
        $this->id_categoria = $id_categoria;
        $this->icono = $icono;
    }

    /************************************* METODOS **************************************/

    /**
     * Añade un nuevo bloque en la BD
     * Inserta los datos del objeto actual en la tabla 'bloque'
     */
    public function InsertarBloque() :void{
        $db = DB::conectar();
        $stmt = $db->prepare("INSERT INTO bloque (id_bloque, id_categoria, titulo, descripcion, texto, orden, fecha_actualizacion, icono) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $this->id_bloque,
            $this->id_categoria,
            $this->titulo_bloque,
            $this->descripcion_bloque,
            $this->texto_bloque,
            $this->orden_bloque,
            $this->fecha_actualizacion_bloque,
            $this->icono
        ]);
    }
    /**
     * Actualiza el bloque en la BD
     * @return void
     */
    public function ActualizarBloque(): void{
        $db = DB::conectar();
        $stmt = $db->prepare("UPDATE bloque SET id_categoria = ?,titulo = ?, descripcion = ?, texto = ?, orden = ?, fecha_actualizacion = ?, icono = ? WHERE id_bloque = ?");
        $stmt->execute([
            $this->id_categoria,
            $this->titulo_bloque,
            $this->descripcion_bloque,
            $this->texto_bloque,
            $this->orden_bloque,
            $this->fecha_actualizacion_bloque,
            $this->icono,
            $this->id_bloque
        ]);
    }
    /**
     * eliminar un bloque de la base de datos
     * @param $id_bloque int
     * @return void
     */
    public function EliminarBloque() :void{
        $db = DB::conectar();
        $contenidos = Contenido::getContenidoByBloque($this->id_bloque);
        foreach ($contenidos as $contenido) {
            $contenido->EliminarContenido();
        }
        $stmt = $db->prepare("DELETE FROM bloque WHERE id_bloque = ?");
        $stmt->execute([$this->id_bloque]);
        if (file_exists("/Medicas-del-Mundo/styles/img/". $this->icono) && $this->icono != "placeholder_bloque.png") {
            unlink($this->icono);
        }
    }

    /**
     * obtener todos los bloques de una categoría específica Ordenados por el campo 'orden' ascendente
     * @param $id_categoria int id de la categoría
     * @return array de objetos Bloque
     */
    public static function getBloquesByCategoria($id_categoria) :array{
        $db = DB::conectar();
        $stmt = $db->prepare("SELECT * FROM bloque WHERE id_categoria = ? ORDER BY orden ASC");
        $stmt->execute([$id_categoria]);
        $bloques = array();

        while ($bloque = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $bloque_pasar = new Bloque(
                $bloque['id_bloque'],
                $bloque['orden'],
                $bloque['titulo'],
                $bloque['descripcion'],
                $bloque['texto'],
                $bloque['fecha_actualizacion'],
                $bloque['id_categoria'],
                $bloque['icono']
            );
            $bloques[] = $bloque_pasar;
        }
        return $bloques;
    }

    /**
     * obtener un bloque específico por su ID
     * @param $id_bloque int ID del bloque
     * @return Bloque
     */
    public static function getBloqueById($id_bloque) :Bloque{
        $db = DB::conectar();
        $stmt = $db->prepare("SELECT * FROM bloque WHERE id_bloque = ? ");
        $stmt->execute([$id_bloque]);
        $bloque = $stmt->fetch(PDO::FETCH_ASSOC);
        return new Bloque(
            $bloque['id_bloque'],
            $bloque['orden'],
            $bloque['titulo'],
            $bloque['descripcion'],
            $bloque['texto'],
            $bloque['fecha_actualizacion'],
            $bloque['id_categoria'],
            $bloque['icono']
        );
    }
    public static function BuscarBloques(string $termino): array {
        $db = DB::conectar();
        // Buscamos coincidencias en el título, descripción o el texto del contenido
        $stmt = $db->prepare("SELECT * FROM bloque WHERE titulo LIKE ? OR descripcion LIKE ? OR texto LIKE ?");
        $busqueda = "%" . $termino . "%";
        $stmt->execute([$busqueda, $busqueda, $busqueda]);

        $bloques = array();
        try {
            while ($bloque = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $bloques[] = new Bloque(
                    $bloque['id_bloque'],
                    $bloque['orden'],
                    $bloque['titulo'],
                    $bloque['descripcion'],
                    $bloque['texto'],
                    $bloque['fecha_actualizacion'],
                    $bloque['id_categoria'],
                    $bloque['icono']
                );
            }
        } catch (PDOException $e) {
            error_log("Error en la búsqueda de bloques: " . $e->getMessage());
        }
        return $bloques;
    }

    public static function SiguienteId(){
        $db = DB::conectar();
        $stmt = $db->prepare("SELECT MAX(id_bloque) as id_bloque FROM bloque");
        $stmt->execute();
        $id_bloque = $stmt->fetch(PDO::FETCH_ASSOC);
        return $id_bloque['id_bloque'] + 1;
    }
    public static function SiguienteOrden($id_categoria){
        $db = DB::conectar();
        $stmt = $db->prepare("SELECT MAX(orden) as orden FROM bloque WHERE id_categoria = ?");
        $stmt->execute([$id_categoria]);
        $orden = $stmt->fetch(PDO::FETCH_ASSOC);
        return $orden['orden'] + 1;
    }

    /******************************** GETTER y SETTER *************************************/
    public function getIdBloque(){
        return $this->id_bloque;
    }
    public function getOrdenBloque(){
        return $this->orden_bloque;
    }
    public function setOrdenBloque($orden_bloque){
        $this->orden_bloque = $orden_bloque;
    }
    public function getTituloBloque(){
        return $this->titulo_bloque;
    }
    public function setTituloBloque($titulo_bloque){
        $this->titulo_bloque = $titulo_bloque;
    }
    public function getDescripcionBloque(){
        return $this->descripcion_bloque;
    }
    public function setDescripcionBloque($descripcion_bloque){
        $this->descripcion_bloque = $descripcion_bloque;
    }
    public function getTextoBloque(){
        return $this->texto_bloque;
    }
    public function setTextoBloque($texto_bloque){
        $this->texto_bloque = $texto_bloque;
    }
    public function getFechaActualizacionBloque(){
        return $this->fecha_actualizacion_bloque;
    }

    public function getIdCategoria(){
        return $this->id_categoria;
    }
    public function getIcono(){
        return $this->icono;
    }


}