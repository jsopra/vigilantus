<?php

use yii\db\Schema;

class m140203_223707_campo_Data_boletim_rg extends \yii\db\Migration
{
	public function up()
	{
        $this->addColumn('boletins_rg', 'data', 'date');
	}

	public function down()
	{
		echo "m140203_223707_campo_Data_boletim_rg cannot be reverted.\n";
		return false;
	}
}
