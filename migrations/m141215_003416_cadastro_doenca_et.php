<?php

use yii\db\Migration;

class m141215_003416_cadastro_doenca_et extends Migration
{
    public function safeUp()
    {
        $this->createTable('especie_transmissor_doencas', [
            'id' => 'pk',
            'cliente_id' => 'integer not null references clientes(id)',
            'doenca_id' => 'integer not null references doencas(id)',
            'especie_transmissor_id' => 'integer not null references especies_transmissores(id)',
        ]);
    }

    public function safeDown()
    {
        echo "m141215_003416_cadastro_doenca_et cannot be reverted.\n";
        return false;
    }
}
