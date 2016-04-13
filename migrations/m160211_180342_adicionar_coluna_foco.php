<?php

use yii\db\Migration;

class m160211_180342_adicionar_coluna_foco extends Migration
{
    public function safeUp()
    {
        $this->addColumn('amostras_transmissores', 'foco', 'boolean');
    }

    public function safeDown()
    {
        echo "m160211_180342_adicionar_coluna_foco cannot be reverted.\n";
        return false;
    }
}
