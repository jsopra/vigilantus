<?php

use yii\db\Migration;

class m150316_212636_cadastro_equipe extends Migration
{
    public function safeUp()
    {
        $this->createTable('equipes', [
            'id' => 'pk',
            'data_criacao' => 'timestamp without time zone not null default now()',
            'cliente_id' => 'integer not null references clientes(id)',
            'nome' => 'varchar',
        ]);
    }

    public function safeDown()
    {
        echo "m150316_212636_cadastro_equipe cannot be reverted.\n";
        return false;
    }
}
