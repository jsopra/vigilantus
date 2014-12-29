<?php

use yii\db\Migration;

class m140827_132306_campos_adicionais_foco extends Migration
{
    public function safeUp()
    {
        $this->addColumn('focos_transmissores', 'planilha_endereco', 'varchar');
        $this->addColumn('focos_transmissores', 'planilha_imovel_tipo_id', 'integer references imovel_tipos(id)');  
    }

    public function safeDown()
    {
        echo "m140827_132306_campos_adicionais_foco cannot be reverted.\n";
        return false;
    }
}
