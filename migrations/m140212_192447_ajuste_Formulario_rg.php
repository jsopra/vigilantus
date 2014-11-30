<?php

use yii\db\Schema;

class m140212_192447_ajuste_Formulario_rg extends \yii\db\Migration
{
	public function up()
	{
        $this->dropColumn('boletim_rg_fechamento', 'condicao_imovel_id');
        $this->dropColumn('boletim_rg_fechamento', 'area_de_foco');
        $this->addColumn('boletim_rg_fechamento', 'imovel_lira', 'boolean');
        
        $this->dropColumn('boletim_rg_imoveis', 'condicao_imovel_id');
        $this->dropColumn('boletim_rg_imoveis', 'area_de_foco');
        
        $this->addColumn('bairro_rua_imoveis', 'imovel_lira', 'boolean');
	}

	public function down()
	{
		echo "m140212_192447_ajuste_Formulario_rg cannot be reverted.\n";
		return false;
	}
}
