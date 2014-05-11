<?php

use yii\db\Migration;

class m140510_171522_removendo_campos_foco extends Migration
{
    public function safeUp()
    {
        $this->dropColumn('focos_transmissores', 'tipo_imovel_id');
        $this->dropColumn('focos_transmissores', 'quarteirao_id');
    }
    
    public function safeDown()
    {
        echo "m140510_171522_removendo_campos_foco cannot be reverted.\n";
        return false;
    }
}
