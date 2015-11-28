<?php

use yii\db\Migration;

class m151121_155252_add_coordenadas_centro_to_bairros extends Migration
{
    public function safeUp()
    {
        $this->addColumn('bairros', 'coordenadas_centro', 'geometry');
    }

    public function safeDown()
    {
        $this->dropColumn('bairros', 'coordenadas_centro', 'geometry');
    }
}
