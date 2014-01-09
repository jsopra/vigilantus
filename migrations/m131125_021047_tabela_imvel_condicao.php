<?php

use yii\db\Migration;

class m131125_021047_tabela_imvel_condicao extends Migration
{
	public function safeUp()
	{
        $this->createTable('imovel_condicoes', array(
            'id' => 'pk',
            'municipio_id' => 'integer  not null references municipios(id)',
            'nome' => 'varchar not null',
            'exibe_nome' => 'boolean',
            'data_cadastro' => 'timestamp with time zone not null default now()',
            'data_atualizacao' => 'timestamp with time zone',
            'inserido_por' => 'integer not null references usuarios(id)',
            'atualizado_por' => 'integer references usuarios(id)',
        ));
        
        $this->insert('imovel_condicoes', array(
            'municipio_id' => 1,
            'nome' => 'Normal',
            'inserido_por' => 1,
        ));
        
        $this->insert('imovel_condicoes', array(
            'municipio_id' => 1,
            'nome' => 'Área de Foco',
            'inserido_por' => 1,
        ));
        
        $this->insert('imovel_condicoes', array(
            'municipio_id' => 1,
            'nome' => 'RG Lira',
            'inserido_por' => 1,
        ));
	}

	public function safeDown()
	{
		$this->dropTable('imovel_condicoes');
	}
}
