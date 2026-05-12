<?php
// Incluir clases necesarias para usuario y base de datos
require_once "classes/Usuario.php";
require_once "classes/DB.php";

session_start();
?>
<!DOCTYPE html>
<!-- Página de inicio de sesión para usuarios -->
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="styles/style.css">
    <title>Bienvenidas</title>
    <link rel="icon" type="image/png" sizes="32x32" href="https://www.medicosdelmundo.org/app/themes/mdm/library/medias/favicon/favicon-32x32.png">
</head>
<body>
<?php
include_once "header.php";
?>
<!-- Contenido principal con formulario de login -->
    <main class="login-container">

        <!-- Mostrar mensaje de error si las credenciales son incorrectas -->
        <?php if (isset($error)){ ?>
        <article class="login-box">
            <article class="mensaje_error"><?php echo $error;?> </article>
        </article>
        <?php } ?>
        <!-- Formulario de inicio de sesión -->
        <form action="controladores/inicio_sesion.php" method="post">
         <h1>Inicio de sesión</h1>
            <article class="form-usuario">
                <label for="usuario">Email: </label>
                <input type="email" id="usuario" name="usuario" required>
            </article>
            <article class="form-password">
                <label for="password">Contraseña: </label>
                <input type="password" id="password" name="password" required>
            </article>
            <article class="boton-login">
                <button type="submit" class="input-login">LOGIN</button>
            </article>
        </form>
    </main>
<!-- Pie de página con información de contacto -->
    <?php require_once "footer.php"; ?>
<!-- Enlace para volver al inicio -->
    <a href="index.php" class="volver-inicio"><img src="styles/img/casita.png" alt="regresa a inicio"></a>
</body>
</html>
