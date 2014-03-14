<?php

use yii\db\Schema;

class m140304_174404_table_deposito_tipos extends \yii\db\Migration
{
	public function up()
	{
		$this->createTable('deposito_tipos', array(
            'id' => 'pk',
            'municipio_id' => 'integer not null references municipios(id)',
            'deposito_tipo_pai' => 'integer not null references deposito_tipos(id)',
            'descricao' => 'varchar not null',
            'sigla' => 'varchar not null',
        ));
	}

	public function down()
	{
		echo "m140304_174404_table_deposito_tipos cannot be reverted.\n";
		return false;
	}
}
