<?php

session_start();

include_once("../models/Alunos.php");
include_once("../models/Patrimonio.php");

if (
    !isset($_SESSION["id_usuarios"]) ||
    !isset($_SESSION["id_alunos"]) ||
    $_SESSION["tipo_usuario"] != "Aluno"
) {
    header("Location: ../../index.html");
    exit();
}


$objAluno = new Alunos();
$aluno = $objAluno->BuscarAlunoPorId(
    $_SESSION["id_alunos"]
);

if (!$aluno) {
    session_destroy();
    header("Location: ../../index.html");
    exit();
}

$objPatrimonio = new Patrimonio();

$patrimoniosDisponiveis =
    $objPatrimonio->ListarPatrimoniosDisponiveisAluno();

if (!is_array($patrimoniosDisponiveis)) {
    $patrimoniosDisponiveis = [];
}

$totalPatrimoniosDisponiveis =
    count($patrimoniosDisponiveis);

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BeepYou - Patrimônios disponíveis</title>
    <link rel="stylesheet" type="text/css"  href="../../public/css/aluno.css">
</head>
<body>
    <div class="app-aluno">
           <header class="topo-aluno topo-scan">
            <a href="inicio.php">
                <img src="../../public/img/1.png" alt="Logo BeepYou" class="logo-aluno">
            </a>
            <a href="inicio.php" class="btn-voltar-scan" title="Voltar">
            <img src="../../public/img/iconvoltar.png" alt="Voltar">
            </a>     
            <a href="../controllers/logoff.php" class="btn-sair">
                <span>Sair</span>
            </a>
        </header>
<main class="conteudo-aluno pagina-patrimonios">

    <section class="cabecalho-patrimonios">
        <h1>Patrimônios disponíveis</h1>

        <p>
            <?php echo $totalPatrimoniosDisponiveis; ?>
            patrimônio<?php echo $totalPatrimoniosDisponiveis == 1 ? "" : "s"; ?>
            disponível<?php echo $totalPatrimoniosDisponiveis == 1 ? "" : "is"; ?>
            para empréstimo.
        </p>
    </section>


    <?php if (empty($patrimoniosDisponiveis)) { ?>

        <section class="patrimonio-vazio">

            <div class="icone-patrimonio-vazio">—</div>

            <p>
                No momento não existem itens disponíveis para empréstimo.
            </p>

        </section>

    <?php } else { ?>

        <section class="lista-patrimonios-aluno">

            <?php foreach ($patrimoniosDisponiveis as $patrimonio) { ?>

                <div class="patrimonio-card-aluno">

                    <div class="codigo-patrimonio">
                        <?php echo htmlspecialchars($patrimonio["codigo"]); ?>
                    </div>

                    <div class="nome-patrimonio">
                        <?php echo htmlspecialchars($patrimonio["nome"]); ?>
                    </div>

                </div>

            <?php } ?>

        </section>

    <?php } ?>

</main>       
      
        <nav class="menu-inferior">
            <a href="inicio.php" class="menu-bottom ativo">
                <img src="../../public/img/2dashboard.png" alt="Início">
                <span>Início</span>
            </a>
            <a href="historico.php" class="menu-bottom">
                <img src="../../public/img/2rental.png" alt="Histórico">
                <span>Histórico</span>
            </a>
            <a href="perfil.php" class="menu-bottom">
                <img src="../../public/img/2student.png" alt="Perfil">
                <span>Perfil</span>
            </a>
        </nav>
    </div>
</body>
</html>