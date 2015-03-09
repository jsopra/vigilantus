<?php

use yii\db\Migration;

class m150309_220746_data_fechamento_denuncia extends Migration
{
    public function safeUp()
    {
        $this->addColumn('denuncias', 'data_fechamento', 'timestamp without time zone');
    }

    public function safeDown()
    {
        echo "m150309_220746_data_fechamento_denuncia cannot be reverted.\n";
        return false;
    }
}
