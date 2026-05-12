<?php
if (!isset($_SESSION['usuaria']) || !$_SESSION["usuaria"]->controlUsuarioAdmin()) {
    header("location: ../index.php");
    exit();
}