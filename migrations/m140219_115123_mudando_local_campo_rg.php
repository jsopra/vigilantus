<?php

use yii\db\Schema;

class m140219_115123_mudando_local_campo_rg extends \yii\db\Migration
{
	public function up()
	{
        $this->addColumn('bairro_quarteiroes', 'seq', 'numeric');
        $this->dropColumn('boletins_rg', 'seq');
	}

	public function down()
	{
		echo "m140219_115123_mudando_local_campo_rg cannot be reverted.\n";
		return false;
	}
}
