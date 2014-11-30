<?php

use yii\db\Schema;

class m140314_144026_remove_constraint_tipo_deposito extends \yii\db\Migration
{
	public function up()
	{
		$this->dropColumn('deposito_tipos', 'deposito_tipo_pai');
        $this->addColumn('deposito_tipos', 'deposito_tipo_pai', 'integer references deposito_tipos(id)');
	}

	public function down()
	{
		echo "m140314_144026_remove_constraint_tipo_deposito cannot be reverted.\n";
		return false;
	}
}
