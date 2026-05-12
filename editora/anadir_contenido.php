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
// Si no tiene permisos, redirige a la página principal
require_once "../controladores/control_editora.php";
if (!isset($_GET['page'])){
    header("location: index.php");
    exit();
}
// Verificar si se envió un formulario por POST con los datos del contenido
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST["categoria"], $_POST['titulo'], $_POST["descripcion"], $_POST['texto'])) {
    $titulo = $_POST["titulo"];
    $descripcion = $_POST["descripcion"];
    $fecha = date("Y-m-d H:i:s", time());
    //manejamos imagen, con nombre y ruta a guardar
    $imagen_subida = true;
    if (isset($_FILES['img']) && $_FILES['img']['error'] === UPLOAD_ERR_OK) {
        $imagen = uniqid() . "_" . basename($_FILES['img']['name']);
        $target_dir = "../styles/img/";
        $target_file = $target_dir . $imagen;
        if (!move_uploaded_file($_FILES['img']['tmp_name'], $target_file)) {
            $imagen_subida = false;
        }
    } else {
        $imagen = "placeholder_bloque.png";
    }
    // Crear una nueva instancia de Bloque con los datos del formulario
    $bloque = new Bloque (Bloque::SiguienteId(), Bloque::SiguienteOrden($_POST['categoria']), $titulo, $descripcion, $_POST['texto'], $fecha, $_POST['categoria'], $imagen);
    try {
        if ($imagen_subida) {
            // Intentar insertar el contenido en la base de datos
            $bloque->InsertarBloque();
            header("location: index.php?page=". $bloque->getIdCategoria());
            exit();
        } else {
            $error = "No se pudo subir la imagen";
        }
    } catch (Exception $e){
        // Si ocurre un error, capturarlo y almacenarlo en la variable $error
        $error = "Ha ocurrido un error";
    }
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <!-- Definir codificación de caracteres UTF-8 -->
    <meta charset="UTF-8">
    <!-- Configuración de viewport para responsividad -->
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <!-- Compatibilidad con versiones antiguas de Internet Explorer -->
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <!-- Incluir CSS personalizado -->
    <link rel="stylesheet" href="../styles/style.css">
    <title>Bienvenidas</title>
    <!-- Favicon de la página -->
    <link rel="icon" type="../styles/img/logo.png" sizes="32x32" href="../styles/img/logo.png">
</head>
<body>
<!-- Encabezado con navegación -->
<?php
require_once "../header.php";
?>
<!-- Contenido principal -->
<main>
    <!-- Mostrar mensaje de error si existe -->
    <?php
    if (isset($error)) {
        ?>
        <article class="error">
            <p class="error-p"><?php echo $error; ?></p>
        </article>
        <?php
    }
    ?>
    <!-- Formulario para agregar una nueva categoría más simple -->
    <article class="anadir-categoria anadir-contenido-container">
        <form action="" method="post" class="form-anadir" enctype="multipart/form-data">
            <!-- Campo oculto para identificar la acción como 'categoria' -->
            <input type="hidden" name="categoria" value="<?= $_GET['page']; ?>">
            <div class="anadir-contenido-columnas">
                <article class="anadir-contenido-izquierda">
            <!-- Campo de título -->
                <label for="titulo">Titulo: </label>
                <input type="text" id="titulo" name="titulo" required>
                <label for="img">Imagen ejemplo: </label>
                <input type="file" id="img" name="img" accept="image/*">
                <!-- Campo de descripción -->
                <label for="descripcion">Descripcion: </label>
                <input type="text" id="descripcion" name="descripcion" required>
                    <!-- Botón para enviar el formulario -->
                <button type="submit" class="btn-anadir-contenido">Añadir</button>
            </article>
            <article class="anadir-contenido-derecha">
            <!-- Campo de texto/contenido principal -->
                <label for="texto">Texto: </label>
                <textarea id="texto" name="texto" required></textarea>
            </article>
            </div>
        </form>
    </article>
</main>
<!-- Procesamiento de formularios POST después de mostrar la página -->
<?php
// Verificar si se envió un formulario por POST
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action'])) {
    // Si la acción es 'contenido', crear un nuevo bloque
    if ($_POST['action'] === 'contenido') {
        // Crear instancia de Bloque con los datos del formulario
        $bloque = new Bloque(null, $_POST['prioridad'], $_POST['titulo'], $_POST['descripcion'], $_POST['texto'], null, $_POST['fecha_actualizacion'], $_POST['id_categoria']);
        // Guardar el bloque en la base de datos
        $bloque->CrearBloque();
        // Si la acción es 'categoria', crear una nueva categoría
    } elseif ($_POST['action'] === 'categoria') {
        // Crear instancia de Categoria con los datos del formulario
        $categoria = new Categoria(null, $_POST['nombre'], '', 0, '', null, date("Y-m-d H:i:s"));
        // Guardar la categoría en la base de datos
        $categoria->InsertarCategoria();
    }
}
?>
<!-- Pie de página con información de contacto -->
<!-- Pie de página con información de contacto de Médicos del Mundo -->
<?php require_once "../footer.php"; ?>
<!-- Enlace a inicio de sesión -->
<a href="../admin/index.php" class="volver-inicio"><img src="../styles/img/casita.png" alt="regresa a inicio"></a>
</body>
</html>
