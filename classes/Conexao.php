<?php

abstract class Conexao
{
	protected $db_connection = null;

    protected function __construct()
    {
        // se a conexão já existe
        if ($this->db_connection != null) {
            return true;
        } else {
            try {
                // conexão com o banco SQL usando PDO
                $this->db_connection = new PDO("sqlsrv:Server=".DB_HOST.";Database=".DB_NAME, DB_USER, DB_PASS);
                $this->db_connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                return true;
            } catch (PDOException $e) {
                echo $e->getMessage();
            }
        }

        // retorno padrão
        return false;
    }
}