<?php

use yii\db\Schema;

class m140126_150849_campo_mes_em_boletim_rg extends \yii\db\Migration
{
	public function up()
	{
        $this->addColumn('boletins_rg', 'mes', 'integer NOT NULL ');
	}

	public function down()
	{
		echo "m140126_150849_campo_mes_em_boletim_rg cannot be reverted.\n";
		return false;
	}
}
