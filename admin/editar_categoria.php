<?php
require_once "../classes/Usuario.php";
require_once "../classes/DB.php";
require_once "../classes/Contenido.php";
require_once "../classes/Categoria.php";
require_once "../classes/Faq.php";
require_once "../classes/Bloque.php";

session_start();

// CONTROL ACCESO ADMIN
require_once "../controladores/control_admin.php";

$error = null;
$categoria_edit = null;

// Obtenemos el ID de la categoría por la URL.
// Revisamos si viene como 'id_categoria' o como 'page' (por compatibilidad con tus enlaces anteriores)
$id_buscar = $_GET['id_categoria'] ?? $_GET['page'] ?? null;

if ($id_buscar) {
    try {
        $categoria_edit = Categoria::getCategoriaById($id_buscar);
    } catch (Exception $e) {
        $error = "Error al buscar la categoría: " . $e->getMessage();
    }
} else {
    header ("location: index.php");
    exit();
}

// Lógica para actualizar la categoría al enviar el formulario
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST["action"], $_POST['id_madre']) && $_POST["action"] == "editar_categoria") {
    try {
        $id_categoria_post = $_POST['id_categoria'];
        $nombre = $_POST['nombre'];
        $descripcion = $_POST['descripcion'];
        $orden = $_POST['orden'];
        // Si no se selecciona categoría madre, lo dejamos en null
        if (!empty($_POST['id_madre'])){
            $id_madre = $_POST['id_madre'];
        } else{
            $id_madre = null;
        }
        $fecha_actualizacion = date("Y-m-d H:i:s", time());
        $imagen_subida = true;
        if (isset($_FILES['img']) && $_FILES['img']['error'] === UPLOAD_ERR_OK) {
            $img_cat = uniqid() . "_" . basename($_FILES['img']['name']);
            $target_dir = "../styles/img/";
            $target_file = $target_dir . $img_cat;
            if (!move_uploaded_file($_FILES['img']['tmp_name'], $target_file)) {
                $imagen_subida = false;
            }
        } else{
            $img_cat = $categoria_edit->getImg();
        }
        if ($imagen_subida) {
            // Instanciamos la categoría con los datos editados
            $categoriaEditada = new Categoria($id_categoria_post, $nombre, $descripcion, $orden, $img_cat, $id_madre, $fecha_actualizacion);

            // Llamamos al metodo que ejecuta el UPDATE en la BD
            $categoriaEditada->ActualizarCategoria();
            // Redirigimos al inicio de admin
            header("Location: index.php");
            exit();
        } else {
            $error = "No se pudo subir la imagen";
        }

    } catch (Exception $e) {
        $error = "Error al actualizar";
    }
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="../styles/style.css">
    <title>Bienvenidas - Editar Categoría</title>
    <link rel="icon" type="image/png" sizes="32x32" href="../styles/img/logo.png">
</head>

<body>
<?php
require_once "../header.php";
?>
<main>
    <?php
    if (isset($_GET['page'])): ?>
        <section class="titulo-section">
            <a href="index.php?page=<?= $_GET['page'];?>" class="faq-variable">⮌ Volver atrás</a>
            <h1 class="faq-titulo-categoria"><?= Categoria::getCategoriaById($_GET['page'])->getNombre();?></h1>
            <h1 class="titulo-page">Preguntas Frecuentes</h1>
        </section>
    <?php endif;

    if (isset($error)) {
        ?>
        <article class="error">
            <p class="error-p"><?php echo htmlspecialchars($error); ?></p>
        </article>
        <?php
    }

    if ($categoria_edit) {
        ?>
        <article class="anadir-categoria" style="margin-top: 1px;">
            <form action="" method="post" class="form-anadir" enctype="multipart/form-data">
                <input type="hidden" name="action" value="editar_categoria">
                <input type="hidden" name="id_categoria"
                       value="<?php echo htmlspecialchars($categoria_edit->getIdCategoria()); ?>">

                <label for="nombre">Nombre: </label>
                <input type="text" id="nombre" name="nombre"
                       value="<?php echo htmlspecialchars($categoria_edit->getNombre()); ?>" required>

                <label for="descripcion">Descripción: </label>
                <input type="text" id="descripcion" name="descripcion"
                       value="<?php echo htmlspecialchars($categoria_edit->getDescripcion()); ?>" required>

                <input type="hidden" id="orden" name="orden"
                       value="<?php echo htmlspecialchars($categoria_edit->getOrden()); ?>">

                <label for="img">Imagen: </label>
                <input type="file" id="img" name="img" accept="image/*">

                <label for="id_madre">Pertenece a la categoría: </label>
                <select name="id_madre" id="id_madre">
                    <?php
                    if ($categoria_edit->getIdMadre()) {
                        $categoriaDelBloque = Categoria::getCategoriaById($categoria_edit->getIdMadre());
                        echo "<option value='" . $categoriaDelBloque->getIdCategoria() . "'>" . htmlspecialchars($categoriaDelBloque->getNombre()) . "</option>";
                    } else {
                        echo "<option value=''>Ninguna</option>";
                    }
                    // Carga dinámica de categorías desde la BD
                    $categorias = Categoria::getCategorias();
                    foreach ($categorias as $categoria) {
                        echo "<option value='" . $categoria->getIdCategoria() ."'>Categoria: " . htmlspecialchars($categoria->getNombre()) . "</option>";
                        $subcategorias = Categoria::getSubCategorias($categoria->getIdCategoria());
                        foreach ($subcategorias as $subcategoria) {
                            echo "<option value='" . $subcategoria->getIdCategoria() ."'>Subcategoria: " . htmlspecialchars($subcategoria->getNombre()) . "</option>";

                        }
                    }
                    ?>
                </select><br>

                <button type="submit">Guardar Cambios</button>
            </form>
        </article>
    <?php } else { ?>
        <article class="error">
            <p class="error-p">Categoría no encontrada. Por favor, selecciona una categoría para editar desde el panel de administración.</p>
        </article>
    <?php } ?>
</main>
<!-- Pie de página con información de contacto de Médicos del Mundo -->
<?php require_once "../footer.php"; ?>
<a href="../admin/index.php" class="volver-inicio"><img src="../styles/img/casita.png" alt="regresa a inicio"></a>
</body>

</html>
