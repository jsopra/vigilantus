<?php

use yii\db\Migration;

class m150912_165857_add_descricao_outro_tipo_problema_to_ocorrencias extends Migration
{
    public function safeUp()
    {
        $this->addColumn('ocorrencias', 'descricao_outro_tipo_problema', 'string');
    }

    public function safeDown()
    {
        $this->dropColumn('ocorrencias', 'descricao_outro_tipo_problema');
    }
}
