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

include_once("../models/Alunos.php");
include_once("../models/User.php");
include_once("../models/Patrimonio.php");
include_once("../models/Emprestimo.php");
include_once("../models/Configuracao.php");

/*USUÁRIOS*/

$objUsuario = new User();
$usuarios = $objUsuario->ListarTodosUsuarios();


/* PATRIMÔNIOS */

$objPatrimonio = new Patrimonio();
$patrimonios = $objPatrimonio->ListarTodosPatrimonios();


/* CONFIGURAÇÃO DE EMPRÉSTIMOS*/

$objConfiguracao = new Configuracao();
$diasEmprestimo = $objConfiguracao->BuscarDiasEmprestimo();

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">
    <title>BeepYou - Configurações</title>
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
                    </a> <div class="menu-grupo"> <div class="menu-item menu-toggle"> <img src="../../public/img/2student.png" alt="" class="menu-icone"> <span class="menu-texto">Alunos</span> </div>
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
                                <h2 class="titulo-pagina"><img src="../../public/img/1config.png" class="titulo-icone"> Configurações</h2>
                                <div class="acoes-topo">
                                    <a href="dashboard.php">
                                        <span class="icon-acao icon-voltar" title="Editar"></span>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="config-grid">
                            <section class="config-card">
                                <h3>Meu Perfil</h3>
                                <form action="../controllers/editarPerfil.php" method="POST" class="form-cadastro">
                                    <label for="nome"> Nome</label>
                                    <input type="text" name="nome" id="nome" class="campo-form" value="<?php echo $_SESSION['usuarios']; ?>" required>
                                    <label for="email">E-mail</label>
                                    <input type="email" name="email" id="email" class="campo-form" value="<?php echo $_SESSION['email']; ?>" required>
                                    <label for="telefone"> Telefone</label>
                                    <input type="text" name="telefone" id="telefone" class="campo-form" placeholder="Digite seu telefone">
                                    <label for="tipousuario">Tipo de usuário</label>
                                    <input type="text" class="campo-form" id="tipousuario" value="<?php echo $_SESSION['tipo_usuario']; ?>" readonly>
                                    <button type="submit" class="btn-salvar"> Salvar alterações </button>
                                </form>
                            </section>
                            <section class="config-card">
                                <h3> Segurança</h3>
                                <form action="../controllers/alterarSenha.php" method="POST"class="form-cadastro" autocomplete="new-password">
                                    <label for="nova_senha">Nova senha</label>
                                    <input type="password" id="nova_senha" name="nova_senha" class="campo-form" placeholder="Digite a nova senha" autocomplete="new-password" required>
                                    <label for="confirmar_senha">Confirmar nova senha</label>
                                    <input type="password" id="confirmar_senha" name="confirmar_senha" class="campo-form" placeholder="Confirme a nova senha" autocomplete="new-password" required>
                                    <button type="submit" class="btn-salvar"> Atualizar senha </button>
                                </form>
                            </section>
                            <?php if ($_SESSION["tipo_usuario"] == "Administrativo") { ?>
                                <section class="config-card">
                                    <h3>Gerenciamento de Usuários</h3>
                                    <form id="formUsuario" action="../controllers/cadastrarUsuario.php" method="POST" class="form-cadastro" autocomplete="off">
                                        <input type="hidden" name="id_usuario" id="id_usuario"  autocomplete="off">
                                        <label for="nome1"> Nome </label>
                                        <input type="text" name="nome" id="nome1" class="campo-form" placeholder="Nome do usuário" required autocomplete="off">
                                        <label for="email1"> E-mail </label>
                                        <input type="email" name="email" id="email1" class="campo-form" placeholder="E-mail do usuário" required autocomplete="off">
                                        <label for="tipo_usuario1"> Tipo de usuário </label>
                                        <select name="tipo_usuario" id="tipo_usuario1" class="campo-form">
                                            <option value="Leitor">Leitor</option>
                                            <option value="Aluno">Aluno</option>
                                            <option value="Administrativo">Administrativo</option>
                                        </select>
                                        <div class="acoes-usuarios">
                                            <button type="submit" id="btnUsuario" class="btn-salvar"> Cadastrar usuário </button> 
                                            <button type="button" id="btnCancelarEdicao" class="btn-salvar" onclick="cancelarEdicaoUsuario()" style="display:none;"> Cancelar </button>
                                            <button type="button" class="btn-salvar" onclick="mostrarUsuarios()"> Visualizar usuários </button>
                                        </div>
                                    </form>
                                </section>
                                <div id="tabelaUsuarios" class="tabela-usuarios">
                                    <section class="table-box">
                                        <table>
                                            <thead>
                                                <tr>
                                                    <th>Nome</th>
                                                    <th> E-mail</th>
                                                    <th>Tipo</th>
                                                    <th>Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($usuarios as $usuario): ?>
                                                <tr>
                                                    <td>
                                                        <a
                                                            href="javascript:void(0)"
                                                            class="link-visualizar"
                                                            onclick='editarUsuario(
                                                                <?= $usuario["id_usuarios"]; ?>,
                                                                <?= json_encode($usuario["nome"]); ?>,
                                                                <?= json_encode($usuario["email"]); ?>,
                                                                <?= json_encode($usuario["tipo_usuario"]); ?>
                                                            )'>
                                                            <?= htmlspecialchars($usuario["nome"]); ?>
                                                        </a>
                                                    </td>
                                                    <td><?= htmlspecialchars($usuario["email"]); ?></td>
                                                    <td><?= htmlspecialchars($usuario["tipo_usuario"] ?? ''); ?></td>
                                                    <td>
                                                        <label class="switch">
                                                            <input type="checkbox"
                                                                <?= ($usuario["ativo"]) ? "checked" : ""; ?>
                                                                onclick="return confirmarInativacaoUsuario(
                                                                    this,
                                                                    <?= $usuario['id_usuarios']; ?>
                                                                )">
                                                            <span class="slider"></span>
                                                        </label>
                                                    </td>
                                                </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </section>
                                </div>
                                <?php } ?>
                            <section class="config-card config-full">
                                <h3>Impressão QRCODE</h3>
                                <form action="../controllers/imprimirQRCODE.php" method="POST" class="form-cadastro" id="formQRCode">
                                    <div class="acoes-qrcode">
                                        <button type="button" class="btn-salvar" onclick="mostrarPatrimoniosQRCode()">Escolher patrimônio</button>
                                    </div>
                                    <div id="tabelaQRCode" class="tabela-usuarios">
                                        <section class="table-box">
                                            <table class="tabela-qrcode">
                                                <thead>
                                                    <tr>
                                                        <th>Código</th>
                                                        <th>Nome</th>
                                                        <th>Selecionar</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($patrimonios as $patrimonio) { ?>
                                                    <tr>
                                                        <td><?php echo $patrimonio["codigo"]; ?></td>
                                                        <td><?php echo $patrimonio["nome"]; ?></td>
                                                        <td>
                                                            <input type="checkbox" name="patrimonios[]"  value="<?php echo $patrimonio["id_patrimonio"]; ?>"
                                                                class="check-qrcode" onchange="habilitarImpressaoQRCode()"> 
                                                        </td>
                                                    </tr>
                                                    <?php } ?>
                                                </tbody>
                                            </table>
                                            <button type="submit" id="btnImprimirQRCode" class="btn-salvar" style="display:none;"> Imprimir QR Codes </button>
                                        </section>
                                    </div>
                                </form>
                            </section>
                            <section class="config-card">
                                <h3> Configuração de Empréstimos </h3> 
                                <form action="../controllers/salvarConfiguracao.php" method="POST" class="form-cadastro">
                                    <label for="dias_emprestimo"> Prazo para devolução </label>
                                    <div class="campo-dias">
                                        <input type="number" name="dias_emprestimo" id="dias_emprestimo" class="campo-form"  min="1" value="<?php echo $diasEmprestimo; ?>" required>
                                    </div>
                                    <button type="submit" class="btn-salvar"> Salvar configuração </button>
                                </form>
                            </section>

                            <section class="config-card">
                                <h3>Aparência</h3>
                                <form class="form-cadastro">
                                    <label for="tema">Tema</label>
                                    <select class="campo-form" id="tema" name="tema" onchange="alterarTema()">
                                        <option value="claro">Tema BeepYou</option>
                                        <option value="escuro">Tema Escuro BeepYou</option>
                                    </select>
                                    <label for="fonte">Estilo da fonte</label>
                                    <select class="campo-form" id="fonte" name="fonte" onchange="alterarFonte()">
                                        <option value="Segoe UI">Segoe UI (Padrão)</option>
                                        <option value="Arial">Arial</option>
                                        <option value="Verdana">Verdana</option>
                                        <option value="Tahoma">Tahoma</option>
                                        <option value="Georgia">Georgia</option>
                                    </select>                                       
                                <label>Cor principal</label>
                                    <div class="paleta-cores">
                                        <button type="button" class="cor-opcao" style="background:#112B6D"
                                            title="Azul BeepYou" onclick="selecionarCor('#112B6D')">
                                        </button>
                                        <button type="button" class="cor-opcao" style="background:#6A46F5"
                                            title="Roxo" onclick="selecionarCor('#6A46F5')">
                                        </button>
                                        <button type="button" class="cor-opcao" style="background:#2ECC71"
                                            title="Verde" onclick="selecionarCor('#2ECC71')">
                                        </button>
                                        <button type="button" class="cor-opcao" style="background:#E5801D"
                                            title="Laranja" onclick="selecionarCor('#E5801D')">
                                        </button>
                                        <button type="button" class="cor-opcao" style="background:#E91E63"
                                            title="Rosa" onclick="selecionarCor('#E91E63')">
                                        </button>
                                        <button type="button" class="cor-opcao" style="background:#0097A7"
                                            title="Azul Turquesa" onclick="selecionarCor('#0097A7')">
                                        </button>
                                        <button type="button" class="cor-opcao" style="background:#7B1FA2"
                                            title="Violeta" onclick="selecionarCor('#7B1FA2')">
                                        </button>
                                        <button type="button" class="cor-opcao" style="background:#C62828"
                                            title="Vermelho" onclick="selecionarCor('#C62828')">
                                        </button>
                                    </div>
                                    <div class="personalizar-cor">
                                        <input type="color" id="cor-personalizada" value="#112B6D" onchange="alterarCorPersonalizada()">
                                        <button type="button" class="btn-personalizar-cor"
                                            onclick="document.getElementById('cor-personalizada').click()"> Personalize sua cor
                                        </button>
                                    </div>                        
                                        <button button type="button" 
                                            onclick="restaurarCorPadrao()" class="btn-salvar"> Cor original
                                        </button>
                                    </div>
                                </form>
                            </section>
                        </div>
                    </section>
                </main>
            </div>
        </main>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="../../public/js/configuracoes.js"></script>
    <button id="btnVoltarTopo" title="Voltar ao topo" aria-label="Voltar ao topo">
        <img src="../../public/img/voltar.png" alt="Voltar ao topo">
    </button>
</body>
</html>