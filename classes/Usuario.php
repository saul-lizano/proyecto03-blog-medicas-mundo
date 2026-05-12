<?php
// Clase Usuario: Maneja la autenticación y control de acceso de usuarios en el sistema.
// Incluye métodos para verificar roles y gestionar sesiones.
class Usuario
{
    // Propiedades privadas del usuario
    private string $nombre;
    private string $email;
    private string $password;
    private int $id_usuario;
    private string $rol;

    /**
     * @param $nombre
     * Nombre del usuario
     *
     * @param $email
     * Correo electrónico del usuario
     *
     * @param $password
     * Contraseña hasheada del usuario
     *
     * @param $id_usuario
     * Identificador único del usuario
     *
     * @param $rol
     * Rol del usuario (Admin, Editora, etc.)
     */

    // Constructor: Inicializa un objeto Usuario con los datos proporcionados
    public function __construct($nombre, $email, $password, $id_usuario, $rol)
    {
        $this->nombre = $nombre;
        $this->email = $email;
        $this->password = $password;
        $this->id_usuario = $id_usuario;
        $this->rol = $rol;
    }
    //************************GETTERS******************************//
    public function getNombre()
    {
        return $this->nombre;
    }
    public function getEmail()
    {
        return $this->email;
    }

    public function getIdUsuario(): int
    {
        return $this->id_usuario;
    }
    public function getRol(): string
    {
        return $this->rol;
    }
    public function getPassword(): string{
        return $this->password;
    }
    //**************************** SETTERS ***********************************//
    public function setNombre($nombre):void{
        $this->nombre = $nombre;
    }
    public function setEmail($email):void{
        $this->email = $email;
    }
    public function setPassword($password):void{
        $this->password = $password;
    }
    //********************** MÉTODOS *****************************//

    /**
     * verifica si el usuario actual es Administrador
     * @return bool true si el rol es "Admin", false en caso contrario
     */
    public function controlUsuarioAdmin() :bool
    {
        if ($this->rol == 'admin') {
            return true;
        } else {
            return false;
        }
    }

    /**
     * verifica si el usuario actual es Editora
     * @return bool true si el rol es "editora", false en caso contrario
     */
    public function controlUsuarioEditora() :bool
    {
        if ($this->rol == 'editora') {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Verifica credenciales de inicio sesion en la base de datos y te devuelve el Usuario si es válido
     * @param $nombre string nombre o correo
     * @param $password string
     * @return Usuario|null
     */
    public static function InicioSesion($email, $password) :Usuario|null
    {
        $db = DB::conectar();
        // Consulta para obtener usuario por email o nombre, uniendo con tabla rol
        $stmt = $db->prepare("SELECT u.email, u.password, u.nombre, r.id_rol, r.nombre_rol AS rol, u.id_usuario FROM usuario u LEFT JOIN rol r ON u.id_rol = r.id_rol WHERE email = ?");
        $stmt->execute([$email]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$usuario) {
            return null; // Usuario no encontrado
        }
        // Verificar contraseña hasheada
        if (password_verify($password, $usuario['password'])) {
            // Crear sesión con objeto Usuario
            return new Usuario($usuario['nombre'], $usuario['email'], $usuario['password'], $usuario['id_usuario'], $usuario['rol']);
        } else {
            return null; // Contraseña incorrecta
        }
    }

    /**
     * Devuelve todas las usuarias
     * @return array de objetos Usuario
     */
    public static function ListarUsuarias() :array{
        $usuarias = array ();
        $db = DB::conectar();
        $stmt = $db->prepare("SELECT u.*, r.id_rol, r.nombre_rol AS rol FROM usuario u JOIN rol r ON u.id_rol = r.id_rol ORDER BY u.nombre");
        $stmt->execute();
        while ($usuaria = $stmt->fetch(PDO::FETCH_ASSOC)){
            $usuarias[] = new Usuario($usuaria['nombre'],
                $usuaria['email'],
                $usuaria['password'],
                $usuaria['id_usuario'],
                $usuaria['rol']);
        }
        return $usuarias;
    }

    /**
     * Inserta una Editora en la BD
     * @return bool
     */
    public function InsertarEditora():bool{
        $db = DB::conectar();
        $stmt = $db->prepare("SELECT * FROM rol WHERE nombre_rol = ?");
        $stmt->execute([$this->rol]);
        $rol = $stmt->fetch(PDO::FETCH_ASSOC);
        $id_rol = $rol['id_rol'];
        $stmt = $db->prepare("INSERT INTO usuario(id_usuario, email, password, nombre, id_rol) VALUES ($this->id_usuario, '$this->email', '$this->password', '$this->nombre', $id_rol)");
        $result = $stmt->execute();
        return $result>0;
    }

    /**
     * Actualiza los datos de la usuaria en la BD
     * @return bool
     */
    public function ActualizarUsuaria():bool{
        $db = DB::conectar();
        $stmt = $db->prepare("UPDATE usuario SET nombre='$this->nombre', email='$this->email', password='$this->password' WHERE id_usuario=$this->id_usuario");
        $result = $stmt->execute();
        return $result>0;
    }

    /**
     * Devuelve el siguiente identificador de Usuario segun BD
     * @return int
     */
    public static function SiguienteId(): int{
        $db = DB::conectar();
        $stmt = $db->prepare("SELECT MAX(id_usuario) as id_usuario FROM usuario");
        $stmt->execute();
        $id_usuario = $stmt->fetch(PDO::FETCH_ASSOC);
        return $id_usuario['id_usuario'] + 1;
    }

    /** Devuelve el rol de la Editora
     * @return string
     */
    public static function RolEditora(): string
    {
        $db = DB::conectar();
        $stmt = $db->prepare("SELECT nombre_rol as rol FROM rol WHERE nombre_rol = ?");
        $stmt->execute(['editora']);
        $rol = $stmt->fetch(PDO::FETCH_ASSOC);
        return $rol['rol'];
    }
    /**
     * Elimina a una usuaria de la BD
     * @param $id_usuario int identificador de una usuaria
     * @return void
     */
    public static function EliminarUsuaria (int $id_usuario) :void{
        $db = DB::conectar();
        $stmt = $db->prepare("DELETE FROM usuario WHERE id_usuario = ?");
        $stmt->execute([$id_usuario]);
    }

    /**
     * Obtiene una usuario concreta de la BD
     * @param $id_usuario
     * @return Usuario
     */
    public static function getUsuaria($id_usuario) :Usuario{
        $db = DB::conectar();
        $stmt = $db->prepare("SELECT u.*, r.nombre_rol AS rol FROM usuario u JOIN rol r ON u.id_rol=r.id_rol WHERE id_usuario = ?");
        $stmt->execute([$id_usuario]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
        return new Usuario($usuario['nombre'], $usuario['email'], $usuario['password'], $usuario['id_usuario'], $usuario['rol']);
    }
}