<?php

use yii\db\Schema;

class m140122_211024_colunas_municipio extends \yii\db\Migration
{
	public function up()
	{
        $this->addColumn('boletim_rg_imoveis', 'municipio_id', 'integer not null references municipios(id)');
        $this->addColumn('boletim_rg_fechamento', 'municipio_id', 'integer not null references municipios(id)');
	}

	public function down()
	{
		echo "m140122_211024_colunas_municipio cannot be reverted.\n";
		return false;
	}
}
