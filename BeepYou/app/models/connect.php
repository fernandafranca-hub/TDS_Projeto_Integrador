<?php
class Connect
{
    private $host; //endereço onde o servidor de banco de dados esta instalado.
    private $dbname; // nome do database base de dados que iremos utilizar
    private $password; // senha do meu banco de dados
    private $user; // é o usuario do banco de dados no postgre é admin
    private $port; //  porta onde o banco de dados esta sendo executado o padrão do postgre é 5432

    function __construct()
    {
        $this->host = "localhost";
        $this->dbname = "BeepYou";
        $this->password = "123456";
        $this->user = "postgres";
        $this->port = "5432";
    }

    public function conectarBanco()
    {
        try
        {
            $PDO = new PDO("pgsql:host=".$this->host.";port=".$this->port.";dbname=".$this->dbname,$this->user,$this->password);            
            return($PDO);
        }
        catch(PDOException $erro)
        {
            $msg = "Falha no acesso com postGres: ".$erro->getMessage();
            echo $msg;
            return(NULL);
        }
    }
}

?>