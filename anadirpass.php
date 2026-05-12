<?php
require_once "Classes/DB.php";
$pass = password_hash("1234", PASSWORD_DEFAULT);
$db = DB::conectar();
$db->exec("UPDATE usuario SET password = '$pass' WHERE id_usuario = 2");

echo "Contraseña actualizada correctamente";
?>