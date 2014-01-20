<?php

use yii\db\Schema;

class m140119_185851_alterando_nome_tabela_imovel_tipo_categoria extends \yii\db\Migration
{
	public function up()
	{
        $this->renameTable('imovel_tipos', 'imovel_categorias');
	}

	public function down()
	{
		echo "m140119_185851_alterando_nome_tabela_imovel_tipo_categoria cannot be reverted.\n";
		return false;
	}
}
