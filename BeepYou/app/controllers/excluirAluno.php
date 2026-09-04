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
    $obj = new Alunos();

    if($obj->ExcluirAluno($id))
    {
        mostrarAlerta(
            "success",
            "Aluno excluído!",
            "O aluno foi excluído com sucesso.",
            "../views/alunos.php"
        );
    }
    else
    {
        mostrarAlerta(
            "error",
            "Erro ao excluir aluno!",
            "Não foi possível excluir o aluno.",
            "voltar"
        );
    }
}
?>