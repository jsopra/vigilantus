<?php

use yii\db\Migration;

class m150912_170024_drop_not_null_from_ocorrencias_ocorrencia_tipo_problema_id extends Migration
{
    public function safeUp()
    {
        $this->db->pdo->query('ALTER TABLE ocorrencias ALTER COLUMN ocorrencia_tipo_problema_id DROP NOT NULL');
    }

    public function safeDown()
    {
        echo "m150912_170024_drop_not_null_from_ocorrencias_ocorrencia_tipo_problema_id cannot be reverted.\n";
        return false;
    }
}
