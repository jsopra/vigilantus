<?php

use yii\db\Migration;

class m151031_170114_permite_cliente_nulo_ocorrencias extends Migration
{
    public function safeUp()
    {
        $this->db->pdo->query('ALTER TABLE ocorrencias ALTER COLUMN cliente_id DROP NOT NULL');
    }

    public function safeDown()
    {
        echo "m151031_170114_permite_cliente_nulo_ocorrencias cannot be reverted.\n";
        return false;
    }
}
