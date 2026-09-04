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

    include_once("../models/Emprestimo.php");

    $obj = new Emprestimo();

    $emprestimo = $obj->BuscarEmprestimoPorId($id);

    if(!$emprestimo)
    {
        mostrarAlerta(
            "error",
            "Empréstimo não encontrado!",
            "O empréstimo informado não está cadastrado.",
            "voltar"
        );
        exit();
    }
    $_SESSION["emprestimo_visualizar"] = $emprestimo;

    header("Location: ../views/visualizaremprestimo.php");

    exit();
}
?>