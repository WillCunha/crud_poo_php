<?php

namespace App\Entidade;

use \App\Db\Database;
use \PDO;

class Vaga{

    /** 
     * Identificador único
     * @var integer
    */
    public $id;

    /**
     * Titulo da vaga
     * @var string
     */
    public $titulo;

    /**
     * Descricao da vaga
     * @var string
     */
    public $descricao;
    
    /**
     * Define o status da vaga
     * @var string (s/n)
     */
    public $ativo;

    /**
     * Data da publicação
     * @var string
     */
    public $data;

    /**
     * CADASTRA UMA NOVA VAGA
     * @return boolean
     */
    public function cadastrar()
    {

        //DEFINE A DATA
        $this->data = date('Y-m-d H:i:s');

        //INSERE A VAGA
        $obDatabase = new Database('disponivel');
        $this->id = $obDatabase->insert([
            'titulo' => $this->titulo,
            'descricao' => $this->descricao,
            'ativo' => $this->ativo,
            'data' => $this->data
        ]);
            
        
        return true;

    }

    /**
     * Método responsável por atualizações das vagas
     * @return boolean
     */
    public function atualizar(){
        return (new Database('disponivel'))->update('id = ' .$this->id, [
            'titulo' => $this->titulo,
            'descricao' => $this->descricao,
            'ativo' => $this->ativo,
            'data' => $this->data
        ]);
    }

    /**
     * Método responsável por buscar as vagas
     * @param string $where
     * @param string $order
     * @param string $limit
     * @return array  PDOStatement
     */
    public static function getVagas($where = null, $order = null, $limit = null)
    {
        return (new Database('disponivel'))->select($where,$order,$limit)
        ->fetchAll(PDO::FETCH_CLASS,self::class);
    }

    /**
     * Método responsável por buscar a vaga especifica
     * @param integer $id
     * @return Vaga
     */
    public static function getVaga($id){
        return (new Database('disponivel'))->select('id = ' .$id)
                                ->fetchObject(self::class);
    }
}