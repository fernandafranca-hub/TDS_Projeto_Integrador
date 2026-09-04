<?php

session_start();

if (
    !isset($_SESSION["id_alunos"]) ||
    $_SESSION["tipo_usuario"] != "Aluno"
) {
    header("Location: ../../index.html");
    exit();
}

include_once("../models/Emprestimo.php");

$objEmprestimo = new Emprestimo();
$idAluno = $_SESSION["id_alunos"];
$emprestimos = $objEmprestimo->ListarEmprestimosPorAluno($idAluno);

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BeepYou - Histórico</title>
    <link rel="stylesheet" type="text/css" href="../../public/css/aluno.css">
</head>

<body>
    <div class="app-aluno">
        <header class="topo-aluno">
            <a href="inicio.php">
                <img src="../../public/img/1.png" alt="Logo BeepYou" class="logo-aluno">
            </a>
            <a href="../controllers/logoff.php" class="btn-sair">
                <span>Sair</span>
            </a>
        </header>
        <main class="conteudo-aluno">
            <section class="saudacao">
                <h1>Meu histórico</h1>
                <p>Confira seus empréstimos e devoluções.</p>
            </section>
            <section class="historico">
                <?php if (empty($emprestimos)) { ?>
                <div class="emprestimo-card">
                    <div class="emprestimo-info">
                        <h3>Nenhum empréstimo encontrado</h3>
                        <span>Você ainda não possui empréstimos registrados.</span>
                    </div>
                </div>
                <?php } else { ?>
                <?php foreach ($emprestimos as $emprestimo) { ?>
                <?php                     
                $atrasado = false;
                if (
                    $emprestimo["status"] == "Emprestado" &&
                    !empty($emprestimo["data_prevista"]) &&
                    strtotime($emprestimo["data_prevista"]) < strtotime(date("Y-m-d"))
                ) {
                    $atrasado = true;
                }
                ?>
                <div class="emprestimo-card <?php echo $atrasado ? 'emprestimo-atrasado' : ''; ?>">
                    <div class="emprestimo-info">
                        <h3><?php echo htmlspecialchars($emprestimo["nome_patrimonio"]); ?></h3>
                        <span class="patrimonio">
                            Patrimônio:<?php echo htmlspecialchars($emprestimo["codigo_patrimonio"]); ?>
                        </span>
                        <div class="emprestimo-datas">
                            <div class="data-item">
                                <span class="data-label">Empréstimo</span>
                                <strong><?php echo date("d/m/Y", strtotime($emprestimo["data_emprestimo"])); ?></strong>
                            </div>
                            <div class="data-item">
                                <span class="data-label">Devolução prevista</span>
                                <strong><?php
                                    if (!empty($emprestimo["data_prevista"])) 
                                    {
                                        echo date("d/m/Y", strtotime($emprestimo["data_prevista"]));
                                    } 
                                    else 
                                    {
                                        echo "-";
                                    } ?>
                                </strong>
                            </div>
                            <div class="data-item">
                                <span class="data-label">Devolvido em</span>
                                <strong><?php
                                    if (!empty($emprestimo["data_devolucao"])) {
                                        echo date(
                                            "d/m/Y",
                                            strtotime($emprestimo["data_devolucao"])
                                        );
                                    } else {
                                        echo "Ainda não devolvido";
                                    }?>
                                </strong>
                            </div>
                        </div>
                        <div class="emprestimo-status">
                            <?php if ($atrasado) 
                            { ?>
                                <span class="status atrasado">
                                    ● Em atraso
                                </span><?php 
                            } 
                            elseif ($emprestimo["status"] == "Emprestado") 
                            { ?>
                                <span class="status ativo">
                                    ● Emprestado
                                </span><?php 
                            } 
                            else 
                            { ?>
                                <span class="status devolvido">
                                    ✓ Devolvido
                                </span><?php 
                            } ?>
                        </div>
                    </div>
                </div>
                <?php } ?>
                <?php } ?>
            </section>
        </main>
        <nav class="menu-inferior">
            <a href="inicio.php" class="menu-bottom">
                <img src="../../public/img/2dashboard.png" alt="Início">
                <span>Início</span>
            </a>
            <a href="historico.php" class="menu-bottom ativo">
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