<?php
// Incluir las clases necesarias para manejar categorías, base de datos y bloques de contenido
require_once "../classes/Usuario.php";
require_once "../classes/DB.php";
require_once "../classes/Contenido.php";
require_once "../classes/Categoria.php";
require_once "../classes/Faq.php";
require_once "../classes/Bloque.php";

// OBLIGATORIO: Iniciar la sesión antes de hacer nada con $_SESSION
session_start();

// Opcional pero necesario si no usas autoloader:
// require_once 'tus_clases.php'; // Asegúrate de cargar las clases Categoria, Bloque y la de la usuaria.

// CONTROL DE ACCESO ADMIN
// Si no tiene permisos, redirige a la página principal
require_once "../controladores/control_editora.php";
if (!isset($_GET["categoria"])) {
    header("Location: index.php");
    exit();
}

?>
<!doctype html>
<!-- Página principal del sitio web de Médicos del Mundo, muestra categorías y contenido -->
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="../styles/style.css">
    <title>Bienvenidas</title>
    <link rel="icon" type="image/png" sizes="32x32" href="https://www.medicosdelmundo.org/app/themes/mdm/library/medias/favicon/favicon-32x32.png">
</head>
<body>
<?php
include_once "../header.php";
?>
<main>
    <?php
    if (isset($_GET['categoria'])): ?>
        <section class="titulo-section">
            <a href="index.php?page=<?= $_GET['categoria'];?>" class="faq-variable">⮌ Volver atrás</a>
            <h1 class="faq-titulo-categoria"><?= Categoria::getCategoriaById($_GET['categoria'])->getNombre();?></h1>
            <h1 class="titulo-page">Preguntas Frecuentes</h1>
        </section>
    <?php endif; ?>
    <section class="faq-container" style="margin-top: 0">

        <?php
        // Verificar si se ha pasado un parámetro 'categoria'
        if (isset($_GET['categoria'])) {
            $faqs = Faq::ListarFAQPorCategoria($_GET['categoria']);
        } else {
            // Si no hay categoría, mostrar todos los FAQs (opcional)
            $faqs = Faq::ListarFAQ();
        }

        if (count($faqs) > 0) {
            foreach ($faqs as $faq) {
                ?>
                <details class="faq-item">
                    <summary class="faq-question" style="font-weight: normal; font-size: x-large;"><?php echo htmlspecialchars($faq->getPregunta()); ?></summary>
                    <p class="faq-answer"><?php echo nl2br(htmlspecialchars($faq->getRespuesta())); ?></p>
                    <article class="faq-acciones">
                        <a href="editar_faq.php?faq=<?php echo $faq->getIdFaq(); ?>" class="boton-editar"><img src="../styles/img/lapiz.png" alt="Editar" class="boton-editar-img"></a>
                        <a href="/Medicas-del-Mundo/controladores/eliminar_faq.php?id=<?php echo $faq->getIdFaq(); ?>" class="boton-eliminar" onclick="return confirm('¿Estás segura de eliminar este FAQ?');"><img src="../styles/img/basura.png" alt="Eliminar" class="boton-eliminar-img"></a>
                    </article>
                </details>
                <?php
            }
        } else {
            echo "<p class='faq-vacio'>No hay preguntas frecuentes disponibles para esta categoría en este momento.</p>";
        }
        ?>

        <?php
        $url_anadir = isset($_GET['categoria']) ? "anadir_faq.php?categoria=" . $_GET['categoria'] : "anadir_faq.php";
        ?>
    <a class="enlace-crear-faq" href="<?php echo $url_anadir; ?>" >
        <section class="anadir-faq">
                <article class="testo-crear-faq" >
                    <h3 style="font-weight: normal">Añadir FAQ</h3>
                </article>
            </section>
        </a>

    </section>
</main>
<a href='index.php' class='volver-inicio'><img src='../styles/img/casita.png' alt='regresa a inicio'></a>
<!-- Pie de página con información de contacto de Médicos del Mundo -->
<footer>
    <section class="footer-section">
        <h2>Médicos del Mundo España</h2>
        <p>Conde de Vilches, 15 · 28028, Madrid</p>
        <p>Lunes a viernes: 8:00 - 20:00</p>
        <p>
            Tel: <a href="tel:+34915436033">91 543 60 33</a> ·
            Email: <a href="mailto:informacion@medicosdelmundo.org">informacion@medicosdelmundo.org</a>
        </p>
        <p><a href="../login.php">Cerrar Sesion</a></p>
    </section>
</footer>
</body>
</html>