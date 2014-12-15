<?php

use yii\db\Migration;

class m141215_002551_cadastro_doenca extends Migration
{
    public function safeUp()
    {
        $this->createTable('doencas', [
            'id' => 'pk',
            'data_criacao' => 'timestamp without time zone not null default now()',
            'cliente_id' => 'integer not null references clientes(id)',
            'nome' => 'varchar',
        ]);
    }

    public function safeDown()
    {
        echo "m141215_002551_cadastro_doenca cannot be reverted.\n";
        return false;
    }
}
