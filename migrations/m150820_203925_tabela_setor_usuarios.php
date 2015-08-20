<?php

use yii\db\Migration;

class m150820_203925_tabela_setor_usuarios extends Migration
{
    public function safeUp()
    {
        $this->createTable('setor_usuarios', [
            'id' => 'pk',
            'setor_id' => 'integer not null references setores(id)',
            'usuario_id' => 'integer not null references usuarios(id)',
            'cliente_id' => 'integer not null references clientes(id)',
        ]);
    }

    public function safeDown()
    {
        $this->dropTable('setor_usuarios');
    }
}
