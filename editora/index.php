<?php
// Incluir las clases necesarias para gestionar categorías, bloques y base de datos
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

/** COMPROBAMOS SI
            *SE HA BUSCADO CON 'buscar'
            *SE HA ENTRADO EN UNA CATEGORIA con 'page'
            *ESTAS EN INICIO CON CATEGORIAS INICIALES
 */
if (!empty($_GET["page"]) && is_numeric($_GET["page"])) {
    $categoria_actual = Categoria::getCategoriaById($_GET['page']);
    $categorias = Categoria::getSubcategorias($_GET['page']);
    $contenidos = Bloque::getBloquesByCategoria($_GET['page']);
} elseif (!empty($_GET["buscar"])) {
    $buscar = $_GET['buscar'];
    $categorias = Categoria::BuscarCategorias($buscar);
    $contenidos = Bloque::BuscarBloques($buscar);
} else {
    $categorias = Categoria::getCategorias();
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
    <!-- ***************** Mostrar categorias y bloques ********************* -->
    <?php
    try {
    //************** Encabezado para navegacion y mostrar INFO de la pagina actual + enlace a FAQ ************//
    if (isset($categoria_actual)){ ?>
        <section class="titulo-section"><a href="index.php<?= $categoria_actual->getIdMadre() ? "?page=".$categoria_actual->getIdMadre(): null; ?>">
                ⮌ Volver atrás</a>
            <h1 class="titulo-page"><?= $categoria_actual->getNombre(); ?></h1>
            <a href="FaQ.php?categoria=<?= $_GET['page'];?>" class="faq-variable">
                <span class="faq-link">?</span>
                <span class="faq-link-hover">Preguntas Frecuentes</span>
            </a></section>
        <?php
    } elseif (!empty($_GET['buscar'])) { ?>
        <section class="titulo-section">
            <a href="index.php" class="faq-variable">
                <span class="">Volver a Inicio</span>
            </a>
            <h1 class="titulo-page">Buscando: <?= $buscar;?></h1>
        </section>
    <?php } ?>

    <!-- ********** Contenido principal: muestra categorías o subcategorías y bloques de contenido ***************
         ********** según el parámetro 'page' o el parametro 'buscar' ******************************************** -->

    <?php
    // Manejo de excepciones para errores de base de datos
            echo "<section class='contenedor'>";

            // Iterar sobre cada categoría raíz y mostrarla
            foreach ($categorias as $categoria) {
                ?>
                <section class="categoria">
                    <!-- Botón para editar la categoría -->
                    <a href="editar_categoria.php?page=<?php echo $categoria->getIdcategoria(); ?>" class="boton-editar"><img src="../styles/img/lapiz.png" alt="Editar" class="boton-editar-img"></a>
                    <!-- Botón para eliminar la categoría -->
                    <a href="/Medicas-del-Mundo/controladores/eliminar_categoria.php?categoria=<?php echo $categoria->getIdcategoria(); ?>" class="boton-eliminar" onclick="return confirm('¿Estás segura de eliminar esta Categoría?');"><img src="../styles/img/basura.png" alt="Eliminar" class="boton-eliminar-img"></a>
                    <!-- Enlace para ver subcategorías dentro de esta categoría -->
                    <a class="enlace-bloque" href="index.php?page=<?php echo $categoria->getIdcategoria(); ?>">
                        <!-- Imagen de la categoría -->
                        <article class="imagen-categoria">
                            <img src="<?php echo "../styles/img/".$categoria->getImg(); ?>" alt="Imagen1">
                        </article>
                        <!-- Nombre y descripción de la categoría -->
                        <article class="testo-categoria">
                            <h1><?php echo $categoria->getNombre(); ?></h1>
                            <p><?php echo $categoria->getDescripcion(); ?></p>
                        </article>
                    </a>
                </section>
            <?php } ?>
            <!-- Botón flotante para crear una nueva categoría -->
            <section class="crear-categoria">
                <a class="enlace-crear-categoria" href="anadir_categoria.php?page=<?= $_GET['page'] ?? null; ?>">
                    <article class="testo-crear-categoria tamaño-variable">
                        <h1>+</h1>
                        <h3>Añadir Categoria</h3>
                    </article>
                </a>
            </section>
    <?php
    echo "</section>";
    //mostrar los bloques de contenido de esa categoría
    if (isset($_GET['page']) || isset($_GET['buscar'])) {
        // Iniciar contenedor para los bloques
        echo "<section class='contenedor'>";
        // Iterar sobre cada bloque y mostrarlo
        foreach ($contenidos as $contenido) { ?>
            <section class="contenido-bloque">
                <!-- Botón para editar el contenido -->
                <a href="editar_contenido.php?page=<?php echo $contenido->getIdBloque(); ?>" class="boton-editar"><img src="../styles/img/lapiz.png" alt="Editar" class="boton-editar-img"></a>
                <!-- Botón para eliminar el contenido -->
                <a href="/Medicas-del-Mundo/controladores/eliminar_categoria.php?contenido=<?php echo $contenido->getIdBloque(); ?>" class="boton-eliminar" onclick="return confirm('¿Estás segura de eliminar este Contenido?');"><img src="../styles/img/basura.png" alt="Eliminar" class="boton-eliminar-img"></a>
                <!-- Enlace para ver el contenido completo del bloque -->
                <a class="enlace-bloque" href="content.php?page=<?php echo $contenido->getIdBloque(); ?>">
                    <!-- Imagen asociada al bloque (actualmente vacía) -->
                    <article class="imagen-contenido">
                        <img src="../styles/img/<?php  echo $contenido->getIcono(); ?>" alt="Imagen1">
                    </article>
                    <!-- Título y descripción del bloque -->
                    <article class="testo-contenido">
                        <h1><?php echo $contenido->getTituloBloque(); ?></h1>
                        <p><?php echo $contenido->getDescripcionBloque(); ?></p>
                    </article>
                </a>
            </section>

            <?php
        }
    }

    if (isset($_GET['page'])): ?>
        <!-- Añadir Contenido ubicado dentro del contenedor flex para alinearse con los otros -->
        <section class="anadir-contenido">
            <!-- Se añade el id de la categoría para preestablecerla en el select de añadir -->
            <a class="enlace-crear-categoria" href="anadir_contenido.php?page=<?php echo $_GET['page']; ?>">
                <article class="testo-crear-categoria tamaño-variable">
                    <h1>+</h1>
                    <h3>Añadir Contenido</h3>
                </article>
            </a>
        </section>
    <?php endif;
        // Cerrar el contenedor de bloques
        echo "</section>";
    }catch (Exception $e){
        // Capturar y mostrar errores de conexión o consultas a la base de datos
        echo "<p>Ha ocurrido un error</p>";
    }
    ?>
    </main>
<!-- Pie de página con información de contacto -->
<!-- *********** Pie de página con información de contacto de Médicos del Mundo ***************** -->
<?php require_once "../footer.php"; ?>
<!-- ************** Enlace para volver al inicio (categorías raíz) ****************** -->
<a href="index.php" class="volver-inicio"><img src="../styles/img/casita.png" alt="inicio"></a>
</body>
</html>



