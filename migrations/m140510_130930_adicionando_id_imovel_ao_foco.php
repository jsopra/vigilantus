<?php

use yii\db\Migration;

class m140510_130930_adicionando_id_imovel_ao_foco extends Migration
{
    public function safeUp()
    {
        $this->addColumn('focos_transmissores', 'laboratorio', 'varchar');
        $this->addColumn('focos_transmissores', 'tecnico', 'varchar');  
        $this->addColumn('focos_transmissores', 'imovel_id', 'integer references imoveis(id)');  
        $this->dropColumn('focos_transmissores', 'endereco');
    }

    public function safeDown()
    {
        echo "m140510_130930_adicionando_id_imovel_ao_foco cannot be reverted.\n";
        return false;
    }
}
