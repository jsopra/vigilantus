<?php

use yii\db\Migration;

class m160318_171138_tabela_casos_doencas extends Migration
{
    public function safeUp()
    {
        $this->createTable('casos_doencas', array(
            'id' => 'pk',
            'cliente_id' => 'integer not null references clientes(id)',
            'doenca_id' => 'integer not null references doencas(id)',
            'inserido_por' => 'integer not null references usuarios(id)',
            'data_cadastro' => 'timestamp with time zone not null default now()',
            'atualizado_por' => 'integer references usuarios(id)',
            'data_atualizacao' => 'timestamp with time zone',
            'coordenadas_area' => 'geometry',
            'bairro_quarteirao_id' => 'integer references bairro_quarteiroes(id)',
            'nome_paciente' => 'varchar',
            'data_sintomas' => 'date',
        ));
    }

    public function safeDown()
    {
        $this->dropTable('casos_doencas');
    }
}
