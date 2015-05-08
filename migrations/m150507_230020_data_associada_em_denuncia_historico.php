<?php

use yii\db\Migration;

class m150507_230020_data_associada_em_denuncia_historico extends Migration
{
    public function safeUp()
    {
        $this->addColumn('denuncia_historico', 'data_associada', 'date');
    }

    public function safeDown()
    {
        echo "m150507_230020_data_associada_em_denuncia_historico cannot be reverted.\n";
        return false;
    }
}
