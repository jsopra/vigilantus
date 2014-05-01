<?php

use yii\db\Schema;

class m140501_033929_criando_colunas_geolocalizacao_bairro_quarteirao extends \yii\db\Migration
{
    public function up()
    {
        $this->addColumn('bairros', 'coordenadas_area', 'geometry');
        $this->addColumn('bairro_quarteiroes', 'coordenadas_area', 'geometry');
    }

    public function down()
    {
        echo "m140501_033929_criando_colunas_geolocalizacao_bairro_quarteirao cannot be reverted.\n";

        return false;
    }
}
