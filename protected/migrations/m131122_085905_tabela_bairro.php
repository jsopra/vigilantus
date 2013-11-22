<?php

class m131122_085905_tabela_bairro extends CDbMigration
{
	public function up()
	{
        $this->createTable('bairros', array(
            'id' => 'pk',
            'municipio_id' => 'integer  not null references municipios(id)',
            'nome' => 'varchar not null',
            'bairro_tipo_id' => 'integer references bairro_tipos(id)',
        ));
	}

	public function down()
	{
		echo "m131122_085905_tabela_bairro does not support migration down.\n";
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