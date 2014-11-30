<?php

use yii\db\Schema;

class m140202_151008_campo_em_tabela_formulario_rg extends \yii\db\Migration
{
	public function up()
	{
        $this->addColumn('boletim_rg_imoveis', 'condicao_imovel_id', 'integer references imovel_condicoes(id)');
	}

	public function down()
	{
		echo "m140202_151008_campo_em_tabela_formulario_rg cannot be reverted.\n";
		return false;
	}
}
