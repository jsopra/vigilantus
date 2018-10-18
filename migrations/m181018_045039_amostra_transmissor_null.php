<?php

use yii\db\Migration;

class m181018_045039_amostra_transmissor_null extends Migration
{
    public function safeUp()
    {
        $this->db->pdo->query('ALTER TABLE amostras_transmissores ALTER COLUMN tipo_deposito_id DROP NOT NULL');
    }

    public function safeDown()
    {
        echo "m181018_045039_amostra_transmissor_null cannot be reverted.\n";
        return false;
    }
}
