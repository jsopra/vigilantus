<?php

use yii\db\Migration;

class m131124_154248_tabela_tipo_imovel extends Migration
{
	public function safeUp()
	{
        $this->createTable('imovel_tipos', array(
            'id' => 'pk',
            'municipio_id' => 'integer  not null references municipios(id)',
            'nome' => 'varchar not null',
            'sigla' => 'varchar',
            'data_cadastro' => 'timestamp with time zone not null default now()',
            'data_atualizacao' => 'timestamp with time zone',
            'inserido_por' => 'integer not null references usuarios(id)',
            'atualizado_por' => 'integer references usuarios(id)',
            'excluido' => 'boolean not null default false',
            'excluido_por' => 'integer references usuarios(id)',
            'data_exclusao' => 'timestamp with time zone',
        ));
        
        $this->insert('imovel_tipos', array(
            'municipio_id' => 1,
            'nome' => 'Residencial',
            'sigla' => null,
            'inserido_por' => 1,
        ));
        
        $this->insert('imovel_tipos', array(
            'municipio_id' => 1,
            'nome' => 'Comercial',
            'sigla' => null,
            'inserido_por' => 1,
        ));
        
        $this->insert('imovel_tipos', array(
            'municipio_id' => 1,
            'nome' => 'Terreno Baldio',
            'sigla' => 'TB',
            'inserido_por' => 1,
        ));
        
        $this->insert('imovel_tipos', array(
            'municipio_id' => 1,
            'nome' => 'Pontos Estratégicos',
            'sigla' => 'PE',
            'inserido_por' => 1,
        ));
        
        $this->insert('imovel_tipos', array(
            'municipio_id' => 1,
            'nome' => 'Outros',
            'sigla' => null,
            'inserido_por' => 1,
        ));
	}

	public function safeDown()
	{
		$this->dropTable('imovel_tipos');
	}
}