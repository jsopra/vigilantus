<?php

use yii\db\Migration;

class m150720_005506_create_amostras_transmissores extends Migration
{
    public function safeUp()
    {
        $this->createTable(
            'amostras_transmissores',
            [
                'id' => 'pk',
                'data_criacao' => 'timestamp not null default now()',
                'data_atualizacao' => 'timestamp  not null default now()',
                'data_coleta' => 'timestamp',
                'cliente_id' => 'integer not null references clientes(id)',
                'tipo_deposito_id' => 'integer not null references deposito_tipos(id)',
                'quarteirao_id' => 'integer not null references bairro_quarteiroes(id)',
                'endereco' => 'text',
                'observacoes' => 'text',
                'numero_casa' => 'integer',
                'numero_amostra' => 'integer',
                'quantidade_larvas' => 'integer NOT NULL DEFAULT 0',
                'quantidade_pupas' => 'integer NOT NULL DEFAULT 0',
            ]
        );
    }

    public function safeDown()
    {
        $this->dropTable('amostras_transmissores');
    }
}
