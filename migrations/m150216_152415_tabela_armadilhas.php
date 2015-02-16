<?php

use yii\db\Migration;

class m150216_152415_tabela_armadilhas extends Migration
{
    public function safeUp()
    {
        $this->createTable('armadilhas', [
            'id' => 'pk',
            'inserido_por' => 'integer not null references usuarios(id)',
            'data_cadastro' => 'timestamp without time zone default now()',
            'atualizado_por' => 'integer references usuarios(id)',
            'data_atualizacao' => 'timestamp',
            'cliente_id' => 'integer not null references clientes(id)',
            'descricao' => 'varchar',
            'coordenadas_area' => 'geometry',
            'bairro_quarteirao_id' => 'integer references bairro_quarteiroes(id)',
        ]);
    }

    public function safeDown()
    {
        echo "m150216_152415_tabela_armadilhas cannot be reverted.\n";
        return false;
    }
}
