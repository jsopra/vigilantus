<?php

use yii\db\Migration;

class m150212_105315_atualizando_latlon extends Migration
{
    public function safeUp()
    {
        $this->execute("update bairro_quarteiroes set coordenadas_area = ST_FlipCoordinates(coordenadas_area)");
        $this->execute("update bairros set coordenadas_area = ST_FlipCoordinates(coordenadas_area)");
        $this->execute("update municipios set coordenadas_area = ST_FlipCoordinates(coordenadas_area) WHERE coordenadas_area is not null");
    }

    public function safeDown()
    {
        echo "m150212_105315_atualizando_latlon cannot be reverted.\n";
        return false;
    }
}
