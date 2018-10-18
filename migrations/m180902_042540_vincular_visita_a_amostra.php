<?php

use yii\db\Migration;

class m180902_042540_vincular_visita_a_amostra extends Migration
{
    public function safeUp()
    {

   		$this->addColumn('amostras_transmissores', 'visita_id', 'integer references visita_imoveis(id)');
    }

    public function safeDown()
    {
        $this->dropColumn('amostras_transmissores', 'visita_id');
    }
}
