<?php

session_start();

include_once("../models/Alunos.php");

$objAluno = new Alunos();

if(isset($_GET["id"]))
{
    $objAluno->AtivarAluno($_GET["id"]);
}

header("Location: ../views/alunos.php");
exit();
