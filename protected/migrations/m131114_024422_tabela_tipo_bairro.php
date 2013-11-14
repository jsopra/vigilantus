<?php

class m131114_024422_tabela_tipo_bairro extends CDbMigration
{
	public function up()
	{
        $this->createTable('bairro_tipos', array(
            'id' => 'pk',
            'municipio_id' => 'integer  not null references municipios(id)',
            'nome' => 'varchar not null',
            'data_cadastro' => 'timestamp with time zone not null default now()',
            'data_atualizacao' => 'timestamp with time zone',
            'inserido_por' => 'integer not null references usuarios(id)',
            'atualizado_por' => 'integer references usuarios(id)',
        ));
        
        $this->insert('bairro_tipos', array(
            'municipio_id' => 1,
            'nome' => 'Urbano',
            'inserido_por' => 1,
        ));
        
        $this->insert('bairro_tipos', array(
            'municipio_id' => 1,
            'nome' => 'Rural',
            'inserido_por' => 1,
        ));
	}

	public function down()
	{
		echo "m131114_024422_tabela_tipo_bairro does not support migration down.\n";
		return false;
	}

	/*
	// Use safeUp/safeDown to do migration with transaction
	public function safeUp()
	{
	}

	public function safeDown()
	{
	}
	*/
}