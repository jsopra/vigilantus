<?php

use yii\db\Migration;

class m141203_014530_tabelas_configuracao extends Migration
{
    public function safeUp()
    {
        $this->createTable('configuracoes', array(
            'id' => 'pk',
            'nome' => 'varchar not null',
            'descricao' => 'varchar not null',
            'tipo' => 'varchar not null',
            'valor' => 'varchar not null',
            'valores_possiveis' => 'varchar'
        ));

        $this->createTable('configuracoes_clientes', array(
            'id' => 'pk',
            'cliente_id' => 'integer not null references clientes(id)',
            'valor' => 'varchar not null',
        ));
    }

    public function safeDown()
    {
        echo "m141203_014530_tabelas_configuracao cannot be reverted.\n";
        return false;
    }
}
