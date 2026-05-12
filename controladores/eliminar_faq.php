<?php
// Incluir las clases necesarias para gestionar categorías, bloques y base de datos
require_once "../classes/Usuario.php";
require_once "../classes/Contenido.php";
require_once "../classes/Categoria.php";
require_once "../classes/Faq.php";
require_once "../classes/Bloque.php";
require_once "../classes/DB.php";

// 1. OBLIGATORIO: Iniciar la sesión antes de hacer nada con $_SESSION
session_start();

// 2. CRÍTICO: Primero comprobamos que la sesión 'usuaria' existe (isset).
// Si no lo haces y alguien entra sin loguearse, el código "peta" al intentar llamar a un metodo de algo que no existe.
if (isset($_SESSION['usuaria']) && (($_SESSION['usuaria']->controlUsuarioEditora() || $_SESSION['usuaria']->controlUsuarioAdmin()))) {
    // Comprobamos qué llegue el faq
    if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['id'])) {
        $faq = Faq::getFaqById($_GET['id']);
        $faq->EliminarFAQ();
        // Redirigimos al inicio según su rol
        header ("Location: ../".$_SESSION['usuaria']->getRol()."/faq.php?categoria=".$faq->getIdCategoria());
        exit();
    }
}

// Si no hay sesión iniciada (o no es editora/admin), redirigimos al inicio
header ("Location: ../index.php");
exit();

