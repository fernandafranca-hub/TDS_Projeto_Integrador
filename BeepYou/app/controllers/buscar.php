<?php

session_start();

include_once("../models/Busca.php");

if(!isset($_SESSION["id_usuarios"]))
{
    header("Location: ../../index.html");
    exit();
}

$pesquisa = $_GET["pesquisa"] ?? "";

if(trim($pesquisa) == "")
{
    header("Location: ../views/dashboard.php");
    exit();
}

$id_usuario = $_SESSION["id_usuarios"];
$objBuscar = new Busca();
$resultados = $objBuscar->BuscarTudo($pesquisa, $id_usuario);

include_once("../views/resultadoBusca.php");

?>