<?php

use yii\db\Migration;

class m151003_133004_add_index_to_ocorrencias_cliente_id extends Migration
{
    public function safeUp()
    {
        $this->createIndex('idx_ocorrencias_cliente_id', 'ocorrencias', ['cliente_id']);
    }

    public function safeDown()
    {
        $this->dropIndex('idx_ocorrencias_cliente_id', 'ocorrencias');
    }
}
