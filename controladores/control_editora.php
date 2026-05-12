<?php
if (!isset($_SESSION['usuaria']) || !$_SESSION["usuaria"]->controlUsuarioEditora()) {
    header("location: ../index.php");
    exit();
}