<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alterar Senha</title>
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


if (!isset($_SESSION["id_usuario"]))
{
    header("Location: ../../index.html");
    exit();
}

$objUser = new User();

$id = $_SESSION["id_usuario"];

$novaSenha = $_POST["nova_senha"] ?? "";
$confirmarSenha = $_POST["confirmar_senha"] ?? "";

if ($novaSenha == "" || $confirmarSenha == "")
{
    mostrarAlerta(
        "warning",
        "Preencha todos os campos.",
        "Informe a nova senha e confirme a nova senha.",
        "voltar"
    );
    exit();
}

if ($novaSenha != $confirmarSenha)
{
    mostrarAlerta(
        "warning",
        "Senhas diferentes.",
        "A confirmação da nova senha não confere.",
        "voltar"
    );
    exit();
}


if (!preg_match('/^(?=.*[A-Z])(?=.*\d).{8,}$/', $novaSenha))
{
    mostrarAlerta(
        "warning",
        "Senha inválida.",
        "A senha deve conter pelo menos 8 caracteres, uma letra maiúscula e um número.",
        "voltar"
    );

    exit();
}

$novaSenhaCriptografada = md5($novaSenha);

if ($objUser->AlterarSenha($id, $novaSenhaCriptografada))
{
    $_SESSION["primeiro_acesso"] = false;


    if ($_SESSION["tipo_usuario"] == "Aluno")
    {
        mostrarAlerta(
            "success",
            "Senha alterada com sucesso!",
            "Agora você já pode acessar o BeepYou.",
            "../views/inicio.php"
        );
        exit();
    }

    mostrarAlerta(
        "success",
        "Senha alterada com sucesso!",
        "Sua senha foi alterada com sucesso.",
        "../views/dashboard.php"
    );

    exit();
}

mostrarAlerta(
    "error",
    "Erro ao alterar senha.",
    "Não foi possível atualizar sua senha.",
    "voltar"
);
exit();

?>