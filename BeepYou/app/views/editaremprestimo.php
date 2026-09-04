<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (
    !isset($_SESSION["tipo_usuario"]) ||
    $_SESSION["tipo_usuario"] != "Administrativo"
) {
    header("Location: dashboard.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BeepYou - EDITAR EMPRÉSTIMO</title>
    <link rel="stylesheet" type="text/css" href="../../public/css/styles.css">
</head>
<body>
    <div class="container">
        <main class="dashboard-container">
            <div class="pagina-interna">
                <aside class="menu-lateral">
                    <div class="menu-logo">
                        <a href="dashboard.php">
                            <img src="../../public/img/1.png" alt="Logo BeepYou" class="imgtopo">
                        </a>
                    </div>             

                    <div class="menu-usuario">
                        <span class="menu-bemvindo"> Bem-vindo, </span>
                        <br>
                        <strong><?php echo $_SESSION['usuarios']; ?></strong>
                        <br>
                        <br>
                    </div>

                    <a href="dashboard.php"class="menu-item">
                        <img src="../../public/img/2dashboard.png" alt="" class="menu-icone"> 
                        <span class="menu-texto"> Início </span>
                    </a>
                     <div class="menu-grupo"> <div class="menu-item menu-toggle"> <img src="../../public/img/2student.png" alt="" class="menu-icone"> <span class="menu-texto">Alunos</span> </div>
                        <div class="submenu">
                            <a href="alunos.php" class="submenu-item">
                                <span class="submenu-linha">└</span>
                                <span>Visualizar alunos</span>
                            </a>

                            <a href="cadastroAluno.php" class="submenu-item">
                                <span class="submenu-linha">└</span>
                                <span>Cadastrar aluno</span>
                            </a>
                        </div>

                        </div> <div class="menu-grupo"> <div class="menu-item menu-toggle"> <img src="../../public/img/2object.png" alt="" class="menu-icone"> <span class="menu-texto">Patrimônio</span> </div>
                        <div class="submenu">
                            <a href="patrimonio.php" class="submenu-item">
                                <span class="submenu-linha">└</span>
                                <span>Visualizar patrimônios</span>
                            </a>

                            <a href="cadastropatrimonio.php" class="submenu-item">
                                <span class="submenu-linha">└</span>
                                <span>Cadastrar patrimônio</span>
                            </a>
                        </div>

                        </div> <div class="menu-grupo"> <div class="menu-item menu-toggle"> <img src="../../public/img/2rental.png" alt="" class="menu-icone"> <span class="menu-texto">Empréstimos</span> </div>
                        <div class="submenu">
                            <a href="emprestimos.php" class="submenu-item">
                                <span class="submenu-linha">└</span>
                                <span>Visualizar empréstimos</span>
                            </a>

                            <a href="cadastroemprestimo.php" class="submenu-item">
                                <span class="submenu-linha">└</span>
                                <span>Cadastrar empréstimo</span>
                            </a>
                        </div>
                    </div>              

                    <?php if ($_SESSION["tipo_usuario"] == "Administrativo") { ?>
                        <a href="../views/config.php" class="menu-item">
                            <img src="../../public/img/2settings.png" alt="" class="menu-icone">
                            <span class="menu-texto"> Configurações </span>
                        </a>
                    <?php } ?>
                    <a href="../controllers/logoff.php" class="menu-item menu-sair">
                        <img src="../../public/img/exit.png"  alt="" class="menu-icone">
                        <span class="menu-texto"> Sair </span>
                    </a>
                </aside>
                <main class="conteudo-pagina">
                      <button id="btnMenu" class="btn-menu" aria-label="Abrir menu">
                        ☰
                    </button>
                    <div class="area-busca">
                        <form class="pesquisa" action="../controllers/buscar.php" method="GET">
                            <div class="campo-pesquisa">
                                <input type="search" id="pesquisa" name="pesquisa" class="buscar" 
                                placeholder="Digite o que deseja pesquisar" required>
                                <button class="btn-lupa" type="submit">  
                                    <img src="../../public/img/iconpesquisa.png" class="icone-lupa" alt="Pesquisar">
                                </button>
                            </div>
                        </form>
                    </div>
                        <section class="table-box">
                            <div class="table-header">
                                <div class="titulo-area">
                                    <h2 class="titulo-pagina">
                                    <img src="../../public/img/1emprestimo.png" class="titulo-icone">Editar Empréstimo </h2>
                                    <a href="../views/patrimonio.php"> 
                                        <span class="icon-acao icon-voltar" title="Editar"></span>
                                    </a>
                                </div>
                            </div>
                            
                            
                            <form action="../controllers/editarEmprestimo.php" method="POST" class="form-cadastro">
                                <input type="hidden" name="id_emprestimos" value="<?php echo $emprestimo['id_emprestimos']; ?>">
                                <label for="alunos_idfk">Aluno</label>
                                <select name="alunos_idfk" id="alunos_idfk" class="campo-form" required><?php foreach($alunos as $aluno){ ?>
                                <option value="<?php echo $aluno['id_alunos']; ?>"<?php if($aluno['id_alunos'] == $emprestimo['alunos_idfk']) echo "selected"; ?>>
                                        <?php echo $aluno['nome']; ?></option>
                                        <?php } ?>
                                </select>
                                <label for="patrimonio_idfk">Patrimônio</label>
                                <select name="patrimonio_idfk" id="patrimonio_idfk" class="campo-form" required>
                                <?php foreach($patrimonios as $patrimonio){ ?>
                                <option value="<?php echo $patrimonio['id_patrimonio']; ?>"<?php if($patrimonio['id_patrimonio'] == $emprestimo['patrimonio_idfk']) echo "selected"; ?>>
                                <?php echo $patrimonio['nome']; ?></option>
                                <?php } ?>
                                </select>
                                <label for="data_emprestimo">Data do empréstimo</label>
                                <input type="date"  id="data_emprestimo" name="data_emprestimo" class="campo-form" value="<?php echo $emprestimo['data_emprestimo']; ?>" required>
                                <label for="data_prevista">Data prevista para devolução</label>
                                <input type="date" id="data_prevista" name="data_prevista" class="campo-form" value="<?php echo $emprestimo['data_prevista']; ?>" required>
                                <label for="observacao">Observação</label>
                                <input type="text" id="observacao" name="observacao" class="campo-form" value="<?php echo $emprestimo['observacao']; ?>">
                                <label for="status" onclick="document.getElementById('status').click();"> Status</label>
                                <select name="status" id="status" class="campo-form">
                                <option value="Emprestado" <?php if($emprestimo['status']=="Emprestado") echo "selected"; ?>> Emprestado</option>
                                <option value="Devolvido" <?php if($emprestimo['status']=="Devolvido") echo "selected"; ?>>Devolvido</option>
                                </select>
                                <button type="submit" class="btn-salvar">Salvar alterações </button>
                            </form>
                        </section>
                    </main>
                </div>
            </main>
        </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../../public/js/configuracoes.js"></script>
    <button id="btnVoltarTopo" title="Voltar ao topo" aria-label="Voltar ao topo">
        <img src="../../public/img/voltar.png" alt="Voltar ao topo">
    </button>
</body>
</html>