
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Devolver Empréstimo</title>
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

$objEmprestimo = new Emprestimo();

$id = $_POST["id_emprestimo"] ?? "";


// Verifica se o ID foi enviado
if (empty($id))
{
    mostrarAlerta(
        "error",
        "Empréstimo inválido!",
        "Não foi possível identificar o empréstimo.",
        "../views/emprestimos.php"
    );
    exit();
}

$emprestimo = $objEmprestimo->BuscarEmprestimoPorId($id);

if (!$emprestimo)
{
    mostrarAlerta(
        "error",
        "Empréstimo não encontrado!",
        "Não foi possível localizar este empréstimo.",
        "../views/emprestimos.php"
    );
    exit();
}


if ($emprestimo["status"] == "Devolvido")
{
    mostrarAlerta(
        "warning",
        "Empréstimo já devolvido!",
        "Este empréstimo já foi registrado como devolvido.",
        "../views/emprestimos.php"
    );
    exit();
}

if ($objEmprestimo->DevolverEmprestimo($id))
{
    mostrarAlerta(
        "success",
        "Devolução realizada!",
        "O empréstimo foi registrado como devolvido com sucesso.",
        "../views/emprestimos.php"
    );
}
else
{
    mostrarAlerta(
        "error",
        "Erro ao devolver!",
        "Não foi possível registrar a devolução.",
        "../views/emprestimos.php"
    );
}
exit();
?>