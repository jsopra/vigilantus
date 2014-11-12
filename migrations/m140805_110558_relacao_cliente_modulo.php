<?php

use yii\db\Migration;

class m140805_110558_relacao_cliente_modulo extends Migration
{
    public function safeUp()
    {
        $this->createTable('cliente_modulos',[
            'id' => 'pk',
            'cliente_id' => 'integer not null references clientes (id)',
            'modulo_id' => 'integer not null references modulos (id)',
        ]);
    }

    public function safeDown()
    {
        echo "m140805_110558_relacao_cliente_modulo cannot be reverted.\n";
        return false;
    }
}
