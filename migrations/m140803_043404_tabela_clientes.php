<?php

use yii\db\Migration;

class m140803_043404_tabela_clientes extends Migration
{
    public function safeUp()
    {
        $this->createTable('clientes',[
            'id' => 'pk',
            'municipio_id' => 'integer NOT NULL references municipios (id)',
            'data_cadastro' => 'timestamp without time zone DEFAULT now()',  
        ]);
    }

    public function safeDown()
    {
        echo "m140803_043404_tabela_clientes cannot be reverted.\n";
        return false;
    }
}
