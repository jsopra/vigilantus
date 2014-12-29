<?php

use yii\db\Migration;

class m141101_132753_indice_boletim_Rg extends Migration
{
    public function safeUp()
    {
    	$this->createIndex('idx_boletins_rg_data', 'boletins_rg', 'data');
    }

    public function safeDown()
    {
        echo "m141101_132753_indice_boletim_Rg cannot be reverted.\n";
        return false;
    }
}
