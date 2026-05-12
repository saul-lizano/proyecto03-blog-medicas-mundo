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
if (empty($_GET['faq'])){
    header ("Location: index.php");
    exit();
}
// instanciamos el Faq
$faq = Faq::getFaqById($_GET['faq']);
// Verificar si se envió un formulario por POST con los datos de la categoría
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['faq'])) {
    if (!empty($_POST['pregunta'])) {
        $faq->setPregunta($_POST["pregunta"]);
    }
    if (!empty($_POST['respuesta'])) {
        $faq->setRespuesta($_POST["respuesta"]);
    }
    $fecha = date("Y-m-d H:i:s", time());
    $faq->setFecha($fecha);
    try {
        $faq->InsertarFAQ();
        header ("location: FaQ.php?categoria=". $faq->getIdCategoria());
        exit();
    } catch (Exception $e) {
        $error = "No se pudo guardar la pregunta";
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
        <section class="titulo-section" >
            <a href="FaQ.php?categoria=<?= $faq->getIdCategoria();?>" class="faq-variable">⮌ Volver atrás</a>
            <h1 class="faq-titulo-categoria"><?= Categoria::getCategoriaById($faq->getIdCategoria())->getNombre();?></h1>
            <h1 class="titulo-page">Preguntas Frecuentes</h1>
        </section>

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

    <article class="anadir-categoria" style="align-items: normal;">
        <form action="" method="post" class="form-anadir">
            <input type="hidden" name="faq" value="<?= $faq->getIdFaq(); ?>" >

            <label for="pregunta">Pregunta: </label>
            <input type="text" id="pregunta" name="pregunta" value="<?= $faq->getPregunta(); ?>">

            <label for="respuesta">Respuesta: </label>
            <textarea id="respuesta" name="respuesta"><?= htmlspecialchars($faq->getRespuesta()); ?></textarea>

            <button type="submit">Añadir</button>
        </form>
    </article>
</main>
<!-- Pie de página con información de contacto de Médicos del Mundo -->
<?php require_once "../footer.php"; ?>
<a href="../admin/index.php" class="volver-inicio"><img src="../styles/img/casita.png" alt="regresa a inicio"></a>
</body>
</html>