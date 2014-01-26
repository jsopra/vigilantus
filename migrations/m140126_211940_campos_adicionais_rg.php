<?php

use yii\db\Schema;

class m140126_211940_campos_adicionais_rg extends \yii\db\Migration
{
	public function up()
	{
        $this->addColumn('bairro_rua_imoveis', 'imovel_tipo_id', 'integer references imovel_tipos(id)');
        $this->addColumn('bairro_rua_imoveis', 'imovel_condicao_id', 'integer references imovel_condicoes(id)');
        
        $this->addColumn('boletim_rg_imoveis', 'area_de_foco', 'boolean default false');
        $this->dropColumn('boletim_rg_imoveis', 'condicao_imovel_id');
        
        $this->dropColumn('boletim_rg_fechamento', 'data');
        $this->addColumn('boletim_rg_fechamento', 'mes', 'integer');
        $this->addColumn('boletim_rg_fechamento', 'ano', 'integer');
        $this->addColumn('boletim_rg_fechamento', 'area_de_foco', 'boolean default false');
	}

	public function down()
	{
		echo "m140126_211940_campos_adicionais_rg cannot be reverted.\n";
		return false;
	}
}
