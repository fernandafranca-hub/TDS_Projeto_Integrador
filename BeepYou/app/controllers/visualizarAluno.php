<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VisualizarAluno</title>
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

if(isset($_GET["id"]))
{
    $id = $_GET["id"];

    include_once("../models/Alunos.php");
    include_once("../models/Emprestimo.php");


    $objAluno = new Alunos();
    $objEmprestimo = new Emprestimo();


    $aluno = $objAluno->BuscarAlunoPorId($id);


    if(!$aluno)
    {
        mostrarAlerta(
            "error",
            "Aluno não encontrado!",
            "O aluno informado não está cadastrado.",
            "voltar"
        );
        exit();
    }

    $emprestimos = $objEmprestimo->ListarEmprestimosPorAluno($id);

    $_SESSION["aluno_visualizar"] = $aluno;
    $_SESSION["emprestimos_aluno"] = $emprestimos;


    header("Location: ../views/visualizaraluno.php");
    exit();
}
?>