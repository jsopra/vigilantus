<?php

use yii\db\Migration;

class m180412_031527_cadastro_semanas_epidemiologicas extends Migration
{
    public function safeUp()
    {
        $this->createTable('semanas_epidemiologicas',[
            'id' => 'pk',
            'nome' => 'varchar',
            'cliente_id' => 'integer not null references clientes(id)',
            'inserido_por' => 'integer not null references usuarios(id)',
            'data_cadastro' => 'timestamp with time zone NOT NULL DEFAULT now()',
            'atualizado_por' => 'integer references usuarios(id)',
            'data_atualizacao' => 'timestamp with time zone',
            'inicio' => 'date not null',
            'fim' => 'date not null'
        ]);
    }

    public function safeDown()
    {
        $this->dropTable('semanas_epidemiologicas');
    }
}
