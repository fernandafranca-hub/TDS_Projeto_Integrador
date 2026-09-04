
<?php

function mostrarAlerta($icone, $titulo, $texto = "", $acao = "")
{
?>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        let corPrincipal = "#112B6D";
        switch(localStorage.getItem("cor"))
        {
            case "Roxo":
                corPrincipal = "#6A46F5";
                break;

            case "Verde":
                corPrincipal = "#2ECC71";
                break;

            case "Laranja":
                corPrincipal = "#E5801D";
                break;

            default:
                corPrincipal = "#112B6D";
        }

        Swal.fire({
            icon: "<?php echo $icone; ?>",
            title: "<?php echo $titulo; ?>",
            text: "<?php echo $texto; ?>",
            confirmButtonColor: corPrincipal,
            cancelButtonColor: "#6c757d"
        }).then(() =>
        {
            <?php
            if($acao == "voltar")
            {
            ?>
                history.back();
            <?php
            }
            else if($acao != "")
            {
            ?>
                window.location.href = "<?php echo $acao; ?>";
            <?php
            }
            ?>
        });
    </script>
    <?php
    exit();
}

?>