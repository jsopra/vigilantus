<?php

use yii\db\Migration;

class m140804_113442_tabela_modulos extends Migration
{
    public function safeUp()
    {
        $this->createTable('modulos',[
            'id' => 'pk',
            'nome' => 'varchar not null',
            'ativo' => 'boolean not null default true',
            'data_cadastro' => 'timestamp without time zone DEFAULT now()',  
            'data_atualizacao' => 'timestamp without time zone',  
        ]);
    }

    public function safeDown()
    {
        echo "m140804_113442_tabela_modulos cannot be reverted.\n";
        return false;
    }
}
