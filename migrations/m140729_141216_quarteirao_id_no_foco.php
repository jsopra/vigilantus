<?php

use yii\db\Migration;

class m140729_141216_quarteirao_id_no_foco extends Migration
{
    public function safeUp()
    {
        $this->addColumn('focos_transmissores', 'bairro_quarteirao_id', 'integer references bairro_quarteiroes(id)');
        
        $this->execute("
            UPDATE focos_transmissores SET bairro_quarteirao_id = (
                SELECT bairro_quarteirao_id 
                FROM imoveis
                WHERE id = focos_transmissores.imovel_id
            )
        ");
    }

    public function safeDown()
    {
        echo "m140729_141216_quarteirao_id_no_foco cannot be reverted.\n";
        return false;
    }
}
