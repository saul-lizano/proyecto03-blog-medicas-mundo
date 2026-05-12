<?php
require_once "../classes/Usuario.php";
require_once "../classes/DB.php";

session_start();
if ($_SESSION['usuaria']->ControlUsuarioAdmin()) {
    if (isset($_GET['page'])){
        Usuario::EliminarUsuaria($_GET['page']);
    }
    header ("Location: ../".$_SESSION['usuaria']->getRol()."/index.php");
    exit();
} else {
    header ("Location: ../index.php");
    exit();
}

