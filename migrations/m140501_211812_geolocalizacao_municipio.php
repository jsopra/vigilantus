<?php

use yii\db\Schema;

class m140501_211812_geolocalizacao_municipio extends \yii\db\Migration
{
    public function up()
    {
        $this->addColumn('municipios', 'coordenadas_area', 'geometry');
    }

    public function down()
    {
        echo "m140501_211812_geolocalizacao_municipio cannot be reverted.\n";

        return false;
    }
}
