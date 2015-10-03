<?php

use yii\db\Migration;

class m151003_131639_add_index_to_ocorrencias_data_criacao extends Migration
{
    public function safeUp()
    {
        $this->createIndex('idx_ocorrencias_data_criacao', 'ocorrencias', ['data_criacao']);
    }

    public function safeDown()
    {
        $this->dropIndex('idx_ocorrencias_data_criacao', 'ocorrencias');
    }
}
