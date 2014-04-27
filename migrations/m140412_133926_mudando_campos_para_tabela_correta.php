<?php

use yii\db\Schema;

class m140412_133926_mudando_campos_para_tabela_correta extends \yii\db\Migration
{
    public function up()
    {
        $this->dropColumn('bairro_rua_imoveis', 'imovel_condicao_id');
        
        $this->addColumn('bairro_rua_imoveis', 'bairro_quarteirao_id', 'integer references bairro_quarteiroes(id)');
        
        $this->dropTable('imovel_condicoes');
        
        $this->dropColumn('bairro_ruas', 'bairro_id');
        
        $this->execute("ALTER TABLE bairro_ruas RENAME TO ruas");
        
        $this->execute("ALTER TABLE bairro_rua_imoveis RENAME TO imoveis");
    }

    public function down()
    {
        echo "m140412_133926_mudando_campos_para_tabela_correta cannot be reverted.\n";

        return false;
    }
}
