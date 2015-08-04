<?php

use yii\db\Migration;

class m150716_110305_indice_busca_ocorrencia extends Migration
{
    public function safeUp()
    {
        $this->createIndex('idx_ocorrencias_numero_controle', 'ocorrencias', 'numero_controle');
    }

    public function safeDown()
    {
        echo "m150716_110305_indice_busca_ocorrencia cannot be reverted.\n";
        return false;
    }
}
