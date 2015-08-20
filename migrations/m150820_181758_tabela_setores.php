<?php

use yii\db\Migration;

class m150820_181758_tabela_setores extends Migration
{
    public function safeUp()
    {
        $this->createTable('setores', [
            'id' => 'pk',
            'nome' => 'varchar',
            'cliente_id' => 'integer not null references clientes(id)',
            'usuario_inseriu_id' => 'integer not null references usuarios(id)',
            'datahora_inseriu' => 'timestamp with time zone NOT NULL DEFAULT now()',
            'usuario_alterou_id' => 'integer references usuarios(id)',
            'datahora_alterou' => 'timestamp with time zone',
        ]);
    }

    public function safeDown()
    {
        $this->dropTable('setores');
    }
}
