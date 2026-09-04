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

include_once("../models/Emprestimo.php");
$objEmprestimo = new Emprestimo();
$idAluno = $_SESSION["id_alunos"];
$emprestimos = $objEmprestimo->ListarEmprestimosPorAluno($idAluno);
$emprestimosAtivos = [];

foreach ($emprestimos as $emprestimo) {

    if ($emprestimo["status"] === "Emprestado") {
        $emprestimosAtivos[] = $emprestimo;
    }

}

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"content="width=device-width, initial-scale=1.0">
    <title>BeepYou - Devolver</title>
    <link rel="stylesheet" type="text/css" href="../../public/css/aluno.css">
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
        </header>
        <main class="conteudo-aluno">
            <section class="saudacao">
                <h1>Devolver patrimônio</h1>
                <p>Selecione o patrimônio que deseja devolver.</p>
            </section>
            <section class="historico">
                <?php if (empty($emprestimosAtivos)) { ?>
                <div class="historico-emprestimo">
                    <span class="nome-patrimonio">
                        Nenhum empréstimo ativo
                    </span>
                    <span class="data-devolucao">
                        Você não possui patrimônios para devolver.
                    </span>
                </div>
                <?php } else { ?>
                <form id="formDevolucao" method="GET" action="scan_patrimonio.php">
                    <input type="hidden" name="acao" value= "devolver">
                    <?php foreach ($emprestimosAtivos as $emprestimo) { ?>
                    <label class="item-selecao-emprestimo">
                        <input type="radio" name="id_emprestimo"
                        value="<?php echo $emprestimo["id_emprestimos"]; ?>" onchange="habilitarBotao()">
                        <div class="selecao-circulo">
                            <span>✓</span>
                        </div>
                        <div class="historico-emprestimo">
                            <span class="nome-patrimonio">
                                <?php  echo htmlspecialchars($emprestimo["nome_patrimonio"]); ?>
                            </span>
                            <span class="data-devolucao">
                                Patrimônio:
                                <?php echo htmlspecialchars( $emprestimo["codigo_patrimonio"] );?>
                            </span>
                            <span class="data-devolucao">
                                Devolver até:
                                <?php echo date( "d/m/Y", strtotime($emprestimo["data_prevista"]));?>
                            </span>
                        </div>
                    </label>
                    <?php } ?>
                    <button  type="submit" id="btnContinuarDevolucao" class="btn-emprestimo btn-emprestar" disabled >
                        Continuar
                    </button>
                </form>
                <?php } ?>
            </section>
        </main>
    </div>
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
    <script>
    function habilitarBotao()
    {
        const selecionado = document.querySelector(
            'input[name="id_emprestimo"]:checked'
        );

        const botao = document.getElementById(
            "btnContinuarDevolucao"
        );

        botao.disabled = !selecionado;
    }
    </script>
</body>
</html>