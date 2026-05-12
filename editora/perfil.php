<?php
// Incluir las clases necesarias para acceder a la base de datos y gestionar usuarios
require_once "../classes/Usuario.php";
require_once "../classes/DB.php";
require_once "../classes/Contenido.php";
require_once "../classes/Categoria.php";
require_once "../classes/Faq.php";
require_once "../classes/Bloque.php";
// Iniciar la sesión para acceder a datos de usuario
session_start();
// CONTROL DE ACCESO ADMIN
// Si no tiene permisos, redirige a la página principal
require_once "../controladores/control_editora.php";

if($_SERVER["REQUEST_METHOD"] == "POST"){
    if (isset($_POST["email"])){
        $_SESSION['usuaria']->setEmail($_POST["email"]);
        $_SESSION['usuaria']->ActualizarUsuaria();
    } elseif(isset($_POST["nueva_password"]) && isset($_POST["repite_password"]) && isset($_POST["vieja_password"])){
        if (password_verify($_POST['vieja_password'], $_SESSION['usuaria']->getPassword())){
            if($_POST["nueva_password"] == $_POST["repite_password"]){
                $password = password_hash($_POST["nueva_password"], PASSWORD_DEFAULT);
                $_SESSION['usuaria']->setPassword($password);
                $_SESSION['usuaria']->ActualizarUsuaria();
            } else {
                $error= "Las contraseñas no coinciden";
            }
        } else {
            $error = "Contraseña incorrecta";
        }
    } else {
        $error = "Ingresa todos los campos";
    }

}
?>
<!doctype html>
<html lang="es">
<head>
    <!-- Definir codificación de caracteres UTF-8 -->
    <meta charset="UTF-8">
    <!-- Configuración de viewport para responsividad -->
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <!-- Compatibilidad con versiones antiguas de Internet Explorer -->
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <!-- Incluir CSS personalizado -->
    <link rel="stylesheet" href="../styles/style.css">
    <title>Bienvenidas</title>
    <!-- Favicon de la página -->
    <link rel="icon" type="image/png" sizes="32x32" href="https://www.medicosdelmundo.org/app/themes/mdm/library/medias/favicon/favicon-32x32.png">
</head>
<body>
<!-- Encabezado con navegación -->
<?php
require_once "../header.php";
?>
<!-- Contenido principal -->
<main>
    <?php if (isset($error)): echo "<p>". $error ."</p>"; endif; ?>
    <!-- Tabla con lista de editoras registradas  -->
    <section class="lista-editoras" style="display: flex; justify-content: center; align-items: flex-start; gap: 5vw;">
        <article class="" style="width: 50%; margin-top: 2%; display: inline-block; vertical-align: top;">
            <table>
                <!-- Encabezados de la tabla -->
                <thead class="cabecera-tabla">
                <tr class="cabecera-tabla-tr">
                    <th class="cabecera-tabla-tr-th">Editora</th>
                    <th class="cabecera-tabla-tr-th" colspan="2">Email</th>
                </tr>
                </thead>
                <!-- Cuerpo de la tabla con datos de editoras -->
                <tbody>
                <!-- Mostramos los datos de la editora -->
                <tr class="cuerpo-tabla-tr">
                    <!-- Nombre de la editora -->
                    <td class="cuerpo-tabla-tr-td">
                    <?php echo $_SESSION['usuaria']->getNombre(); ?></td>
                    <!-- Email de la editora -->
                    <td class="cuerpo-tabla-tr-td">
                    <?php echo $_SESSION['usuaria']->getEmail(); ?>
                    </td>
                    <td>
                        <form method="POST" action="">
                            <article>
                                <label for="email">nuevo correo: </label>
                                <input type="email" id="email" name="email">
                            </article>
                            <input type="submit" value="enviar">
                        </form>
                    </td>
                </tr>
                </tbody>
            </table>
        </article>
        <article class="" style="width: 20%; margin-top: 2%; display: inline-block; vertical-align: top;">
            <form method="POST" action="" class="form-anadir">
                <h2>Cambiar Contraseña</h2>
                <label for="vieja_password">Contraseña actual: </label>
                <input type="password" name="vieja_password" id="vieja_password" required>

                <label for="nueva_password">Nueva contraseña: </label>
                <input type="password" name="nueva_password" id="nueva_password" required>

                <label for="repite_password">Repita la contraseña</label>
                <input type="password" name="repite_password" id="repite_password" required>

                <button type="submit">Cambiar contraseña</button>
            </form>
        </article>
    </section>

    <a href='index.php' class='volver-inicio'><img src='../styles/img/casita.png' alt='regresa a inicio'></a>
</main>
<!-- Pie de página con información de contacto de Médicos del Mundo -->
<?php require_once "../footer.php"; ?>
</body>
</html>