<?php
// Clase Contenido: Representa contenido extra (enlaces, URLs) asociados a bloques de contenido.
// Actualmente, solo implementa la recuperación de contenidos por bloque; otros métodos son stubs.
class Contenido{
    private $id_extra;
    private $url;
    private $descripcion;
    private $id_bloque;
    private $fecha_actualizacion;
    private $tipo;

    /**
     * @param $id_extra
     * @param $url
     * @param $descripcion
     * @param $id_bloque
     * @param $fecha_actualizacion
     * @param $tipo
     */
    public function __construct($id_extra, $id_bloque, $descripcion, $url, $fecha_actualizacion, $tipo)
    {
        $this->id_extra = $id_extra;
        $this->url = $url;
        $this->descripcion = $descripcion;
        $this->id_bloque = $id_bloque;
        $this->fecha_actualizacion = $fecha_actualizacion;
        $this->tipo = $tipo;
    }

    /************************* GETTERS y SETTERS ****************************************/
    /************************************************************************************/

    public function getIdExtra()
    {
        return $this->id_extra;
    }

    public function getUrl()
    {
        return $this->url;
    }

    public function getDescripcion()
    {
        return $this->descripcion;
    }

    public function getIdBloque()
    {
        return $this->id_bloque;
    }

    public function getFechaActualizacion()
    {
        return $this->fecha_actualizacion;
    }

    public function getTipo(){
        return $this->tipo;
    }

    public function setIdExtra($id_extra)
    {
        $this->id_extra = $id_extra;
    }

    public function setUrl($url)
    {
        $this->url = $url;
    }

    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;
    }

    public function setIdBloque($id_bloque)
    {
        $this->id_bloque = $id_bloque;
    }

    public function setFechaActualizacion($fecha_actualizacion)
    {
        $this->fecha_actualizacion = $fecha_actualizacion;
    }

    /*********************************** METODOS ****************************************/
    /************************************************************************************/

    /**
     * Añade un nuevo contenido a la BD
     * @return void
     */
    public function InsertarContenido(): void
    {
        $db = DB::conectar();
        $stmt = $db->prepare("INSERT INTO extra(id_extra, id_bloque, descripcion, url, fecha_actualizacion, tipo) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$this->id_extra, $this->id_bloque, $this->descripcion, $this->url, $this->fecha_actualizacion, $this->tipo]);
    }

    /**
     * Actualiza un contenido de la BD
     * @return void
     */
    public function ActualizarContenido(): void
    {
        $db = DB::conectar();
        $stmt = $db->prepare("UPDATE extra SET id_bloque = ?, descripcion = ?, url = ?, fecha_actualizacion = ? WHERE id_extra = ? ");
        $stmt->execute([$this->id_bloque, $this->descripcion, $this->url, $this->fecha_actualizacion, $this->id_extra]);
    }

    /**
     * Eliminar un contenido de la BD
     * @return void
     */
    public function EliminarContenido(): void
    {
        $db = DB::conectar();
        if ($this->tipo == "imagen"){
            if (file_exists("../styles/img/". $this->url)){
                unlink("../styles/img/". $this->url);
            }
        }
        $stmt = $db->prepare("DELETE FROM extra WHERE id_extra = ?");
        $stmt->execute([$this->id_extra]);
    }

    /**
     * Devuelve un array con los Contenidos pertenecientes a un Bloque
     * @param $id_bloque int identificador del Bloque al que pertenece el contenido
     * @return array
     */
    public static function getContenidoByBloque($id_bloque) :array{
        $db = DB::conectar();
        $stmt = $db->prepare("SELECT * FROM extra WHERE id_bloque = ? ");
        $stmt->execute([$id_bloque]);
        $contenidos = array();
        while ($consultaContenido = $stmt->fetch(PDO::FETCH_ASSOC)){
            $contenido = new Contenido(
                $consultaContenido['id_extra'],
                $consultaContenido['id_bloque'],
                $consultaContenido['descripcion'],
                $consultaContenido['url'],
                $consultaContenido['fecha_actualizacion'],
                $consultaContenido['tipo']
            );
            $contenidos[] = $contenido;
        }
        return $contenidos;
    }

    /**
     * Devuelve el contenido de la BD segun su id
     * @param $id_contenido int
     * @return Contenido
     */
    public static function getContenidoById($id_contenido) :Contenido{
        $db = DB::conectar();
        $stmt = $db->prepare("SELECT * FROM extra WHERE id_extra = ?");
        $stmt->execute([$id_contenido]);
        $nuevoContenido = $stmt->fetch(PDO::FETCH_ASSOC);
        return new Contenido(
            $nuevoContenido['id_extra'],
            $nuevoContenido['id_bloque'],
            $nuevoContenido['descripcion'],
            $nuevoContenido['url'],
            $nuevoContenido['fecha_actualizacion'],
            $nuevoContenido['tipo']
        );
    }

    /**
     * Obtiene el id para insertar en la BD
     *
     * @return int
     */
    public static function SiguienteId() :int
    {
        $db = DB::conectar();
        $stmt = $db->prepare("SELECT MAX(id_extra) as id_extra FROM extra");
        $stmt->execute();
        $id_extra = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($id_extra){
            return $id_extra['id_extra'] + 1;
        } else {
            return 1;
        }
    }

    /**
     * Para controlar el tipo añadido a la BD en un futuro
     * @return string
     */
    public static function Imagen() :string
    {
        return "imagen";
    }

    /**
     * Para controlar el tipo añadido a la BD en un futuro
     * @return string
     */
    public static function Enlace() :string
    {
        return "enlace";
    }
}
