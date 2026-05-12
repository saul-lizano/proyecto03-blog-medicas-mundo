<?php
// Incluir clases necesarias para bloques, categorías, base de datos y contenido externo
require_once "classes/Bloque.php";
require_once "classes/Categoria.php";
require_once "classes/DB.php";
require_once "classes/Contenido.php";
require_once "classes/Usuario.php";

if (empty($_GET['page'])) {
    header ("Location: index.php");
    exit();
}
// obtenemos el bloque de contenido de la página
$bloque = Bloque::getBloqueById($_GET['page']);
// obtenemos otros bloques pertenecientes a la misma categoria y que se mostraran en el aside
$bloques_paralelos = Bloque::getBloquesByCategoria($bloque->getIdCategoria());
// categorias pertenecientes a la misma categoria que el bloque
$subcategorias = Categoria::getSubcategorias($bloque->getIdCategoria());
?>
<!-- Página para mostrar el contenido detallado de un bloque específico -->
<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="styles/style.css">
    <title>Bienvenidas</title>
    <link rel="icon" type="image/png" sizes="32x32" href="https://www.medicosdelmundo.org/app/themes/mdm/library/medias/favicon/favicon-32x32.png">
</head>
<body>
<?php
include "header.php";
?>
<!-- Contenido principal dividido en aside para bloques relacionados y sección para el contenido detallado -->
    <main id="content-container-main">
        <?php
        if (isset($_GET['page'])){
            $categoria_actual = Categoria::getCategoriaById($bloque->getIdCategoria());
            ?>
            <section class="titulo-section">
                <a href="index.php?page=<?= $categoria_actual->getIdCategoria(); ?>">
                    ⮌ Volver atrás
                </a>
                <h1 class="titulo-page"><?= $categoria_actual->getNombre(); ?></h1>
            </section>
            <?php
        }
        ?>

<!-- menu de navegación a la izquierda, muestra categorias y otros bloques pertenecientes a la misma categoria que este bloque-->
        <aside class="subcategorias-content" style="min-width: 25%;">
            <?php
            // Verificar si se ha especificado un bloque por parámetro 'page'
            if (isset($_GET['page'])) {
                foreach ($subcategorias as $subcategoria) {
                    ?>
                    <section class="categoria-content">
                        <a class="enlace-bloque-content" href="index.php?page=<?php echo $subcategoria->getIdCategoria(); ?>">
                            <article class="imagen-content">
                                <img src="styles/img/<?= $subcategoria->getImg(); ?>" alt="Imagen1">
                            </article>

                            <article class="testo-content">
                                <h1><?php echo $subcategoria->getNombre(); ?></h1>
                                <p><?php //echo $subcategoria->getDescripcion(); ?></p>
                            </article>
                        </a>
                    </section>
                    <?php
                }
                // Iterar sobre los bloques paralelos y mostrarlos
                foreach ($bloques_paralelos as $bloque_paralelo) {
                    ?>
                    <section class="categoria-content">
                        <a class="enlace-bloque-content" href="content.php?page=<?php echo $bloque_paralelo->getIdBloque(); ?>">
                            <article class="imagen-content">
                                <img src="styles/img/<?= $bloque_paralelo->getIcono(); ?>" alt="Imagen1">
                            </article>

                            <article class="testo-content">
                                <h1><?php echo $bloque_paralelo->getTituloBloque(); ?></h1>
                                <p><?php //echo $bloque_paralelo->getDescripcionBloque(); ?></p>
                            </article>
                        </a>
                    </section>
                    <?php
                }
            }
            ?>
        </aside>

<!-- Sección principal con el contenido detallado del bloque -->
        <section class="contenedor-contenido" style="overflow-y: scroll; height: 79vh;">
            <?php
            // Mostrar contenido si hay parámetro 'page'
            if (isset($_GET['page'])) {
                // Obtener el bloque nuevamente (podría optimizarse)
                $bloque = Bloque::getBloqueById($_GET['page']);
                // Obtener contenidos extra asociados al bloque
                $contenidos = Contenido::getContenidoByBloque($bloque->getIdBloque());
            ?>
            <article class="titulo" style="margin-bottom: 2%; text-align: center">
                <h1><?php echo $bloque->getTituloBloque();?></h1>
                <p><?= $bloque->getDescripcionBloque();?></p>
            </article>
                <article style="margin-bottom: 2%;">
                    <p>Enlaces de referencia :
                        <?php foreach($contenidos as $contenido){
                            if ($contenido->getTipo() == "enlace"): ?>
                                <a style="text-decoration: underline;" href="<?= $contenido->getUrl(); ?>"><?= $contenido->getDescripcion(); ?></a>
                            <?php endif;
                        }?>
                    </p>
            </article>
            <article class="parrafo">
                <?php
                $texto = $bloque->getTextoBloque();
                $parrafos = preg_split("/\r\n/", $texto);
                foreach ($parrafos as $parrafo) {
                    if ($parrafo != "") {
                        echo "<p style='font-weight: lighter;'>" . htmlspecialchars($parrafo) . "</p>";
                    } else {
                        echo "<br>";
                    }

                }
                ?>            </article>
                <?php
                // Iterar sobre los contenidos extra y mostrarlos como enlaces o imágenes
                foreach ($contenidos as $contenido) {
                    if ($contenido->getTipo() == "imagen") {
                    ?>
            <article class="enlace">
                <img src="styles/img/<?php echo $contenido->getUrl(); ?>" alt="<?php echo $contenido->getDescripcion(); ?>" style="max-width: 70%; height: auto;">
            </article>
            <?php
                    }
                }
            }
            ?>
        <section>

    </main>

<!-- Pie de página con información de contacto -->
<?php require_once "footer.php"; ?>
    <a href="index.php" class="volver-inicio"><img src="styles/img/casita.png" alt="regresa a inicio"></a>
</body>
</html>