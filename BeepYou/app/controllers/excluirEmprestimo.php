<?php
session_start();

if(!isset($_SESSION["id_usuarios"]))
    {
        header("Location: ../../index.html");
        exit();
    }

include_once("alerta.php");

echo '.';

if(isset($_GET["id"]))    
{
        $id = $_GET["id"];
        include_once("../models/Emprestimo.php");
        $obj = new Emprestimo();
        
    if($obj->ExcluirEmprestimo($id))
    {
        mostrarAlerta(
            "success",
            "Empréstimo excluído!",
            "O empréstimo foi excluído com sucesso.",
            "../views/emprestimos.php"
        );
    }
    else
    {
        mostrarAlerta(
            "error",
            "Erro ao excluir empréstimo!",
            "Não foi possível excluir o empréstimo.",
            "voltar"
        );
    }
}
?>