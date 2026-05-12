<?php
// Clase para manejar la conexión a la base de datos MySQL usando PDO
class DB
{
    // Configuración de la base de datos: servidor, usuario, contraseña y nombre de la BD
    private static string $servername = "localhost";
    private static string $username  = "root";
    private static string $password = "";
    private static string $dbname = "mdm_db";

    /**
     * Devuelve la conexión a la BBDD
     * @return PDO
     */
    public static function conectar() {
            $conn = new PDO("mysql:host=".self::$servername.";dbname=". self::$dbname, self::$username, self::$password);
            // set the PDO error mode to exception
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        return $conn;
    }
}