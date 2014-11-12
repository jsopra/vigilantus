<?php

use yii\db\Schema;

class m140314_164439_add_columns_log_deposito_tipos extends \yii\db\Migration
{
	public function up()
	{
		$this->addColumn('deposito_tipos', 'data_cadastro', 'timestamp with time zone not null default now()');
        $this->addColumn('deposito_tipos', 'data_atualizacao', 'timestamp with time zone');
		$this->addColumn('deposito_tipos', 'inserido_por', 'integer not null references usuarios(id)');
		$this->addColumn('deposito_tipos', 'atualizado_por', 'integer references usuarios(id)');
	}

	public function down()
	{
		echo "m140314_164439_add_columns_log_deposito_tipos cannot be reverted.\n";
		return false;
	}
}
