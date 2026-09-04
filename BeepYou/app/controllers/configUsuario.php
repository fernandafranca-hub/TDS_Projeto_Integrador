<?php

session_start();

include_once("../models/User.php");
include_once("../models/Alunos.php");
include_once("../models/Patrimonio.php");
include_once("../models/Emprestimo.php");

$objUsuario = new User();
$usuarios = $objUsuario->ListarTodosUsuarios();

include("../views/config.php");

?>