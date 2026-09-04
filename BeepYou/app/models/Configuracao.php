<?php

class Configuracao
{
    private $pdo;

    public function __construct()
    {
        include_once("Connect.php");

        $conexao = new Connect();
        $this->pdo = $conexao->conectarBanco();
    }


    public function BuscarDiasEmprestimo()
    {
        $sql = "SELECT dias_emprestimo FROM configuracoes LIMIT 1";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();

        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

        if($resultado)
        {
            return (int)$resultado["dias_emprestimo"];
        }
        return 7;
    }


    public function AlterarDiasEmprestimo($dias)
    {
        $sql = "UPDATE configuracoes  SET dias_emprestimo = :dias";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(":dias", $dias, PDO::PARAM_INT);
        return $stmt->execute();
    }
}
?>

