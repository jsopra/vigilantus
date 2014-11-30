<?php

use yii\db\Migration;

class m140830_111404_criando_indices_tabelas_principais extends Migration
{
    public function safeUp()
    {
        //bairro_quarteirao
        $this->execute('CREATE INDEX idx_bairro_quarteiroes_coordenadas_area ON bairro_quarteiroes USING gist (coordenadas_area)');
        $this->createIndex('idx_bairro_quarteiroes_bairro_id', 'bairro_quarteiroes', 'bairro_id');
        $this->createIndex('idx_bairro_quarteiroes_numero_quarteirao', 'bairro_quarteiroes', 'numero_quarteirao');
    }

    public function safeDown()
    {
        echo "m140830_111404_criando_indices_tabelas_principais cannot be reverted.\n";
        return false;
    }
}
