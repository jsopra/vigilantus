<?php

use yii\db\Migration;

class m170823_183218_setor_tipos_ocorrencias extends Migration
{
    public function safeUp()
    {
    	$this->createTable('setor_tipos_ocorrencias', [
            'id' => 'pk',
            'setor_id' => 'integer not null references setores(id)',
            'tipos_problemas_id' => 'integer not null references ocorrencia_tipos_problemas(id)',
        ]);
    }

    public function safeDown()
    {
        echo "m170823_183218_tabela_tipoOcorrencias_setor cannot be reverted.\n";
        return false;
    }
}
