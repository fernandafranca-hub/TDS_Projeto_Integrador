<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Emprestar Aluno</title>
</head>
<body>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../../public/js/configuracoes.js"></script>
</body>
</html>

<?php

session_start();

include_once("alerta.php");
include_once("../models/Emprestimo.php");
include_once("../models/Patrimonio.php");
include_once("../models/Configuracao.php");

if (
    !isset($_SESSION["id_usuarios"]) ||
    !isset($_SESSION["id_alunos"]) ||
    $_SESSION["tipo_usuario"] != "Aluno"
) {
    header("Location: ../../index.html");
    exit();
}


if ($_SERVER["REQUEST_METHOD"] != "POST") {

    header("Location: ../views/inicio.php");
    exit();
}


$codigo = trim($_POST["codigo"] ?? "");


if (empty($codigo)) {

    mostrarAlerta(
        "warning",
        "Código não informado!",
        "Digite ou escaneie o código do patrimônio.",
        "../views/inicio.php"
    );

    exit();
}

$objPatrimonio = new Patrimonio();
$objConfiguracao = new Configuracao();
$objEmprestimo = new Emprestimo();

$patrimonio = $objPatrimonio->BuscarPatrimonioPorCodigoSemUsuario(
    $codigo
);

if (!$patrimonio) {

    mostrarAlerta(
        "error",
        "Patrimônio não encontrado!",
        "Não foi encontrado nenhum patrimônio com esse código.",
        "../views/inicio.php"
    );

    exit();
}


if ($patrimonio["status"] !== "Disponível") {

    mostrarAlerta(
        "warning",
        "Patrimônio indisponível!",
        "Este patrimônio já está emprestado e não pode ser emprestado novamente.",
        "../views/inicio.php"
    );

    exit();
}


$emprestimoAtivo = $objEmprestimo->BuscarEmprestimoAtivoPorPatrimonio(
    $patrimonio["id_patrimonio"]
);


if ($emprestimoAtivo) {

    $objPatrimonio->AlterarStatusPatrimonio(
        $patrimonio["id_patrimonio"],
        "Emprestado"
    );

    mostrarAlerta(
        "warning",
        "Patrimônio indisponível!",
        "Este patrimônio já possui um empréstimo ativo.",
        "../views/inicio.php"
    );

    exit();
}

$diasEmprestimo = $objConfiguracao->BuscarDiasEmprestimo();

$data_emprestimo = date("Y-m-d");

$data = new DateTime($data_emprestimo);

$data->modify(
    "+{$diasEmprestimo} days"
);

$data_prevista = $data->format("Y-m-d");

$resultado = $objEmprestimo->CadastrarEmprestimo(

    $_SESSION["id_alunos"],
    $patrimonio["id_patrimonio"],
    $data_emprestimo,
    $data_prevista,
    "",
    $patrimonio["usuarios_idfk"]
);

if (!$resultado) {

    mostrarAlerta(
        "error",
        "Erro ao realizar empréstimo!",
        "Não foi possível registrar o empréstimo.",
        "../views/inicio.php"
    );
    exit();
}


$alterouStatus = $objPatrimonio->AlterarStatusPatrimonio(
    $patrimonio["id_patrimonio"],
    "Emprestado"
);


if (!$alterouStatus) 
{
    mostrarAlerta(
        "warning",
        "Empréstimo registrado!",
        "O empréstimo foi registrado, mas ocorreu um problema ao atualizar o status do patrimônio.",
        "../views/inicio.php"
    );
    exit();
}

    mostrarAlerta(
        "success",
        "Empréstimo realizado!",
        "O patrimônio foi emprestado com sucesso.",
        "../views/inicio.php"
    );

exit();