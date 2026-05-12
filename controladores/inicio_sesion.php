<?php
require_once "../classes/Usuario.php";
require_once "../classes/DB.php";

session_start();
// Verificar si se envió el formulario de login
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["usuario"], $_POST["password"])) {
    $usuario = htmlspecialchars($_POST["usuario"]);
    $password = htmlspecialchars($_POST["password"]);
    // Intentar iniciar sesión con las credenciales proporcionadas
    $usuaria = Usuario::InicioSesion($usuario, $password);
    // Guarda la usuaria en SESSION['usuaria']
    if ($usuaria != null) {
        $_SESSION["usuaria"] = $usuaria;
        // Redirigir a la página según el rol del usuario
        if ($_SESSION['usuaria']->getRol() == "admin") {
            header("Location:../admin/index.php");
        } elseif ($_SESSION['usuaria']->getRol() == "editora") {
            header("Location:../editora/index.php");
        } else {
            header("Location: cerrar_sesion.php");
        }
        exit;
    } else{
        // Mostrar mensaje de error si las credenciales son incorrectas
        $error = "Credenciales incorrectas";
        header("Location: ../login.php?error=".$error);
        exit();
    }
} else{
    $error = "introduzca los datos";
    header("Location: ../login.php?error=".$error);
    exit();
}