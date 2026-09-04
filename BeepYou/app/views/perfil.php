<?php

session_start();

include_once("../models/Alunos.php");


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


$nome = $aluno["nome"] ?? "";
$email = $aluno["email"] ?? "";
$telefone = $aluno["telefone"] ?? "";

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BeepYou - Perfil</title>
    <link rel="stylesheet" type="text/css" href="../../public/css/aluno.css">
</head>
<body>
    <div class="app-aluno">
        <header class="topo-aluno">
            <a href="inicio.php">
                <img src="../../public/img/1.png" alt="Logo BeepYou" class="logo-aluno" >
            </a>
            <a href="../controllers/logoff.php" class="btn-sair">
                <span>Sair</span>
            </a>
        </header>
        <main class="conteudo-aluno">
        <section class="saudacao">
            <h1><?php echo htmlspecialchars($nome); ?>!</h1>
        </section>
        <section class="perfil-aluno">
            <div class="perfil-titulo">
                <h2>Meus dados</h2>
                <p>Confira e atualize seus dados cadastrais.</p>
            </div>
            <form action="../controllers/editarPerfilAluno.php" method="POST" class="form-perfil-aluno">
                <div class="campo-perfil">
                    <label for="nome">Nome</label>
                    <input type="text" id="nome" name="nome" value="<?php echo htmlspecialchars($nome); ?>" readonly>
                </div>
                <div class="campo-perfil">
                    <label for="email">E-mail</label>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
                </div>
                <div class="campo-perfil">
                    <label for="telefone">Telefone</label>
                    <input type="text" id="telefone" name="telefone" value="<?php echo htmlspecialchars($telefone); ?>">
                </div>
                <div class="perfil-acoes">
                    <button type="submit" class="btn-alterar-senha">
                        Salvar dados
                    </button>
                </div>
            </form>
        </section>
        <section class="perfil-aluno">
            <div class="perfil-titulo">
                <h2>Segurança</h2>
                <p>Altere sua senha para manter sua conta protegida.</p>
            </div>
            <form action="../controllers/alterarSenhaAluno.php" method="POST" class="form-perfil-aluno">
                <div class="campo-perfil">
                    <label for="nova_senha">Nova senha</label>
                    <input type="password" id="nova_senha" name="nova_senha" placeholder="Digite sua nova senha" autocomplete="new-password" required>
                </div>
                <div class="campo-perfil">
                    <label for="confirmar_senha">Confirmar nova senha</label>
                    <input type="password" id="confirmar_senha" name="confirmar_senha" placeholder="Digite novamente sua nova senha" autocomplete="new-password" required>
                </div>
                <div class="perfil-acoes">
                    <button type="submit" class="btn-alterar-senha">
                        Alterar senha
                    </button>
                </div>
            </form>
        </section>       
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