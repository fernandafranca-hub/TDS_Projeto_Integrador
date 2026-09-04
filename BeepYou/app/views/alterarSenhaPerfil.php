<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BeepYou - Alterar Senha</title>
    <link rel="stylesheet" type="text/css" href="../../public/css/styles.css">
</head>
<body>
    <div class="container">
        <main class="login-container">
            <div class="login-card">
                <header class="logo-area">
                    <img src="../../public/img/1.png" alt="LogoBeepYou">          
                </header>
                <h2>Alterar senha</h2>
                <form action="../controllers/alterarSenhaPerfil.php"  method="POST" class="form-login">
                    <label for="nova_senha"> Nova senha </label>
                    <input type="password" id="nova_senha" name="nova_senha" class="buscar"
                        placeholder="Digite sua nova senha" autocomplete="new-password" required>                    
                    <label for="confirmar_senha">Confirmar nova senha</label>
                    <input type="password" id="confirmar_senha" name="confirmar_senha" class="buscar"
                        placeholder="Confirme sua nova senha" autocomplete="new-password" required >
                    <button type="submit" class="btn-entrar">Alterar senha </button>
                    <div class="options">
                        <a href="perfil.php"> Voltar para o perfil</a>
                    </div>
                    <div class="options">
                        <span>
                            A senha deve conter:
                            <br>- 8 caracteres;
                            <br>- 1 letra maiúscula;
                            <br>- 1 número.
                        </span>
                    </div>
                </form>
            </div>
        </main>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../../public/js/configuracoes.js"></script>
</body>
</html>