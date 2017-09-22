<?php

use yii\db\Migration;

class m170919_130759_tabela_setor_modulos extends Migration
{
    public function safeUp()
    {
        $this->createTable('setor_modulos',[
            'id' => 'pk',
            'setor_id' => 'integer not null references setores (id)',
            'modulo_id' => 'integer not null references modulos (id)',
        ]);
    }

    public function safeDown()
    {
        echo "m140805_110558_relacao_cliente_modulo cannot be reverted.\n";
        return false;
    }
}