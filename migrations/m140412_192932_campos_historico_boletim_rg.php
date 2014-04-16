<?php

use yii\db\Schema;

class m140412_192932_campos_historico_boletim_rg extends \yii\db\Migration
{
    public function up()
    {
        $this->addColumn('boletim_rg_imoveis', 'rua_nome', 'varchar');
        $this->addColumn('boletim_rg_imoveis', 'rua_id', 'integer references ruas(id)');
        $this->addColumn('boletim_rg_imoveis', 'imovel_numero', 'varchar');
        $this->addColumn('boletim_rg_imoveis', 'imovel_seq', 'varchar');
        $this->addColumn('boletim_rg_imoveis', 'imovel_complemento', 'varchar');
        $this->addColumn('boletim_rg_imoveis', 'imovel_lira', 'boolean');
    }

    public function down()
    {
        echo "m140412_192932_campos_historico_boletim_rg cannot be reverted.\n";

        return false;
    }
}
