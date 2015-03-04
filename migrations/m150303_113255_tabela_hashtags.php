<?php

use yii\db\Migration;

class m150303_113255_tabela_hashtags extends Migration
{
    public function safeUp()
    {
        $this->createTable('social_hashtags', [
            'id' => 'pk',
            'termo' => 'varchar',
            'ativo' => 'boolean not null default true',
            'inserido_por' => 'integer not null references usuarios(id)',
            'data_cadastro' => 'timestamp without time zone default now()',
            'atualizado_por' => 'integer references usuarios(id)',
            'data_atualizacao' => 'timestamp',
            'cliente_id' => 'integer not null references clientes(id)',
        ]);
    }

    public function safeDown()
    {
        echo "m150303_113255_tabela_hashtags cannot be reverted.\n";
        return false;
    }
}
