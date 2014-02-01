<?php

use yii\db\Schema;

class m140201_172905_bairro_hadoop_rg extends \yii\db\Migration
{
	public function up()
	{
        $this->addColumn('bairros', 'ultimo_mes_rg', 'integer');
        $this->addColumn('bairros', 'ultimo_ano_rg', 'integer');
	}

	public function down()
	{
		echo "m140201_172905_bairro_hadoop_rg cannot be reverted.\n";
		return false;
	}
}
