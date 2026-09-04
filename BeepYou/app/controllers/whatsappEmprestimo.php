<?php

session_start();

include_once("../models/Emprestimo.php");

$id = $_GET["id"] ?? "";

if (empty($id)) {
    header("Location: ../views/emprestimos.php");
    exit();
}

$objEmprestimo = new Emprestimo();
$emprestimo = $objEmprestimo->BuscarDadosAviso($id);

if (!$emprestimo) {
    header("Location: ../views/emprestimos.php");
    exit();
}

$telefone = $emprestimo["telefone"] ?? "";

if (empty($telefone)) {

    echo "
        <script>
            alert('Este aluno não possui telefone cadastrado.');
            window.close();
        </script>
    ";
    exit();
}

$telefone = preg_replace('/\D/', '', $telefone);


if (strlen($telefone) == 11) 
{
    $telefone = "55" . $telefone;
}

elseif (strlen($telefone) == 10) 
{
    $telefone = "55" . $telefone;
}

$dataPrevista = date(
    "d/m/Y",
    strtotime($emprestimo["data_prevista"])
);

    $mensagem =
    "Olá, {$emprestimo["nome_aluno"]}! 
    Aqui é do BeepYou.
    Identificamos que o empréstimo do patrimônio *{$emprestimo["nome_patrimonio"]}* está em atraso.
    Data prevista para devolução: {$dataPrevista}
    Pedimos que providencie a devolução do patrimônio o mais breve possível.
    Caso já tenha realizado a devolução, favor desconsiderar esta mensagem.
    Atenciosamente,
    BeepYou";

    $url = "https://wa.me/" .
        $telefone .
        "?text=" .
        urlencode($mensagem);

    header("Location: " . $url);
exit();
?>