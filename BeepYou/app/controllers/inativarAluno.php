<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inativar Aluno</title>
</head>
<body>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../../public/js/configuracoes.js"></script>
</body>
</html>


<?php

session_start();

if(!isset($_SESSION["id_usuarios"]))
{
    header("Location: ../../index.html");
    exit();
}

include_once("alerta.php");
include_once("../models/Alunos.php");
include_once("../models/Emprestimo.php");

$objAluno = new Alunos();

if(isset($_GET["id"]))
{
    $id = $_GET["id"];

    if($objAluno->PossuiEmprestimoAtivo($id))
    {
        mostrarAlerta(
            "warning",
            "Aluno não pode ser inativado!",
            "Este aluno possui um empréstimo ativo e precisa realizar a devolução antes de ser inativado.",
            "../views/alunos.php"
        );
        exit();
    }

       if($objAluno->InativarAluno($id))
    {
        mostrarAlerta(
            "success",
            "Aluno inativado!",
            "O aluno foi inativado com sucesso.",
            "../views/alunos.php"
        );
    }
    else
    {
        mostrarAlerta(
            "error",
            "Erro ao inativar aluno!",
            "Não foi possível inativar o aluno.",
            "../views/alunos.php"
        );
    }
    exit();
}