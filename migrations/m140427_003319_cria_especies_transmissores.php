<?php

use yii\db\Schema;

class m140427_003319_cria_especies_transmissores extends \yii\db\Migration
{
    public function safeUp()
    {
        $this->createTable(
            'especies_transmissores',
            [
                'id' => 'pk',
                'municipio_id' => 'integer NOT NULL REFERENCES municipios ON UPDATE CASCADE ON DELETE CASCADE',
                'nome' => 'string NOT NULL',
            ]
        );
    }

    public function safeDown()
    {
        $this->dropTable('especies_transmissores');
    }
}
