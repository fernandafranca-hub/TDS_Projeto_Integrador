<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Emprestimo</title>
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
include_once("../models/Emprestimo.php");
include_once("../models/Alunos.php");
include_once("../models/Patrimonio.php");

$objEmprestimo = new Emprestimo();
$objAlunos = new Alunos();
$objPatrimonio = new Patrimonio();


if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $id = $_POST["id_emprestimos"] ?? null;
    $alunos = $_POST["alunos_idfk"] ?? null;
    $patrimonio = $_POST["patrimonio_idfk"] ?? null;
    $data_emprestimo = $_POST["data_emprestimo"] ?? null;
    $data_prevista = $_POST["data_prevista"] ?? null;
    $observacao = $_POST["observacao"] ?? "";
    $status = $_POST["status"] ?? null;

    if (!$id) {
        mostrarAlerta(
            "error",
            "Empréstimo inválido!",
            "Não foi possível identificar o empréstimo.",
            "../views/emprestimos.php"
        );
        exit();
    }

    if (
        $objEmprestimo->EditarEmprestimo( $id, $alunos, $patrimonio, $data_emprestimo, $data_prevista, $observacao, $status)) 
            {

        mostrarAlerta(
            "success",
            "Empréstimo atualizado!",
            "Os dados do empréstimo foram alterados com sucesso.",
            "../views/emprestimos.php"
        );
    } 
    else 
    {

        mostrarAlerta(
            "error",
            "Erro ao atualizar empréstimo!",
            "Não foi possível salvar as alterações.",
            "voltar"
        );
    }
    exit();
}


if (isset($_GET["id"])) {

    $id = $_GET["id"];

    $emprestimo = $objEmprestimo->BuscarEmprestimoPorId($id);

    if (!$emprestimo) {

        mostrarAlerta(
            "error",
            "Empréstimo não encontrado!",
            "Não foi possível localizar este empréstimo.",
            "../views/emprestimos.php"
        );
        exit();
    }

    $alunos = $objAlunos->ListarTodosAlunos();
    $patrimonios = $objPatrimonio->ListarTodosPatrimonios();

    include_once("../views/editaremprestimo.php");
    exit();
}

header("Location: ../views/emprestimos.php");
exit();