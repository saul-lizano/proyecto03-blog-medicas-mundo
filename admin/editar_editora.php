<?php
// 1. IMPORTACIÓN DE DEPENDENCIAS
// Se incluyen las clases necesarias para trabajar con la base de datos y los objetos del dominio.
require_once "../classes/Usuario.php";
require_once "../classes/DB.php";
require_once "../classes/Contenido.php";
require_once "../classes/Categoria.php";
require_once "../classes/Faq.php";
require_once "../classes/Bloque.php";

// 2. INICIO DE SESIÓN
// Fundamental para poder acceder a las variables globales $_SESSION y saber quién está navegando.
session_start();

// 3. CONTROL DE ACCESO ADMIN
require_once "../controladores/control_admin.php";
if (!isset($_GET['page'])) {
header ("location: index.php");
exit();
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $error = false;
    $usuaria = Usuario::getUsuaria($_GET['page']);
    if (!empty($_POST['nombre'])) {
        $usuaria->setNombre($_POST['nombre']);
    }
    if (!empty($_POST['email'])) {
        $usuaria->setEmail($_POST['email']);
    }
    if (!empty($_POST['password']) && !empty($_POST['repitePassword'])) {
        if ($_POST['repitePassword'] == $_POST['password']) {
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $usuaria->setPassword($password);
        } else {
            $error = "Contraseñas no coinciden";
        }
    }
    if (!$error) {
        $usuaria->ActualizarUsuaria();
    }
}
// 4. INICIALIZACIÓN DE VARIABLES
$error = null; // Guardará los mensajes de error si la actualización falla.
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="../styles/style.css">
    <title>Bienvenidas - Editar Contenido</title>
    <link rel="icon" type="../styles/img/logo.png" sizes="32x32" href="../styles/img/logo.png">
</head>
<body>
<?php
// Inclusión de la cabecera
require_once "../header.php";
?>
<main style="justify-content: center; text-align: center;">
    <?php
    if (isset($error)) {
        ?>
        <article class="error">
            <p class="error-p"><?php echo htmlspecialchars($error); ?></p>
        </article>
        <?php
    }
    ?>
    <article class="anadir-contenido">
        <form action="" method="post" class="form-anadir">
                <?php
                $usuaria = Usuario::getUsuaria($_GET["page"]);
                ?>

            <label for="nombre"><?php echo $usuaria->getRol().": ". $usuaria->getNombre(); ?> </label>
            <input type="text" id="nombre" name="nombre" placeholder="Cambiar Nombre">

            <label for="email">Email: <?php echo $usuaria->getEmail();?> </label>
            <input type="email" id="email" name="email" placeholder="Cambiar Email">

            <label for="password" style="color: black">Cambiar contraseña: </label>
            <input type="password" id="password" name="password" placeholder="Introduzca nueva contraseña">
            <input type="password" id="password" name="repitePassword" placeholder="Repite la contraseña">
            <button type="submit">Editar usuaria</button>
        </form>
    </article>
</main>
<!-- Pie de página con información de contacto de Médicos del Mundo -->
<?php require_once "../footer.php"; ?>
<a href="../admin/index.php" class="volver-inicio"><img src="../styles/img/casita.png" alt="regresa a inicio"></a>
</body>
</html>