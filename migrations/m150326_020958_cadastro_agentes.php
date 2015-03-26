<?php

use yii\db\Migration;

class m150326_020958_cadastro_agentes extends Migration
{
    public function safeUp()
    {
        $this->createTable('equipe_agentes', [
            'id' => 'pk',
            'cliente_id' => 'integer not null references clientes(id)',
            'equipe_id' => 'integer not null references equipes(id)',
            'nome' => 'varchar',
            'ativo' => 'boolean not null default true',
            'codigo' => 'varchar',
        ]);
    }

    public function safeDown()
    {
        echo "m150326_020958_cadastro_agentes cannot be reverted.\n";
        return false;
    }
}
