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

$acao = $_GET["acao"] ?? "";

if (
    $acao !== "emprestar" &&
    $acao !== "devolver"
) {
    header("Location: inicio.php");
    exit();
}


$idEmprestimo = $_GET["id_emprestimo"] ?? "";


if (
    $acao === "devolver" &&
    empty($idEmprestimo)
) {
    header("Location: devolver.php");
    exit();
}

$titulo = $acao === "emprestar"
    ? "Escanear patrimônio"
    : "Devolver patrimônio";

$descricao = $acao === "emprestar"
    ? "Aponte a câmera para o QR Code do patrimônio."
    : "Aponte a câmera para o QR Code do patrimônio que você deseja devolver.";

$paginaVoltar = $acao === "emprestar"
    ? "inicio.php"
    : "devolver.php";

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> BeepYou - <?php echo htmlspecialchars($titulo); ?> </title>
    <link rel="stylesheet" type="text/css"  href="../../public/css/aluno.css">
</head>
<body>
    <div class="app-aluno">
        <header class="topo-aluno topo-scan">
            <a href="<?php echo htmlspecialchars($paginaVoltar); ?>" class="btn-voltar-scan" title="Voltar">
                <img src="../../public/img/iconvoltar.png" alt="Voltar">
            </a>
        </header>
        <main class="conteudo-aluno">
            <section class="scan-pagina">
                <div class="scan-card">
                    <div class="scan-cabecalho">
                        <h1> <?php echo htmlspecialchars($titulo); ?> </h1>
                        <p><?php echo htmlspecialchars($descricao); ?></p>
                    </div>
                    <div class="leitor-qr">
                        <div id="reader"></div>
                    </div>
                    <div class="separador-scan">
                        <span> ou digite o código </span>
                    </div>
                    <form id="formCodigo" method="POST" action="<?php
                            echo $acao === "emprestar"
                            ? "../controllers/emprestarAluno.php"
                            : "../controllers/devolverAluno.php";?>">
                        <input type="hidden" name="acao" value="<?php echo htmlspecialchars($acao); ?>">
                        <?php if ($acao === "devolver") { ?>                       
                        <input type="hidden"  name="id_emprestimo"  value="<?php echo htmlspecialchars($idEmprestimo); ?>">
                        <?php } ?>
                        <div class="campo-codigo-scan">
                            <label for="codigo">Código do patrimônio</label>
                            <input type="text" id="codigo"  name="codigo" placeholder="Digite o código" autocomplete="off" required>
                        </div>
                        <button type="submit" class="btn-emprestimo btn-devolver">
                            Confirmar código
                        </button>
                    </form>
                    <p class="mensagem-scan">
                        Caso a câmera não consiga ler o QR Code, digite o código exibido na etiqueta.
                    </p>
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
    <script src="https://unpkg.com/html5-qrcode"></script>
    <script>
    document.addEventListener(
        "DOMContentLoaded",
        function ()
        {
            const reader =
                new Html5Qrcode("reader");

            reader.start(
                {
                    facingMode: "environment"
                },

                {
                    fps: 10,
                    qrbox: 250
                },
                function (decodedText)
                {

                    document.getElementById(
                        "codigo"
                    ).value = decodedText;


                    reader.stop();

                },
                function (errorMessage)
                {
                    
                }

            )
            .catch(
                function (err)
                {
                    alert(
                        "Não foi possível acessar a câmera. Verifique se você permitiu o acesso à câmera."
                    );
                    console.error(err);
                }
            );
        }
    );
    </script>
</body>
</html>