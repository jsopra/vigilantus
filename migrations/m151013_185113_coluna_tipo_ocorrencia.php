<?php

use yii\db\Migration;

class m151013_185113_coluna_tipo_ocorrencia extends Migration
{
    public function safeUp()
    {
         $this->addColumn('ocorrencias', 'tipo_registro', 'string NOT NULL default \'denuncia\'');
    }

    public function safeDown()
    {
        $this->dropColumn('ocorrencias', 'tipo_registro');
    }
}
