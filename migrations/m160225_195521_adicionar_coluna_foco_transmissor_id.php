<?php

use yii\db\Migration;

class m160225_195521_adicionar_coluna_foco_transmissor_id extends Migration
{
    public function safeUp()
    {
        $this->addColumn('amostras_transmissores', 'foco_transmissor_id', 'integer references focos_transmissores(id)');
    }

    public function safeDown()
    {
        echo "m160225_195521_adicionar_coluna_foco_transmissor_id cannot be reverted.\n";
        return false;
    }
}
