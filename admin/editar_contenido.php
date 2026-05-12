<?php
// 1. IMPORTACIÓN DE DEPENDENCIAS
// Se incluyen las clases necesarias para trabajar con la base de datos y los objetos del dominio.
// 'require_once' asegura que el archivo se incluya solo una vez, evitando errores de re-declaración.
require_once "../classes/Usuario.php";
require_once "../classes/DB.php";
require_once "../classes/Contenido.php";
require_once "../classes/Categoria.php";
require_once "../classes/Faq.php";
require_once "../classes/Bloque.php";

// 2. INICIO DE SESIÓN
session_start();

// 3. CONTROL DE ACCESO ADMIN
require_once "../controladores/control_admin.php";

// 4. INICIALIZACIÓN DE VARIABLES
$error = null; // Guardará los mensajes de error si la actualización falla.
$bloque = null; // Guardará el objeto Bloque que vamos a editar.

// 5. CARGA DE DATOS (METODO GET)
// Si la URL contiene '?page=X', se busca ese bloque en la base de datos.
if (!empty($_GET['page'])) {
    $bloque = Bloque::getBloqueById($_GET['page']);
} else{
    header ("Location: index.php");
}

// 6. PROCESAMIENTO DEL FORMULARIO (METODO POST)
// Verifica si se ha enviado el formulario por POST y si la acción es "contenido".
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST["titulo"], $_POST["descripcion"], $_POST["texto"])) {
    try {
        // Se recogen todos los datos enviados por el usuario desde el formulario.
        // MEJORA: Faltaría sanear o validar estos datos (trim, filtros) antes de usarlos.
        $id_bloque = $_POST['id_bloque'];
        $titulo = $_POST['titulo'];
        $descripcion = $_POST['descripcion'];
        $texto = $_POST['texto'];
        $id_categoria = $_POST['id_categoria'];
        $fecha_actualizacion = date("Y-m-d H:i:s", time());
        $orden = $bloque->getOrdenBloque();
        //MANEJO DE LA IMAGEN SI SE VA A CAMBIAR
        $subida_icono = true;
        if (isset($_FILES['img']) && $_FILES['img']['error'] == 0) {
            //manejamos imagen, con nombre y ruta a guardar
            $icono = uniqid() . "_" . basename($_FILES['img']['name']);
            $target_dir = "../styles/img/";
            $target_file = $target_dir . $icono;
            if(!move_uploaded_file($_FILES['img']['tmp_name'], $target_file)) {
                $subida_icono = false;
                $error = "Error al subir el archivo";
            }
        } else {
            $icono = $bloque->getIcono();
        }
        // Se instancia un nuevo objeto Bloque con los datos actualizados.
        $bloqueEditado = new Bloque($id_bloque, $orden, $titulo, $descripcion, $texto, $fecha_actualizacion, $id_categoria, $icono);
        if ($subida_icono) {
            // Se llama al metodo que ejecuta la consulta UPDATE en la base de datos.
            $bloqueEditado->ActualizarBloque();
            // Si sale bien, redirige al índice de esa categoría.
            header("Location: index.php?page=" . $id_categoria);
            exit();
        }

    } catch (Exception $e){
        $error = $e->getMessage();
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
    <title>Bienvenidas - Editar Contenido</title>
    <link rel="icon" type="../styles/img/logo.png" sizes="32x32" href="../styles/img/logo.png">
</head>
<body>
<?php
// Inclusión de la cabecera (menú de navegación, logo, etc.)
require_once "../header.php";
?>
<main>
    <?php
    // 7. GESTIÓN DE ERRORES EN LA VISTA
    // Si la variable $error tiene contenido (hubo un fallo en el POST), se muestra al usuario.
    if (isset($error)) {
        ?>
        <article class="error">
            <p class="error-p"><?php echo htmlspecialchars($error); ?></p>
        </article>
        <?php
    }

    if ($bloque) {
    ?>
    <article class="anadir-categoria anadir-contenido-container">
        <form action="" method="post" class="form-anadir" enctype="multipart/form-data">
            <input type="hidden" name="orden" value="<?= $bloque->getOrdenBloque();?>>">
            <input type="hidden" name="id_bloque" value="<?php echo htmlspecialchars($bloque->getIdBloque()); ?>">

            <div class="anadir-contenido-columnas">
                <article class="anadir-contenido-izquierda">

                    <label for="titulo">Titulo: </label>
                    <input type="text" id="titulo" name="titulo" value="<?php echo htmlspecialchars($bloque->getTituloBloque()); ?>" required>

                    <label for="descripcion">Descripcion: </label>
                    <input type="text" id="descripcion" name="descripcion" value="<?php echo htmlspecialchars($bloque->getDescripcionBloque()); ?>" required>

                    <label for="img">Imagen ejemplo: </label>
                    <input type="file" id="img" name="img" accept="image/*">

                    <label for="id_categoria">Pertenece a la categoria: </label>
                    <select name="id_categoria" id="id_categoria" required>
                        <?php
                        $categoriaDelBloque = Categoria::getCategoriaById($bloque->getIdCategoria());
                        echo "<option value='" . $categoriaDelBloque->getIdCategoria() . "'>" . htmlspecialchars($categoriaDelBloque->getNombre()) . "</option>";

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
                    </select>
                    <button type="submit">Editar Contenido</button>
                </article>
                <article class="anadir-contenido-derecha">
                    <label for="texto">Texto: </label>
                    <textarea id="texto" name="texto" required><?php echo htmlspecialchars($bloque->getTextoBloque()); ?></textarea>
                </article>
            </div>
        </form>
    </article>
    <?php } else { ?>
        <article class="error">
            <p class="error-p">Contenido no encontrado. Por favor, selecciona un contenido para editar.</p>
        </article>
    <?php } ?>
</main>
<!-- Pie de página con información de contacto de Médicos del Mundo -->
<?php require_once "../footer.php"; ?>
<a href="../admin/index.php" class="volver-inicio"><img src="../styles/img/casita.png" alt="regresa a inicio"></a>
</body>
</html>