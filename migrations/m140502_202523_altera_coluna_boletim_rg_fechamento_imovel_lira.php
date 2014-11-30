<?php

use yii\db\Migration;

class m140502_202523_altera_coluna_boletim_rg_fechamento_imovel_lira extends Migration
{
    public function safeUp()
    {
        $this->db->pdo->query('ALTER TABLE boletim_rg_fechamento ALTER COLUMN imovel_lira SET DEFAULT FALSE');
        $this->update('boletim_rg_fechamento', ['imovel_lira' => false], 'imovel_lira IS NULL');
        $this->db->pdo->query('ALTER TABLE boletim_rg_fechamento ALTER COLUMN imovel_lira SET NOT NULL');
    }

    public function safeDown()
    {
        $this->db->pdo->query('ALTER TABLE boletim_rg_fechamento ALTER COLUMN imovel_lira DROP DEFAULT');
        $this->db->pdo->query('ALTER TABLE boletim_rg_fechamento ALTER COLUMN imovel_lira DROP NOT NULL');
    }
}
