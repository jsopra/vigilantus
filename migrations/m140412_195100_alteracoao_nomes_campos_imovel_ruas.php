<?php

use yii\db\Schema;

class m140412_195100_alteracoao_nomes_campos_imovel_ruas extends \yii\db\Migration
{
    public function up()
    {
        $this->execute("ALTER TABLE boletim_rg_imoveis RENAME COLUMN bairro_rua_imovel_id TO imovel_id");
        
        $this->execute("ALTER TABLE imoveis RENAME COLUMN bairro_rua_id TO rua_id");
    }

    public function down()
    {
        echo "m140412_195100_alteracoao_nomes_campos_imovel_ruas cannot be reverted.\n";

        return false;
    }
}
