<?php

use yii\db\Migration;

class m140606_112246_cord_do_transmissor extends Migration
{
    public function safeUp()
    {
        $this->addColumn('especies_transmissores', 'cor_foco_no_mapa', 'varchar');
    }

    public function safeDown()
    {
        echo "m140606_112246_cord_do_transmissor cannot be reverted.\n";
        return false;
    }
}
