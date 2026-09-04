<?php

session_start();

if (
    !isset($_SESSION["id_usuarios"]) ||
    !isset($_SESSION["id_alunos"]) ||
    $_SESSION["tipo_usuario"] != "Aluno"
) {
    header("Location: ../../index.html");
    exit();
}

if (!isset($_SESSION["patrimonio_confirmacao"])) {
    header("Location: inicio.php");
    exit();
}

$patrimonio = $_SESSION["patrimonio_confirmacao"];


$nome = $patrimonio["nome"] ?? "";
$codigo = $patrimonio["codigo"] ?? "";
$categoria = $patrimonio["categoria"] ?? "";
$descricao = $patrimonio["descricao"] ?? "";
$status = $patrimonio["status"] ?? "";

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BeepYou - Confirmar empréstimo</title>
    <link rel="stylesheet" type="text/css" href="../../public/css/aluno.css">
</head>
<body>
    <div class="app-aluno">
        <header class="topo-aluno">
            <a href="inicio.php">
                <img src="../../public/img/1.png" alt="Logo BeepYou" class="logo-aluno">
            </a>
            <a href="inicio.php" class="btn-sair">
                <span>Cancelar</span>
            </a>
        </header>
        <main class="conteudo-aluno">
            <section class="saudacao">
                <span>Confira o patrimônio</span>
                <h1>Confirmar empréstimo</h1>
                <p>Verifique se este é o patrimônio que você deseja emprestar.</p>
            </section>
            <section class="historico">
                <div class="emprestimo-card">
                    <div class="emprestimo-info">
                        <h3><?php echo htmlspecialchars($nome);?></h3>
                        <span>Patrimônio: <?php echo htmlspecialchars($codigo);?></span>
                        <?php if (!empty($categoria)) { ?>
                        <span> Categoria:<?php echo htmlspecialchars($categoria);?></span>
                        <?php } ?>
                        <?php if (!empty($descricao)) { ?>
                        <span> Descrição:<?php echo htmlspecialchars($descricao);?></span>
                        <?php } ?>
                        <div class="emprestimo-dados">
                            <span class="status ativo"> ● <?php echo htmlspecialchars($status);?></span>
                        </div>
                    </div>
                </div>
            </section>
            <section class="acoes-emprestimo">
                <div class="card-emprestimo">
                    <p style="text-align:center;"> Este é o patrimônio correto?</p>
                    <div class="botoes-emprestimo">
                        <a href="cancelarConfirmacaoEmprestimo.php" class="btn-emprestimo btn-devolver"
                            style="text-decoration:none; text-align:center;"> Não, cancelar
                        </a>
                        <form method="POST" action="../controllers/emprestarAluno.php" style="width:100%;" >
                            <input type="hidden" name="codigo" value="<?php echo htmlspecialchars($codigo);?>">
                            <button type="submit" class="btn-emprestimo btn-emprestar">
                                Sim, confirmar empréstimo
                            </button>
                        </form>
                    </div>
                </div>
            </section>
        </main>
        <nav class="menu-inferior">
            <a href="inicio.php" class="menu-bottom ativo" >
                <img src="../../public/img/2dashboard.png" alt="Início" >
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