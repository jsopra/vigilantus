<?php

use yii\db\Migration;

class m131122_085905_tabela_bairro extends Migration
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
		$this->dropTable('bairros');
	}
}
