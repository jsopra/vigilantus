<?php

use yii\db\Schema;

class m140126_152441_removendo_campo_data_boletim_rg extends \yii\db\Migration
{
	public function up()
	{
        $this->dropColumn('boletins_rg', 'data');
	}

	public function down()
	{
		echo "m140126_152441_removendo_campo_data_boletim_rg cannot be reverted.\n";
		return false;
	}
}
