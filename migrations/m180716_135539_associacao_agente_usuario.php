<?php

use yii\db\Migration;

class m180716_135539_associacao_agente_usuario extends Migration
{
    public function safeUp()
    {
   		$this->addColumn('equipe_agentes', 'usuario_id', 'integer references usuarios(id)');
    }

    public function safeDown()
    {
        $this->dropColumn('equipe_agentes', 'usuario_id');
    }
}
