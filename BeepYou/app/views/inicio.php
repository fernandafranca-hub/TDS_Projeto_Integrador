```php
<?php

session_start();

include_once("../models/Alunos.php");
include_once("../models/Emprestimo.php");
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

$nomeAluno = $aluno["nome"] ?? "";


/* =====================================================
   PATRIMÔNIOS DISPONÍVEIS
   ===================================================== */

$objPatrimonio = new Patrimonio();

$patrimoniosDisponiveis =
    $objPatrimonio->ListarPatrimoniosDisponiveisAluno();

if (!is_array($patrimoniosDisponiveis)) {
    $patrimoniosDisponiveis = [];
}

$totalPatrimoniosDisponiveis =
    count($patrimoniosDisponiveis);


/* =====================================================
   EMPRÉSTIMOS DO ALUNO
   ===================================================== */

$objEmprestimo = new Emprestimo();

$emprestimos = $objEmprestimo->ListarEmprestimosPorAluno(
    $_SESSION["id_alunos"]
);

if (!is_array($emprestimos)) {
    $emprestimos = [];
}

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>BeepYou - Início</title>

    <link
        rel="stylesheet"
        type="text/css"
        href="../../public/css/aluno.css"
    >

</head>

<body>

    <div class="app-aluno">


        <!-- =================================================
             TOPO
             ================================================= -->

        <header class="topo-aluno">

            <a href="inicio.php">

                <img
                    src="../../public/img/1.png"
                    alt="Logo BeepYou"
                    class="logo-aluno"
                >

            </a>


            <a
                href="../controllers/logoff.php"
                class="btn-sair"
            >
                <span>Sair</span>
            </a>

        </header>


        <!-- =================================================
             CONTEÚDO
             ================================================= -->

        <main class="conteudo-aluno">


            <!-- SAUDAÇÃO -->

            <section class="saudacao">

                <span>Olá,</span>

                <h1>
                    <?php
                    echo htmlspecialchars($nomeAluno);
                    ?>!
                </h1>

            </section>


            <!-- =================================================
                 AÇÕES DE EMPRÉSTIMO
                 ================================================= -->

            <section class="acoes-emprestimo">

                <div class="card-emprestimo">

                    <div class="botoes-emprestimo">


                        <button
                            type="button"
                            class="btn-emprestimo btn-emprestar"
                            onclick="abrirLeitorEmprestimo()"
                        >
                            Emprestar
                        </button>


                        <button
                            type="button"
                            class="btn-emprestimo btn-devolver"
                            onclick="abrirLeitorDevolucao()"
                        >
                            Devolver
                        </button>


                    </div>

                </div>

            </section>


            <!-- =================================================
                 ACESSO AOS PATRIMÔNIOS DISPONÍVEIS
                 ================================================= -->

            <section class="acesso-patrimonios">

                <a
                    href="patrimoniosDisponiveis.php"
                    class="card-acesso-patrimonios"
                >


                    <div class="icone-acesso-patrimonios">

                        <span>✓</span>

                    </div>


                    <div class="texto-acesso-patrimonios">

                        <h2>
                            Patrimônios disponíveis
                        </h2>

                        <p>
                            Consulte os itens disponíveis
                            para empréstimo
                        </p>

                    </div>


                    <div class="info-acesso-patrimonios">

                        <strong>
                            <?php
                            echo $totalPatrimoniosDisponiveis;
                            ?>
                        </strong>

                        <span>
                            disponível<?php
                            echo $totalPatrimoniosDisponiveis == 1
                                ? ""
                                : "is";
                            ?>
                        </span>

                    </div>


                    <div class="seta-acesso-patrimonios">
                        ›
                    </div>
                </a>
            </section>
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
    <script>
    function abrirLeitorEmprestimo()
    {
        window.location.href =
            "scan_patrimonio.php?acao=emprestar";
    }

    function abrirLeitorDevolucao()
    {
        window.location.href =
            "devolver.php";
    }
    </script>
</body>
</html>

