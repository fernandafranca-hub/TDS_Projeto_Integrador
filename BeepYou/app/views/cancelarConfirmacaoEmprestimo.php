<?php

session_start();


if (
    !isset($_SESSION["id_alunos"]) ||
    $_SESSION["tipo_usuario"] != "Aluno"
) {
    header("Location: ../../index.html");
    exit();
}


unset($_SESSION["patrimonio_confirmacao"]);

header("Location: inicio.php");
exit();