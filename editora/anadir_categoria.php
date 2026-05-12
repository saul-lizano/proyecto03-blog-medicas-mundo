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
require_once "../controladores/control_editora.php";

// Verificar si se envió un formulario por POST con los datos de la categoría
if ($_SERVER['REQUEST_METHOD'] == 'POST'
        && isset($_POST["nombre"], $_POST["descripcion"], $_POST['categoria'])) {
    $imagen_subida = true;
    $fecha = date("Y-m-d H:i:s", time());
    // 1. MANEJO DE LA IMAGEN CON $_FILES
    if (isset($_FILES['img']) && $_FILES['img']['error'] === UPLOAD_ERR_OK) {
        $imagen = uniqid() . "_" . basename($_FILES['img']['name']);
        $target_dir = "../styles/img/";
        $target_file = $target_dir . $imagen;

        if (!move_uploaded_file($_FILES['img']['tmp_name'], $target_file)) {
            $imagen_subida = false;
        }
    } else {
        $imagen = "placeholder_categoria.jpg";
    }
    if ($_POST['categoria'] != null) {
        $orden_cat = Categoria::SiguienteOrden($_POST["categoria"]);
        $id_madre = $_POST["categoria"];
    } else{
        $id_madre = null;
        $orden_cat = Categoria::SiguienteOrden($id_madre);
    }
    $categoria = new Categoria(Categoria::SiguienteId(), $_POST["nombre"], $_POST["descripcion"], $orden_cat, $imagen, $id_madre, $fecha);
    if ($imagen_subida) {
        try {
            $categoria->InsertarCategoria();
            header ("location: index.php?page=". $categoria->getIdCategoria());
            exit();
        } catch (Exception $e) {
            // Si ocurre un error, capturarlo y almacenarlo en la variable $error
            $error = "No se pudo añadir la categoría";
        }
    } else{
        $error = "No se pudo subir la imagen";
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
        <form action="" method="post" class="form-anadir" enctype="multipart/form-data">
            <input type="hidden" name="categoria" value="<?= $_GET['page']; ?>" >

            <label for="nombre">Nombre: </label>
            <input type="text" id="nombre" name="nombre" required>

            <label for="descripcion">Descripcion: </label>
            <input type="text" id="descripcion" name="descripcion" required>

            <label for="img">Imagen: </label>
            <input type="file" id="img" name="img" accept="image/*">

            <button type="submit">Añadir</button>
        </form>
    </article>
</main>
<!-- Pie de página con información de contacto de Médicos del Mundo -->
<?php require_once "../footer.php"; ?>
<a href="../admin/index.php" class="volver-inicio"><img src="../styles/img/casita.png" alt="regresa a inicio"></a>
</body>
</html>