<?php
session_start();


if(!isset($_SESSION["id_usuarios"]))
{
    header("Location: ../../index.html");
    exit();
}

include_once("alerta.php");

if(isset($_GET["id"]))
{
    $id = $_GET["id"];

    include_once("../models/Patrimonio.php");
    include_once("../models/Emprestimo.php");

    $obj = new Patrimonio();
    $objEmprestimo = new Emprestimo();

    $patrimonio = $obj->BuscarPatrimonioPorId($id);

    if(!$patrimonio)
    {
        mostrarAlerta(
            "error",
            "Patrimônio não encontrado!",
            "O patrimônio informado não está cadastrado.",
            "voltar"
        );
        exit();
    }

    $emprestimos = $objEmprestimo->ListarEmprestimosPorPatrimonio($id);

    $_SESSION["patrimonio_visualizar"] = $patrimonio;
    $_SESSION["emprestimos_patrimonio"] = $emprestimos;

    header("Location: ../views/visualizarpatrimonio.php");
    exit();
}

?>