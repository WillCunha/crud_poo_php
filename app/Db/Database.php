<?php

namespace App\Db;

use \PDO;
use PDOException;

class Database
{

    /**
     * HOST DE CONEXÃO COM O BANCO
     * @var string
     */
    const HOST = 'localhost';

    /**
     * NOME DO BANCO DE DADOS
     * @var string
     */
    const NAME = 'wfvagas';

    /**
     * USUARIO DE ACESSO AO BANCO DE DADOS
     * @var string
     */
    const USER = 'root';

    /**
     * SENHA DO BANCO
     * @var string
     */
    const PASS = '';

    /**
     * Nome da tabela
     * @var string
     */
    private $table;


    /**
     * INSTANCIA DE CONEXÃO COM O BANCO
     * @var PDO
     */
    private $connection;

    /**
     * DEFINE A TABELA E INSTANCIA A CONEXÃO
     * @param string $table
     */
    public function __construct($table = null){
        $this->table = $table;
        $this->setConnection();
      }

    /**
     * 
     */
    private function setConnection()
    {
        try {
            $this->connection = new PDO('mysql:host='.self::HOST.';dbname='.self::NAME,self::USER,self::PASS);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die('ERROR: ' . $e->getMessage());
        }
    }

    /**
     * Faz a execução das queries dentro do banco de dados
     * @param string $query
     * @param array $params
     * @return PDOStatement
     */

    public function execute($query,$params = []){
        try {
            $statement = $this->connection->prepare($query);
            $statement->execute($params);

            return $statement;
        } catch (PDOException $e) {
            die('ERROR: ' . $e->getMessage());
        }
    }
    

    /**
     * Metodo que persiste os dados pro banco
     * @param array $values[ field => value ]
     * @return integer ID inserido
     */
    public function insert($values)
    {
        //dados da query
        $fields = array_keys($values);
        $binds = array_pad([], count($fields), '?' );

        //Monta a query insert
        $query = 'INSERT INTO '.$this->table.' ('.implode(',',$fields).') VALUES ('.implode(',',$binds).')';


        //executa a query
        $this->execute($query, array_values($values));

        //Retorna o último ID
        return $this->connection->lastInsertId();
    }

    /**
     * Método que faz a busca no banco
     * @param string where
     * @param string order
     * @param string limit
     * @param string fields
     * @return PDOStatement
     */
    public function select($where = null, $order = null, $limit = null, $fields = '*')
    {
        //Dados da query
        $where = strlen($where) ? 'WHERE '.$where : '';
        $order = strlen($order) ? 'ORDER '.$order : '';
        $limit = strlen($limit) ? 'LIMIT '.$limit : '';

        $query = 'SELECT '.$fields.' FROM '.$this->table.' ' . $where . ' ' .$order. ' ' . $limit . ' ';

        return $this->execute($query);
    }


    /**
     * Método que atualiza os dados
     * @param string $where
     * @param array $values
     * @return boolean
     */
    public function update($where, $values){

        //Dados da query
        $fields = array_keys($values);

        //Monta a query
        $query = 'UPDATE '.$this->table.' SET '.implode('=?,', $fields).'=? WHERE '.$where;
        
        //Executa a query
        $this->execute($query, array_values($values));

        return true;
    }
}
