<?php

use yii\db\Schema;

class m140121_000245_ajustes_ficha_cadastro_rg extends \yii\db\Migration
{
	public function up()
	{
        $this->dropColumn('boletins_rg', 'quarteirao_numero');
        $this->addColumn('boletins_rg', 'bairro_quarteirao_id', 'integer not null references bairro_quarteiroes(id)');
	}

	public function down()
	{
		echo "m140121_000245_ajustes_ficha_cadastro_rg cannot be reverted.\n";
		return false;
	}
}
