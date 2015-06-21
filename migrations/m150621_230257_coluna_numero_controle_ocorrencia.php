<?php

use yii\db\Migration;

class m150621_230257_coluna_numero_controle_ocorrencia extends Migration
{
    public function safeUp()
    {
        $this->addColumn('ocorrencias', 'numero_controle', 'varchar');
    }

    public function safeDown()
    {
        echo "m150621_230257_coluna_numero_controle_ocorrencia cannot be reverted.\n";
        return false;
    }
}
