<?php

use yii\db\Schema;

class m140125_134446_alterando_barrio_tipo extends \yii\db\Migration
{
	public function up()
	{
        $this->renameTable('bairro_tipos', 'bairro_categorias');
	}

	public function down()
	{
		echo "m140125_134446_alterando_barrio_tipo cannot be reverted.\n";
		return false;
	}
}
