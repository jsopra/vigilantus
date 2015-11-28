<?php

use yii\db\Migration;

class m151128_131737_permite_cliente_nulo_ocorrencia_historico extends Migration
{
    public function safeUp()
    {
        $this->db->pdo->query('ALTER TABLE ocorrencia_historico ALTER COLUMN cliente_id DROP NOT NULL');
    }

    public function safeDown()
    {
        echo "m151128_131737_permite_cliente_nulo_ocorrencia_historico cannot be reverted.\n";
        return false;
    }
}
