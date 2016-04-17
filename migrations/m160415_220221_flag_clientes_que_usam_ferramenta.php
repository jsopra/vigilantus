<?php

use yii\db\Migration;

class m160415_220221_flag_clientes_que_usam_ferramenta extends Migration
{
    public function safeUp()
    {
        $this->addColumn('clientes', 'ativo', 'boolean default false');
    }

    public function safeDown()
    {
        echo "m160415_220221_flag_clientes_que_usam_ferramenta cannot be reverted.\n";
        return false;
    }
}
