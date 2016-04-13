<?php

use yii\db\Migration;

class m151222_180726_coluna_foco_amostra_transmissor extends Migration
{
    public function safeUp()
    {
        $this->addColumn('amostras_transmissores', 'foco', 'boolean default false');
    }

    public function safeDown()
    {
        echo "m151222_180726_coluna_foco_amostra_transmissor cannot be reverted.\n";
        return false;
    }
}
