<?php
// Clase Rol: Representa los roles de usuario en el sistema (Admin, Editora, etc.).
// Proporciona métodos para obtener roles y generar opciones para formularios.
class Rol {
    // Propiedades privadas del rol
    private $id_rol;
    private $nombre;
    /**
     * @param $id_rol
     * Identificador unico del rol
     *
     * @param $nombre
     * Nombre del rol
     */
    // Constructor: Inicializa un objeto Rol con id y nombre
    public function __construct($id_rol,$nombre){
        $this->id_rol=$id_rol;
        $this->nombre=$nombre;
    }

    // Getters para acceder a las propiedades
    public function getTdRol(){
        return $this->id_rol;
    }
    public function getNombre(){
        return $this->nombre;
    }

    // Setters para modificar las propiedades
    public function setIdRol($id_rol){
    $this->id_rol=$id_rol;
    }
    public function setNombre($nombre){
        $this->nombre=$nombre;
    }

    // Metodo estático para obtener todos los roles y generar opciones HTML para select
    // Imprime directamente <option> tags, no retorna array
    public static function getRoles(){
        $db = DB::conectar();
        $stmt = $db->prepare("SELECT id_rol,nombre FROM rol");
        $stmt->execute();
        while ($consultaRol = $stmt->fetch(PDO::FETCH_ASSOC)){
            echo "<option value='" . $consultaRol['id_rol'] . "'>" . $consultaRol['nombre'] . "</option>";
        }
    }

    // Metodo estático para obtener un rol por su ID
    // Retorna un objeto Rol
    public static function getRolById($id_rol){
        $db = DB::conectar();
        $stmt = $db->prepare("SELECT id_rol,nombre FROM rol WHERE id_rol = ?");
        $stmt->execute([$id_rol]);
        $consultaRol = $stmt->fetch(PDO::FETCH_ASSOC);
        return new Rol(
            $consultaRol['id_rol'],
            $consultaRol['nombre']
        );
    }

    // Metodo estático para obtener un rol por su nombre
    // Retorna un objeto Rol
    public static function getRolByNombre($nombre){
        $db = DB::conectar();
        $stmt = $db->prepare("SELECT id_rol,nombre FROM rol WHERE nombre = ?");
        $stmt->execute([$nombre]);
        $consultaRol = $stmt->fetch(PDO::FETCH_ASSOC);
        return new Rol(
            $consultaRol['id_rol'],
            $consultaRol['nombre']
        );
    }
}   
