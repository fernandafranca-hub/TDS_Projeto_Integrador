<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nova Senha</title>
</head>
<body>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../../public/js/configuracoes.js"></script>
</body>
</html>


<?php

session_start();

include_once("alerta.php");
include_once("../models/User.php");

$objUser = new User();

$token = $_POST["token"] ?? "";
$senha = $_POST["senha"] ?? "";
$confirmarSenha = $_POST["confirmar"] ?? "";


if($token == "")
{
    mostrarAlerta(
        "error",
        "Link inválido!",
        "Não foi possível identificar a solicitação de recuperação.",
        "../../index.html"
    );
    exit();
}


if($senha == "" || $confirmarSenha == "")
{
    mostrarAlerta(
        "warning",
        "Preencha todos os campos.",
        "Informe a nova senha e confirme a senha.",
        "voltar"
    );
    exit();
}


if($senha != $confirmarSenha)
{
    mostrarAlerta(
        "warning",
        "Senhas diferentes.",
        "A confirmação da nova senha não confere.",
        "voltar"
    );
    exit();
}



if(!preg_match('/^(?=.*[A-Z])(?=.*\d).{8,}$/', $senha))
{
    mostrarAlerta(
        "warning",
        "Senha inválida.",
        "A senha deve conter pelo menos 8 caracteres, uma letra maiúscula e um número.",
        "voltar"
    );
    exit();
}


$usuario = $objUser->BuscarPorToken($token);

if(!$usuario)
{
    mostrarAlerta(
        "error",
        "Link inválido!",
        "O link de recuperação não é válido.",
        "../../index.html"
    );
    exit();
}

$novaSenha = md5($senha);

if($objUser->AlterarSenha($usuario["id_usuarios"], $novaSenha))
{
    $objUser->LimparTokenRecuperacao($usuario["id_usuarios"]);

    mostrarAlerta(
        "success",
        "Senha alterada com sucesso!",
        "Sua senha foi alterada. Agora você pode entrar no sistema.",
        "../../index.html"
    );
}
else
{
    mostrarAlerta(
        "error",
        "Erro ao alterar senha.",
        "Não foi possível atualizar sua senha.",
        "voltar"
    );
}
exit();
?>