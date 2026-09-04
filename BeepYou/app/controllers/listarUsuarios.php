<?php

session_start();

if(!isset($_SESSION["id_usuarios"]))
{
    header("Location: ../../index.html");
    exit();
}

include_once("../models/User.php");

$objUsuario = new User();
$usuarios = $objUsuario->ListarTodosUsuarios();

include("../views/usuarios.php");

?>