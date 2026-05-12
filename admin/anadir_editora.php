<?php
// Incluir todas las clases necesarias para el funcionamiento del módulo
require_once "../classes/Usuario.php";
require_once "../classes/DB.php";
require_once "../classes/Contenido.php";
require_once "../classes/Categoria.php";
require_once "../classes/Faq.php";
require_once "../classes/Bloque.php";

session_start();
// CONTROL DE ACCESO ADMIN
require_once "../controladores/control_admin.php";

// Verificar si se envió un formulario por POST con los datos de la categoría
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST["nombre"], $_POST["email"], $_POST["password"], $_POST["repite_password"])) {
        $nombre = $_POST["nombre"];
        $email = $_POST["email"];
        if ($_POST['password'] == $_POST['repite_password']) {
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $editora = new Usuario($nombre, $email, $password, Usuario::SiguienteId(), Usuario::RolEditora());
            if ($editora->InsertarEditora()) {
                header("location: ver_editoras.php");
                exit();
            } else {
                $error = "Error al agregar editora";
            }
        } else {
            $error = "Las contraseñas no coinciden";
        }


}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="../styles/style.css">
    <title>Bienvenidas</title>
    <link rel="icon" type="../styles/img/logo.png" sizes="32x32" href="../styles/img/logo.png">
</head>
<body>
<?php require_once "../header.php"; ?>
<main>
    <?php if (isset($exito)) { ?>
        <article class="exito" style="color: green; padding: 10px;">
            <p><?php echo $exito; ?></p>
        </article>
    <?php } ?>

    <?php if (isset($error)) { ?>
        <article class="error" style="color: red; padding: 10px;">
            <p class="error-p"><?php echo $error; ?></p>
        </article>
    <?php } ?>

    <article class="anadir-categoria">
        <form action="anadir_editora.php" method="POST" class="form-anadir">

            <label for="nombre">Nombre: </label>
            <input type="text" id="nombre" name="nombre" required>

            <label for="email">Email: </label>
            <input type="email" id="email" name="email" required>

            <label for="password">Contraseña: </label>
            <input type="password" id="password" name="password" required>
            <label for="repite-password">Repite la contraseña: </label>
            <input type="password" id="repite-password" name="repite_password" required>

            <button type="submit">Añadir</button>
        </form>
    </article>
</main>
<!-- Pie de página con información de contacto de Médicos del Mundo -->
<?php require_once "../footer.php"; ?>
<a href="../admin/index.php" class="volver-inicio"><img src="../styles/img/casita.png" alt="regresa a inicio"></a>
</body>
</html>