<?php

use yii\db\Migration;

class m141113_102651_criando_tabela_denuncias extends Migration
{
    public function safeUp()
    {
    	$this->createTable('denuncias', [
    		'id' => 'pk',
    		'data_criacao' => 'timestamp without time zone not null default now()',
    		'municipio_id' => 'integer not null references municipios(id)',
    		'nome' => 'varchar',
    		'telefone' => 'varchar',
    		'bairro_id' => 'integer not null references bairros(id)',
    		'endereco' => 'varchar',
    		'imovel_id' => 'integer references imoveis(id)', /* required on scenario aprovacao */
    		'email' => 'varchar',
    		'pontos_referencia' => 'varchar',
    		'mensagem' => 'varchar',
    		'anexo' => 'varchar',
            'nome_original_anexo' => 'varchar',

    		'tipo_imovel' => 'integer', /* casa, apartamento, terreno, edificio publico, espaço comercial, jardim/praça, rua, outro */
    		'localizacao' => 'integer', /* interior, exterior */

    		'status' => 'integer not null' /* avaliação (default), aprovada, ... */ 
		]);
        /*
		$this->createTable('denuncias_entorno', [
			'id' => 'pk',
			'municipio_id' => 'integer not null references municipios(id)',
			'denuncia_id' => 'integer not null references denuncias(id)',
			'local' => 'integer not null' // terreno baldio, água parada, despejo de entulho, construção, construção abandonada, jardim particular, jardim publico, outro 
		]);
        */

        $this->createTable('denuncia_historico', [
            'id' => 'pk',
            'municipio_id' => 'integer not null references municipios(id)',
            'denuncia_id' =>  'integer not null references denuncias(id)',
            'data_hora' => 'timestamp not null default now()',
            'tipo' => 'integer not null', /* criação denúncias, alterãções de status */
            'observacoes' => 'varchar',
            'status_antigo' => 'integer',
            'status_novo' => 'integer',
            'usuario_id' => 'integer references usuarios(id)',
        ]);
    }

    public function safeDown()
    {
        echo "m141113_102651_criando_tabela_denuncias cannot be reverted.\n";
        return false;
    }
}
