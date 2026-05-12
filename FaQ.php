<?php
// Incluir las clases necesarias para manejar categorías, base de datos y bloques de contenido

require_once "Classes/Categoria.php";
require_once "Classes/DB.php";
require_once "Classes/Bloque.php";
require_once "Classes/Faq.php";
?>
<!doctype html>
<!-- Página principal del sitio web de Médicos del Mundo, muestra categorías y contenido -->
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
include_once "header.php";
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
    <section class="faq-container">

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
                    <summary class="faq-question"><?php echo htmlspecialchars($faq->getPregunta()); ?></summary>
                    <p class="faq-answer"><?php echo nl2br(htmlspecialchars($faq->getRespuesta())); ?></p>
                </details>
                <?php
            }
        } else {
            echo "<p style='text-align: center;'>No hay preguntas frecuentes disponibles para esta categoría en este momento.</p>";
        }
        ?>

    </section>
</main>
<a href='index.php' class='volver-inicio'><img src='styles/img/casita.png' alt='regresa a inicio'></a>
<!-- Pie de página con información de contacto de Médicos del Mundo -->
<?php require_once "footer.php"; ?>
</body>
</html>