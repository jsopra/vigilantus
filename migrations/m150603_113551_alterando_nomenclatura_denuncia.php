<?php

use yii\db\Migration;

class m150603_113551_alterando_nomenclatura_denuncia extends Migration
{
    public function safeUp()
    {
        $this->renameTable('denuncias', 'ocorrencias');
        $this->renameTable('denuncia_historico', 'ocorrencia_historico');
        $this->renameTable('denuncia_tipos_problemas', 'ocorrencia_tipos_problemas');

        $this->renameColumn('ocorrencia_historico', 'denuncia_id', 'ocorrencia_id');
        $this->renameColumn('ocorrencias', 'denuncia_tipo_problema_id', 'ocorrencia_tipo_problema_id');
    }

    public function safeDown()
    {
        echo "m150603_113551_alterando_nomenclatura_denuncia cannot be reverted.\n";
        return false;
    }
}
