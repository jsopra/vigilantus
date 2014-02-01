<?php

use yii\db\Schema;

class m140201_174821_campos_em_rg_fechamento extends \yii\db\Migration
{
	public function up()
	{
        $this->addColumn('boletim_rg_fechamento', 'imovel_tipo_id', 'integer references imovel_tipos(id)');
        $this->addColumn('boletim_rg_imoveis', 'imovel_tipo_id', 'integer references imovel_tipos(id)');
	}

	public function down()
	{
		echo "m140201_174821_campos_em_rg_fechamento cannot be reverted.\n";
		return false;
	}
}
