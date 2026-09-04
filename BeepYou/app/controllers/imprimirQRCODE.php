<?php

session_start();

if(!isset($_SESSION["id_usuarios"]))
{
    header("Location: ../../index.html");
    exit();
}


if(!isset($_POST["patrimonios"]) || empty($_POST["patrimonios"]))
{
    header("Location: ../views/config.php");
    exit();
}

$_SESSION["patrimonios_impressao"] = $_POST["patrimonios"];

header("Location: ../views/impressaoQRCODE.php");

exit();

?>
