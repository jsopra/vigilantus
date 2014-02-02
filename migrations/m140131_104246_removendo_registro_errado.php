<?php

use yii\db\Schema;

class m140131_104246_removendo_registro_errado extends \yii\db\Migration
{
	public function up()
	{
        $this->execute("DELETE FROM imovel_condicoes WHERE nome = 'Área de Foco'");
	}

	public function down()
	{
		echo "m140131_104246_removendo_registro_errado cannot be reverted.\n";
		return false;
	}
}
