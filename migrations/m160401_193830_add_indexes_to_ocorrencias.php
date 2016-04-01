<?php

use yii\db\Migration;

class m160401_193830_add_indexes_to_ocorrencias extends Migration
{
    public function safeUp()
    {
        $this->createIndex('idx_ocorrencias_data_fechamento', 'ocorrencias', ['data_fechamento']);
        $this->createIndex('idx_ocorrencias_municipio_id', 'ocorrencias', ['municipio_id']);
    }

    public function safeDown()
    {
        $this->dropIndex('idx_ocorrencias_data_fechamento', 'ocorrencias');
        $this->dropIndex('idx_ocorrencias_municipio_id', 'ocorrencias');
    }
}
