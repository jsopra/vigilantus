<?php

use yii\db\Migration;

class m170815_181925_setor_regras_indefinidas extends Migration
{
    public function safeUp()
    {
    	$this->addColumn('setores', 'padrao_ocorrencias', 'boolean default false');
    }

    public function safeDown()
    {
        $this->dropColumn('setores', 'padrao_ocorrencias');
    }
}
