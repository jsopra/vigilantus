<?php

use yii\db\Migration;

class m150507_223851_agente_id_em_denuncia_historico extends Migration
{
    public function safeUp()
    {
        $this->addColumn('denuncia_historico', 'agente_id', 'integer references equipe_agentes(id)');
    }

    public function safeDown()
    {
        echo "m150507_223851_agente_id_em_denuncia_historico cannot be reverted.\n";
        return false;
    }
}
