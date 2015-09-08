<?php

use yii\db\Migration;

class m150907_233502_coordenada_ocorrencia extends Migration
{
    public function safeUp()
    {
        $this->addColumn('ocorrencias', 'coordenadas', 'geometry');
    }

    public function safeDown()
    {
        echo "m150907_233502_coordenada_ocorrencia cannot be reverted.\n";
        return false;
    }
}
