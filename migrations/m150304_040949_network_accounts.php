<?php

use yii\db\Migration;

class m150304_040949_network_accounts extends Migration
{
    public function safeUp()
    {
        $this->createTable('social_accounts', [
            'id' => 'pk',
            'cliente_id' => 'integer not null references clientes(id)',
            'inserido_por' => 'integer not null references usuarios(id)',
            'data_cadastro' => 'timestamp without time zone default now()',
            'atualizado_por' => 'integer references usuarios(id)',
            'data_atualizacao' => 'timestamp',
            'social' => 'integer not null',
            'social_id' => 'varchar not null',
            'token' => 'text not null',
        ]);
    }

    public function safeDown()
    {
        echo "m150304_040949_network_accounts cannot be reverted.\n";
        return false;
    }
}
