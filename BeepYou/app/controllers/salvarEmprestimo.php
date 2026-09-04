<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Salvar Emprestimo</title>
</head>
<body>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../../public/js/configuracoes.js"></script>
</body>
</html>

<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (
    !isset($_SESSION["tipo_usuario"]) ||
    $_SESSION["tipo_usuario"] != "Administrativo"
) {
    header("Location: dashboard.php");
    exit();
}

include_once("alerta.php");
include_once("../models/Emprestimo.php");
include_once("../models/Patrimonio.php");
include_once("../models/Alunos.php");
include_once("../models/Configuracao.php");


if($_SERVER["REQUEST_METHOD"] == "POST")
{
    $alunos = $_POST["alunos_idfk"];
    $patrimonio = $_POST["patrimonio_idfk"];
    $data_emprestimo = $_POST["data_emprestimo"];
    $observacao = $_POST["observacao"] ?? "";

    $usuarios = $_SESSION["id_usuarios"];


    $objAluno = new Alunos();
    $objPatrimonio = new Patrimonio();
    $objConfiguracao = new Configuracao();
    $objEmprestimo = new Emprestimo();

    $aluno = $objAluno->BuscarAlunoPorId($alunos);

    if(!$aluno)
    {
        mostrarAlerta(
            "error",
            "Aluno não encontrado!",
            "O aluno selecionado não foi encontrado.",
            "../views/novoEmprestimo.php"
        );
        exit();
    }


    if($aluno["ativo"] !== true && $aluno["ativo"] !== "t")
    {
        mostrarAlerta(
            "warning",
            "Aluno inativo!",
            "Não é possível realizar um empréstimo para um aluno inativo.",
            "../views/novoEmprestimo.php"
        );
        exit();
    }

    $patrimonioDados = $objPatrimonio->BuscarPatrimonioPorId($patrimonio);

    if(!$patrimonioDados)
    {
        mostrarAlerta(
            "error",
            "Patrimônio não encontrado!",
            "O patrimônio selecionado não foi encontrado.",
            "../views/novoEmprestimo.php"
        );
        exit();
    }

    if($patrimonioDados["status"] !== "Disponível")
    {
        mostrarAlerta(
            "warning",
            "Patrimônio indisponível!",
            "Este patrimônio já está emprestado e não pode ser emprestado novamente.",
            "../views/novoEmprestimo.php"
        );
        exit();
    }

    $diasEmprestimo = $objConfiguracao->BuscarDiasEmprestimo();

    $data = new DateTime($data_emprestimo);
    $data->modify("+{$diasEmprestimo} days");
    $data_prevista = $data->format("Y-m-d");

    $resultado = $objEmprestimo->CadastrarEmprestimo($alunos, $patrimonio, $data_emprestimo, $data_prevista, $observacao, $usuarios);

    if($resultado)
    {
        $alterouStatus = $objPatrimonio->AlterarStatusPatrimonio(
            $patrimonio,
            "Emprestado"
        );

        if($alterouStatus)
        {
            mostrarAlerta(
                "success",
                "Empréstimo realizado!",
                "O empréstimo foi realizado com sucesso.",
                "../views/emprestimos.php"
            );
        }
        else
        {
            mostrarAlerta(
                "warning",
                "Empréstimo realizado!",
                "O empréstimo foi cadastrado, mas houve um problema ao atualizar o status do patrimônio.",
                "../views/emprestimos.php"
            );
        }
        exit();
    }

    mostrarAlerta(
        "error",
        "Erro ao realizar empréstimo!",
        "Não foi possível realizar o empréstimo.",
        "../views/novoEmprestimo.php"
    );
    exit();
}
?>