<?php

use yii\db\Schema;

class m140112_142259_altera_default_imoveis_condicoes_exibe_nome extends \yii\db\Migration
{
    public function safeUp()
    {
        $this->update('imovel_condicoes', ['exibe_nome' => 0], 'exibe_nome IS NULL');
        $this->db->pdo->query('ALTER TABLE imovel_condicoes ALTER COLUMN exibe_nome SET DEFAULT FALSE');
        $this->db->pdo->query('ALTER TABLE imovel_condicoes ALTER COLUMN exibe_nome SET NOT NULL');
    }

    public function safeDown()
    {
        $this->db->pdo->query('ALTER TABLE imovel_condicoes ALTER COLUMN exibe_nome DROP DEFAULT');
        $this->db->pdo->query('ALTER TABLE imovel_condicoes ALTER COLUMN exibe_nome DROP NOT NULL');
    }
}
