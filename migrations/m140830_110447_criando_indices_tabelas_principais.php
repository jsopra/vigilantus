<?php

use yii\db\Migration;

class m140830_110447_criando_indices_tabelas_principais extends Migration
{
    public function safeUp()
    {
        //foco transmissor
        $this->createIndex('idx_foco_transmissor_bairro_quarteirao_id', 'focos_transmissores', 'bairro_quarteirao_id');
        $this->createIndex('idx_foco_transmissor_data_coleta', 'focos_transmissores', 'data_coleta');
        $this->createIndex('idx_foco_transmissor_especie_transmissor_id', 'focos_transmissores', 'especie_transmissor_id');
    }

    public function safeDown()
    {
        echo "m140830_110447_criando_indices_tabelas_principais cannot be reverted.\n";
        return false;
    }
}
