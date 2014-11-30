<?php

use yii\db\Schema;

class m140412_133311_mudando_nome_bairro_tipo extends \yii\db\Migration
{
    public function up()
    {
        $this->execute("ALTER TABLE bairros RENAME COLUMN bairro_tipo_id TO bairro_categoria_id");
    }

    public function down()
    {
        echo "m140412_133311_mudando_nome_bairro_tipo cannot be reverted.\n";

        return false;
    }
}
