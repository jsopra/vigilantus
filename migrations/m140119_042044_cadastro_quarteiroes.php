<?php

use yii\db\Schema;

class m140119_042044_cadastro_quarteiroes extends \yii\db\Migration
{
	public function up()
	{
        $this->createTable('bairro_quarteiroes', array(
            'id' => 'pk',
            'municipio_id' => 'integer not null references municipios(id)',
            'bairro_id' => 'integer not null references bairros(id)',
            'numero_quarteirao' => 'integer',
            'numero_quarteirao_2' => 'integer',
            'data_cadastro' => 'timestamp with time zone not null default now()',
            'data_atualizacao' => 'timestamp with time zone',
            'inserido_por' => 'integer not null references usuarios(id)',
            'atualizado_por' => 'integer references usuarios(id)',
        ));
	}

	public function down()
	{
		echo "m140119_042044_cadastro_quarteiroes cannot be reverted.\n";
		return false;
	}
}
