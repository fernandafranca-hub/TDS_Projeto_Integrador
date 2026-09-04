window.addEventListener("load", function()
{
    carregarTema();
    carregarFonte();
    carregarCor();
});


function carregarTema()
{
    let tema = localStorage.getItem("tema");
    let seletorTema = document.getElementById("tema");

    if(tema == "escuro")
    {
        document.body.classList.add("dark");

        if(seletorTema)
        {
            seletorTema.value = "escuro";
        }
    }
    else
    {
        document.body.classList.remove("dark");

        if(seletorTema)
        {
            seletorTema.value = "claro";
        }
    }
}


function alterarTema()
{
    let seletorTema = document.getElementById("tema");

    if(!seletorTema)
    {
        return;
    }

    let tema = seletorTema.value;

    if(tema == "escuro")
    {
        document.body.classList.add("dark");
        localStorage.setItem("tema", "escuro");
    }
    else
    {
        document.body.classList.remove("dark");
        localStorage.setItem("tema", "claro");
    }
}


function carregarFonte()
{
    let fonte = localStorage.getItem("fonte");
    let seletorFonte = document.getElementById("fonte");

    if(fonte)
    {
        document.documentElement.style.setProperty(
            "--fonte-principal",
            fonte
        );

        if(seletorFonte)
        {
            seletorFonte.value = fonte;
        }
    }
}


function alterarFonte()
{
    let seletorFonte = document.getElementById("fonte");

    if(!seletorFonte)
    {
        return;
    }

    let fonte = seletorFonte.value;

    document.documentElement.style.setProperty(
        "--fonte-principal",
        fonte
    );

    localStorage.setItem("fonte", fonte);
}


function carregarCor()
{
    let cor = localStorage.getItem("cor");

    if(!cor)
    {
        cor = "#112B6D";
    }

    aplicarCor(cor);
}


function alterarCorPersonalizada()
{
    let seletor = document.getElementById("cor-personalizada");

    if(!seletor)
    {
        return;
    }

    let cor = seletor.value;

    aplicarCor(cor);
}


function selecionarCor(cor)
{
    aplicarCor(cor);
}


function restaurarCorPadrao()
{
    const corPadrao = "#112B6D";

    aplicarCor(corPadrao);
}


function aplicarCor(cor)
{
    document.documentElement.style.setProperty(
        "--cor-principal",
        cor
    );

    localStorage.setItem(
        "cor",
        cor
    );

    let seletor = document.getElementById(
        "cor-personalizada"
    );

    if(seletor)
    {
        seletor.value = cor;
    }

    let preview = document.getElementById(
        "cor-preview"
    );

    if(preview)
    {
        preview.style.backgroundColor = cor;
    }

    let nome = document.getElementById(
        "nome-cor"
    );

    if(nome)
    {
        nome.textContent = cor.toUpperCase();
    }

    atualizarCoresGraficos();
}


function editarUsuario(id, nome, email, tipo)
{
    document.getElementById("id_usuario").value = id;
    document.getElementById("nome1").value = nome;
    document.getElementById("email1").value = email;
    document.getElementById("tipo_usuario1").value = tipo;

    document.getElementById("btnUsuario").innerHTML =
        "Salvar alterações";

    document.getElementById("btnCancelarEdicao").style.display =
        "inline-block";

    document.getElementById("formUsuario").action =
        "../controllers/editarUsuario.php";

    document.getElementById("formUsuario").scrollIntoView({
        behavior: "smooth",
        block: "center"
    });
}


function cancelarEdicaoUsuario()
{
    document.getElementById("id_usuario").value = "";
    document.getElementById("nome1").value = "";
    document.getElementById("email1").value = "";

    let senha = document.getElementById("senha1");

    if(senha)
    {
        senha.value = "";
        senha.disabled = false;
        senha.placeholder = "Senha inicial";
    }

    document.getElementById("tipo_usuario1").value = "Leitor";

    document.getElementById("btnUsuario").innerHTML =
        "Cadastrar usuário";

    document.getElementById("btnCancelarEdicao").style.display =
        "none";

    document.getElementById("formUsuario").action =
        "../controllers/cadastrarUsuario.php";
}


function confirmarInativacaoUsuario(checkbox, id)
{
    let corPrincipal = getComputedStyle(document.documentElement)
        .getPropertyValue("--cor-principal")
        .trim();

    if(!checkbox.checked)
    {
        Swal.fire({
            title: "Tem certeza que deseja inativar o usuário?",
            text: "O usuário ficará inativo no sistema.",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: corPrincipal,
            cancelButtonColor: "#6A46F5",
            confirmButtonText: "Sim, inativar",
            cancelButtonText: "Cancelar"
        }).then((result) => {

            if(result.isConfirmed)
            {
                window.location.href =
                    "../controllers/inativarUsuario.php?id=" + id;
            }
            else
            {
                checkbox.checked = true;
            }

        });

        return false;
    }

    Swal.fire({
        title: "Deseja ativar este usuário?",
        text: "O usuário voltará a ficar disponível.",
        icon: "question",
        showCancelButton: true,
        confirmButtonColor: corPrincipal,
        cancelButtonColor: "#6A46F5",
        confirmButtonText: "Sim, ativar",
        cancelButtonText: "Cancelar"

    }).then((result) => {

        if(result.isConfirmed)
        {
            window.location.href =
                "../controllers/ativarUsuario.php?id=" + id;
        }
        else
        {
            checkbox.checked = false;
        }

    });

    return false;
}


function confirmarInativacaoAluno(checkbox, id, possuiEmprestimo)
{
    let corPrincipal = getComputedStyle(document.documentElement)
        .getPropertyValue("--cor-principal")
        .trim();

    if(!checkbox.checked)
    {
        if(possuiEmprestimo)
        {
            checkbox.checked = true;

            Swal.fire({
                title: "Aluno não pode ser inativado!",
                text: "Este aluno possui um empréstimo ativo e precisa realizar a devolução antes de ser inativado.",
                icon: "warning",
                confirmButtonColor: corPrincipal,
                confirmButtonText: "Entendi"
            });

            return false;
        }

        Swal.fire({
            title: "Tem certeza que deseja inativar o aluno?",
            text: "O aluno ficará inativo no sistema.",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: corPrincipal,
            cancelButtonColor: "#6A46F5",
            confirmButtonText: "Sim, inativar",
            cancelButtonText: "Cancelar"

        }).then((result) => {

            if(result.isConfirmed)
            {
                window.location.href =
                    "../controllers/inativarAluno.php?id=" + id;
            }
            else
            {
                checkbox.checked = true;
            }

        });

        return false;
    }

    Swal.fire({
        title: "Deseja ativar este aluno?",
        text: "O aluno voltará a ficar disponível.",
        icon: "question",
        showCancelButton: true,
        confirmButtonColor: corPrincipal,
        cancelButtonColor: "#6A46F5",
        confirmButtonText: "Sim, ativar",
        cancelButtonText: "Cancelar"

    }).then((result) => {

        if(result.isConfirmed)
        {
            window.location.href =
                "../controllers/ativarAluno.php?id=" + id;
        }
        else
        {
            checkbox.checked = false;
        }

    });

    return false;
}


function confirmarExclusao()
{
    let corPrincipal = getComputedStyle(document.documentElement)
        .getPropertyValue("--cor-principal")
        .trim();

    Swal.fire({
        title: "Tem certeza que deseja excluir este empréstimo?",
        text: "Esta ação não poderá ser desfeita.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: corPrincipal,
        cancelButtonColor: "#6A46F5",
        confirmButtonText: "Sim, excluir",
        cancelButtonText: "Cancelar"

    }).then((result) => {

        if(result.isConfirmed)
        {
            document.querySelector("#formExcluir").submit();
        }

    });

    return false;
}


function habilitarImpressaoQRCode()
{
    let checks = document.querySelectorAll(".check-qrcode");
    let botao = document.getElementById("btnImprimirQRCode");
    let selecionado = false;

    checks.forEach(function(check)
    {
        if(check.checked)
        {
            selecionado = true;
        }
    });

    if(selecionado)
    {
        botao.style.display = "block";
    }
    else
    {
        botao.style.display = "none";
    }
}


function mostrarUsuarios()
{
    const usuarios = document.getElementById("tabelaUsuarios");
    const patrimonio = document.getElementById("tabelaQRCode");

    patrimonio.style.display = "none";

    usuarios.style.display =
        usuarios.style.display === "block"
        ? "none"
        : "block";
}


function mostrarPatrimoniosQRCode()
{
    const usuarios = document.getElementById("tabelaUsuarios");
    const patrimonio = document.getElementById("tabelaQRCode");

    usuarios.style.display = "none";

    patrimonio.style.display =
        patrimonio.style.display === "block"
        ? "none"
        : "block";
}


function confirmarDevolucao(form)
{
    event.preventDefault();

    Swal.fire({
        title: "Confirmar devolução?",
        text: "O patrimônio será registrado como devolvido hoje.",
        icon: "question",
        showCancelButton: true,
        confirmButtonText: "Sim, devolver",
        cancelButtonText: "Cancelar",
        reverseButtons: true

    }).then((resultado) => {

        if(resultado.isConfirmed)
        {
            form.submit();
        }

    });

    return false;
}


function avisarEmprestimo(id)
{
    Swal.fire({
        title: "Avisar aluno",
        text: "Como deseja enviar o aviso de atraso?",
        icon: "warning",

        showDenyButton: true,
        showCancelButton: true,

        confirmButtonText:
            '<i class="fa-regular fa-envelope"></i> E-mail',

        denyButtonText:
            '<i class="fa-brands fa-whatsapp"></i> WhatsApp',

        cancelButtonText: "Cancelar",

        confirmButtonColor: "#112B6D",
        denyButtonColor: "#25D366"

    }).then((result) => {

        /* E-MAIL */

        if(result.isConfirmed)
        {
            const form = document.createElement("form");

            form.method = "POST";
            form.action = "../controllers/avisarEmprestimo.php";

            const input = document.createElement("input");

            input.type = "hidden";
            input.name = "id_emprestimo";
            input.value = id;

            form.appendChild(input);
            document.body.appendChild(form);

            form.submit();
        }

        /* WHATSAPP */

        else if(result.isDenied)
        {
            Swal.fire({
                title: "Aviso pelo WhatsApp",
                text: "Vamos abrir o WhatsApp com a mensagem pronta.",
                icon: "info",
                showCancelButton: true,
                confirmButtonText: "Abrir WhatsApp",
                cancelButtonText: "Cancelar",
                confirmButtonColor: "#25D366"

            }).then((confirmacao) => {

                if(confirmacao.isConfirmed)
                {
                    window.open(
                        "../controllers/whatsappEmprestimo.php?id=" + id,
                        "_blank"
                    );
                }

            });
        }
    });
}


function aplicarCor(cor)
{
    document.documentElement.style.setProperty(
        "--cor-principal",
        cor
    );

    localStorage.setItem(
        "cor",
        cor
    );

    let seletor = document.getElementById(
        "cor-personalizada"
    );

    if(seletor)
    {
        seletor.value = cor;
    }

    let preview = document.getElementById(
        "cor-preview"
    );

    if(preview)
    {
        preview.style.backgroundColor = cor;
    }

    let nome = document.getElementById(
        "nome-cor"
    );

    if(nome)
    {
        nome.textContent = cor.toUpperCase();
    }

    atualizarCoresGraficos();

    const btnTopo =
        document.getElementById("btnVoltarTopo");

    if(btnTopo)
    {
        btnTopo.style.setProperty(
            "background-color",
            cor,
            "important"
        );

        btnTopo.style.setProperty(
            "border-color",
            cor,
            "important"
        );
    }
}

function atualizarCoresGraficos()
{
    const estilos =
        getComputedStyle(document.documentElement);

    const corPrincipal =
        estilos
            .getPropertyValue("--cor-principal")
            .trim();

    if(!corPrincipal)
    {
        return;
    }

    if(typeof Chart === "undefined")
    {
        return;
    }

    const corSecundaria =
        `color-mix(in srgb, ${corPrincipal} 55%, #FFFFFF)`;

    if(Chart.instances)
    {
        Object.keys(Chart.instances).forEach(function(id)
        {
            const grafico = Chart.instances[id];

            if(!grafico)
            {
                return;
            }

            grafico.data.datasets.forEach(function(dataset)
            {
                if(!dataset)
                {
                    return;
                }

                dataset.backgroundColor = [
                    corPrincipal,
                    corSecundaria
                ];

                dataset.borderColor = "#ffffff";
                dataset.borderWidth = 3;
            });

            grafico.update();
        });
    }
}


let leitorQR = null;
let leitorIniciando = false;


function iniciarLeitorQR()
{
    const leitor = document.getElementById("reader");

    if(!leitor)
    {
        return;
    }

    if(typeof Html5Qrcode === "undefined")
    {
        console.error("Biblioteca Html5Qrcode não foi carregada.");
        return;
    }

    if(leitorQR || leitorIniciando)
    {
        return;
    }

    leitorIniciando = true;

    leitorQR = new Html5Qrcode("reader");

    leitorQR.start(
        {
            facingMode: {
                ideal: "environment"
            }
        },
        {
            fps: 10,

            qrbox: function(
                largura,
                altura
            )
            {
                const tamanho = Math.min(
                    largura,
                    altura,
                    250
                );

                return {
                    width: tamanho,
                    height: tamanho
                };
            },

            aspectRatio: 1.0
        },

        function(codigoLido)
        {
            console.log(
                "QR Code encontrado:",
                codigoLido
            );

            const campoCodigo =
                document.getElementById("codigo");

            const formulario =
                document.getElementById("formCodigo");

            if(campoCodigo)
            {
                campoCodigo.value = codigoLido;
            }

            pararLeitorQR();

            if(formulario)
            {
                formulario.submit();
            }
        },

        function(erro)
        {
            /* enquanto a câmera procura o QR Code */
        }
    )
    .then(function()
    {
        leitorIniciando = false;

        console.log(
            "Leitor QR Code iniciado com sucesso."
        );
    })
    .catch(function(erro)
    {
        leitorIniciando = false;
        leitorQR = null;

        console.error(
            "Erro ao iniciar câmera:",
            erro
        );

        alert(
            "Não foi possível acessar a câmera. " +
            "Verifique se você permitiu o acesso à câmera."
        );
    });
}


function pararLeitorQR()
{
    if(!leitorQR)
    {
        return;
    }

    leitorQR.stop()
        .then(function()
        {
            leitorQR.clear();

            leitorQR = null;
        })
        .catch(function(erro)
        {
            console.log(
                "Erro ao parar leitor:",
                erro
            );

            leitorQR = null;
        });
}


document.addEventListener(
    "DOMContentLoaded",
    function()
    {
        const leitor =
            document.getElementById("reader");

        if(leitor)
        {
            iniciarLeitorQR();
        }
    }
);


window.addEventListener(
    "beforeunload",
    function()
    {
        if(leitorQR)
        {
            leitorQR.stop().catch(function()
            {
                // Ignora erro ao sair da página.
            });
        }
    }
);


document.addEventListener("DOMContentLoaded", function()
{
    const btnMenu = document.getElementById("btnMenu");
    const menu = document.querySelector(".menu-lateral");

    if(!btnMenu || !menu)
    {
        return;
    }

    /* Cria o fundo escuro */
    const overlay = document.createElement("div");
    overlay.classList.add("menu-overlay");
    document.body.appendChild(overlay);

    /* Abrir / fechar menu */
    btnMenu.addEventListener("click", function()
    {
        menu.classList.toggle("menu-aberto");
        overlay.classList.toggle("ativo");

        if(menu.classList.contains("menu-aberto"))
        {
            btnMenu.innerHTML = "✕";
            btnMenu.setAttribute(
                "aria-label",
                "Fechar menu"
            );
        }
        else
        {
            btnMenu.innerHTML = "☰";
            btnMenu.setAttribute(
                "aria-label",
                "Abrir menu"
            );
        }
    });

    /* Fechar clicando no fundo */
    overlay.addEventListener("click", function()
    {
        menu.classList.remove("menu-aberto");
        overlay.classList.remove("ativo");

        btnMenu.innerHTML = "☰";
        btnMenu.setAttribute(
            "aria-label",
            "Abrir menu"
        );
    });

    /* Fechar menu quando clicar em um link */
    menu.querySelectorAll("a").forEach(function(link)
    {
        link.addEventListener("click", function()
        {
            menu.classList.remove("menu-aberto");
            overlay.classList.remove("ativo");

            btnMenu.innerHTML = "☰";
            btnMenu.setAttribute(
                "aria-label",
                "Abrir menu"
            );
        });
    });

    /* Submenus */
    document.querySelectorAll(".menu-toggle").forEach(function(botao)
    {
        botao.addEventListener("click", function(event)
        {
            if(event.target.closest(".menu-link"))
            {
                return;
            }

            const grupo = this.closest(".menu-grupo");

            if(grupo)
            {
                grupo.classList.toggle("aberto");
            }
        });
    });
});


document.addEventListener(
    "DOMContentLoaded",
    function()
    {
        const btnTopo =
            document.getElementById("btnVoltarTopo");

        if(!btnTopo)
        {
            return;
        }

        window.addEventListener(
            "scroll",
            function()
            {
                if(window.scrollY > 300)
                {
                    btnTopo.classList.add("mostrar");
                }
                else
                {
                    btnTopo.classList.remove("mostrar");
                }
            }
        );

        btnTopo.addEventListener(
            "click",
            function()
            {
                window.scrollTo({
                    top: 0,
                    behavior: "smooth"
                });
            }
        );
    }
);
