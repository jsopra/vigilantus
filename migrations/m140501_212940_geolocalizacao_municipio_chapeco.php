<?php

use yii\db\Schema;

class m140501_212940_geolocalizacao_municipio_chapeco extends \yii\db\Migration
{
    public function up()
    {
        $this->execute("UPDATE municipios SET coordenadas_area = ST_GeomFromText('POINT(-27.097643 -52.616642)',4326) WHERE id = 1");
    }

    public function down()
    {
        echo "m140501_212940_geolocalizacao_municipio_chapeco cannot be reverted.\n";

        return false;
    }
}
