<?php

use yii\db\Migration;

class m150226_113735_data_ultimo_foco_em_quarteirao extends Migration
{
    public function safeUp()
    {
        $this->addColumn('bairro_quarteiroes', 'data_ultimo_foco', 'date');
    }

    public function safeDown()
    {
        echo "m150226_113735_data_ultimo_foco_em_quarteirao cannot be reverted.\n";
        return false;
    }
}
