<?php

use yii\db\Schema;

class m140202_213108_remove_colunas_mes_ano_das_tabelas_boletins extends \yii\db\Migration
{
	public function up()
	{
        $this->dropColumn('boletim_rg_fechamento', 'mes');
        $this->dropColumn('boletim_rg_fechamento', 'ano');
        $this->dropColumn('boletins_rg', 'mes');
        $this->dropColumn('boletins_rg', 'ano');
        $this->dropColumn('boletim_rg_imoveis', 'data');
	}

	public function down()
	{
		echo "m140202_213108_remove_colunas_mes_ano_das_tabelas_boletins cannot be reverted.\n";
		return false;
	}
}
