<?php

session_start();

include_once("../models/User.php");

$id = $_GET["id"];

$objUsuario = new User();

$_SESSION["usuario_visualizar"] = $objUsuario->BuscarUsuarioPorId($id);

header("Location: ../views/config.php");
exit();