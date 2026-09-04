<?php

class Patrimonio
{
    private $pdo;

    public function __construct()
    {
        include_once("Connect.php");
        $conexao = new Connect();
        $this->pdo = $conexao->conectarBanco();
    }


    public function CadastrarPatrimonio($codigo, $nome, $categoria,  $descricao, $quantidade, $status, $usuarios_idfk) 
    {
        $sql = "INSERT INTO patrimonio(codigo, nome, categoria, descricao, quantidade, status, usuarios_idfk)
        VALUES(:codigo, :nome, :categoria, :descricao, :quantidade, :status, :usuarios) RETURNING id_patrimonio";

        $stmt = $this->pdo->prepare($sql);

        $stmt->bindValue(":codigo", $codigo);
        $stmt->bindValue(":nome", $nome);
        $stmt->bindValue(":categoria", $categoria);
        $stmt->bindValue(":descricao", $descricao);
        $stmt->bindValue(":quantidade", $quantidade);
        $stmt->bindValue(":status", $status);
        $stmt->bindValue(":usuarios", $usuarios_idfk);

        if ($stmt->execute()) {
            return $stmt->fetchColumn();
        }
        return false;
    }


    public function ListarTodosPatrimonios()
    {
        $sql = "SELECT * FROM patrimonio WHERE usuarios_idfk = :usuarios ORDER BY id_patrimonio DESC";

        $stmt = $this->pdo->prepare($sql);

        $stmt->bindValue(":usuarios", $_SESSION["id_usuarios"]);

        if ($stmt->execute()) 
        {
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        return [];
    }


    public function ListarPatrimoniosDisponiveis()
    {
        $sql = "SELECT * FROM patrimonio WHERE usuarios_idfk = :usuarios AND status = 'Disponível' ORDER BY nome ASC";

        $stmt = $this->pdo->prepare($sql);

        $stmt->bindValue(":usuarios", $_SESSION["id_usuarios"]);

        if ($stmt->execute()) 
        {
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        return [];
    }



    public function BuscarPatrimonioPorId($id)
    {
        $sql = "SELECT * FROM patrimonio WHERE id_patrimonio = :id AND usuarios_idfk = :usuarios";

        $stmt = $this->pdo->prepare($sql);

        $stmt->bindValue(":id", $id);
        $stmt->bindValue(":usuarios", $_SESSION["id_usuarios"]);

        if ($stmt->execute()) 
        {
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }
        return false;
    }


    public function BuscarPatrimonioPorIdSemUsuario($id)
    {
        $sql = "SELECT * FROM patrimonio WHERE id_patrimonio = :id";

        $stmt = $this->pdo->prepare($sql);

        $stmt->bindValue(":id", $id);

        if ($stmt->execute()) 
        {
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }
        return false;
    }


    public function BuscarPatrimonioPorCodigo($codigo)
    {
        $sql = "SELECT * FROM patrimonio WHERE codigo = :codigo AND usuarios_idfk = :usuarios";

        $stmt = $this->pdo->prepare($sql);

        $stmt->bindValue(":codigo", $codigo);
        $stmt->bindValue(":usuarios", $_SESSION["id_usuarios"]);

        if ($stmt->execute()) 
        {
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }
        return false;
    }


    public function BuscarPatrimonioPorCodigoSemUsuario($codigo)
    {
        $sql = "SELECT * FROM patrimonio WHERE codigo = :codigo LIMIT 1";

        $stmt = $this->pdo->prepare($sql);

        $stmt->bindValue(":codigo", $codigo);

        if ($stmt->execute()) 
        {
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }
        return false;
    }


    public function EditarPatrimonio($id, $codigo, $nome, $categoria, $descricao, $quantidade, $status) 
    {
        $sql = "UPDATE patrimonio SET codigo = :codigo, nome = :nome, categoria = :categoria, descricao = :descricao, quantidade = :quantidade,
        status = :status WHERE id_patrimonio = :id AND usuarios_idfk = :usuarios";

        $stmt = $this->pdo->prepare($sql);

        $stmt->bindValue(":codigo", $codigo);
        $stmt->bindValue(":nome", $nome);
        $stmt->bindValue(":categoria", $categoria);
        $stmt->bindValue(":descricao", $descricao);
        $stmt->bindValue(":quantidade", $quantidade);
        $stmt->bindValue(":status", $status);
        $stmt->bindValue(":id", $id);

        $stmt->bindValue(":usuarios", $_SESSION["id_usuarios"]);

        return $stmt->execute();
    }


    public function AlterarStatusPatrimonio($id, $status)
    {
        $sql = "UPDATE patrimonio SET status = :status WHERE id_patrimonio = :id";

        $stmt = $this->pdo->prepare($sql);

        $stmt->bindValue(":status", $status);
        $stmt->bindValue(":id", $id);

        return $stmt->execute();
    }


   

    public function ExcluirPatrimonio($id)
    {
        $sql = "DELETE FROM patrimonio WHERE id_patrimonio = :id AND usuarios_idfk = :usuarios";

        $stmt = $this->pdo->prepare($sql);

        $stmt->bindValue(":id", $id);
        $stmt->bindValue(":usuarios", $_SESSION["id_usuarios"]);

        return $stmt->execute();
    }


    public function TotalPatrimonio()
    {
        $sql = "SELECT COUNT(*) AS total FROM patrimonio WHERE usuarios_idfk = :usuarios";

        $stmt = $this->pdo->prepare($sql);

        $stmt->bindValue(":usuarios", $_SESSION["id_usuarios"]);

        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC)["total"];
    }


    public function TotalPatrimonioStatus()
    {
        $sql = "SELECT status, COUNT(*) AS total FROM patrimonio WHERE usuarios_idfk = :usuarios GROUP BY status";

        $stmt = $this->pdo->prepare($sql);

        $stmt->bindValue(":usuarios", $_SESSION["id_usuarios"]);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


   
    public function BuscarPatrimoniosSelecionados($ids)
    {
        if (empty($ids)) 
        {
            return [];
        }

        $placeholders = implode(",", array_fill(0, count($ids), "?"));

        $sql = "SELECT * FROM patrimonio WHERE id_patrimonio IN ($placeholders) AND usuarios_idfk = ? ORDER BY nome ASC";

        $stmt = $this->pdo->prepare($sql);
        $contador = 1;
        foreach ($ids as $id) 
        {
            $stmt->bindValue($contador, $id);
            $contador++;
        }

        $stmt->bindValue($contador, $_SESSION["id_usuarios"]);

        if ($stmt->execute()) 
        {
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        return [];
    }



public function ListarPatrimoniosDisponiveisAluno()
{
    $sql = "SELECT * 
            FROM patrimonio
            WHERE status = 'Disponível'
            ORDER BY nome ASC";

    $stmt = $this->pdo->prepare($sql);

    if ($stmt->execute()) 
    {
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    return [];
}

}